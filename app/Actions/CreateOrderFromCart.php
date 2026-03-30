<?php

namespace App\Actions;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateOrderFromCart
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * @param  array<string, mixed>  $checkoutData
     */
    public function handle(array $checkoutData, string $sessionId): Order
    {
        $cart = $this->cartService->details();

        if (($cart['items'] ?? []) === []) {
            throw ValidationException::withMessages([
                'cart' => 'Корзина пуста.',
            ]);
        }

        return DB::transaction(function () use ($cart, $checkoutData, $sessionId): Order {
            $items = collect($cart['items']);
            $products = Product::query()
                ->with('shop')
                ->lockForUpdate()
                ->whereIn('id', $items->pluck('product_id')->all())
                ->get()
                ->keyBy('id');

            $subtotal = 0;
            $orderItems = [];

            foreach ($items as $item) {
                /** @var Product|null $product */
                $product = $products->get($item['product_id']);

                if (! $product || ! $product->is_active || ! $product->shop?->is_active || $product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'cart' => 'Один или несколько товаров больше недоступны в нужном количестве.',
                    ]);
                }

                $lineTotal = $product->price * $item['quantity'];
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'shop_id' => $product->shop_id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'line_total' => $lineTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $order = Order::query()->create([
                ...Arr::only($checkoutData, ['customer_name', 'customer_phone', 'customer_email', 'comment']),
                'status' => OrderStatus::PENDING,
                'subtotal_amount' => $subtotal,
                'total_amount' => $subtotal,
                'session_id' => $sessionId,
            ]);

            $order->items()->createMany($orderItems);

            foreach ($items as $item) {
                $products[$item['product_id']]->decrement('stock', $item['quantity']);
            }

            $this->cartService->clear();

            return $order->load('items.shop', 'items.product');
        });
    }
}
