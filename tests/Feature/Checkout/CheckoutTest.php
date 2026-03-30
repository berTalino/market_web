<?php

namespace Tests\Feature\Checkout;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_checkout_and_create_order_from_cart(): void
    {
        $shop = Shop::factory()->create([
            'is_active' => true,
        ]);
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'name' => 'Снимок товара',
            'slug' => 'snapshot-product',
            'price' => 1750,
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->withSession([
            'cart.items' => [$product->id => 3],
        ])->post(route('checkout.store'), [
            'customer_name' => 'Покупатель Гость',
            'customer_phone' => '+79990000000',
            'customer_email' => 'guest@example.com',
            'comment' => 'Позвонить перед доставкой',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('checkout.create'))
            ->assertSessionMissing('cart.items');

        $order = Order::query()->with('items')->sole();

        $this->assertSame(OrderStatus::PENDING, $order->status);
        $this->assertSame(5250, $order->subtotal_amount);
        $this->assertSame(5250, $order->total_amount);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => 'Покупатель Гость',
            'customer_phone' => '+79990000000',
            'customer_email' => 'guest@example.com',
            'comment' => 'Позвонить перед доставкой',
            'status' => OrderStatus::PENDING->value,
            'subtotal_amount' => 5250,
            'total_amount' => 5250,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'shop_id' => $shop->id,
            'product_id' => $product->id,
            'product_name' => 'Снимок товара',
            'unit_price' => 1750,
            'quantity' => 3,
            'line_total' => 5250,
        ]);

        $this->assertCount(1, $order->items);

        $product->refresh();

        $this->assertSame(7, $product->stock);
    }
}
