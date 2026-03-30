<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    private const SESSION_KEY = 'cart.items';

    public function __construct(private readonly SessionStore $session)
    {
    }

    /**
     * @return array<int, int>
     */
    public function rawItems(): array
    {
        $items = $this->session->get(self::SESSION_KEY, []);

        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->mapWithKeys(fn ($quantity, $productId) => [(int) $productId => max(0, (int) $quantity)])
            ->filter(fn (int $quantity) => $quantity > 0)
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function details(): array
    {
        $rawItems = $this->rawItems();

        if ($rawItems === []) {
            return $this->emptyCart();
        }

        $products = Product::query()
            ->with('shop')
            ->whereIn('id', array_keys($rawItems))
            ->get()
            ->keyBy('id');

        $items = [];
        $normalized = [];
        $subtotal = 0;
        $count = 0;

        foreach ($rawItems as $productId => $requestedQuantity) {
            /** @var Product|null $product */
            $product = $products->get($productId);

            if (! $product || ! $product->is_active || ! $product->shop?->is_active || $product->stock < 1) {
                continue;
            }

            $quantity = min($requestedQuantity, $product->stock);

            if ($quantity < 1) {
                continue;
            }

            $lineTotal = $product->price * $quantity;

            $items[] = [
                'product_id' => $product->id,
                'shop_id' => $product->shop_id,
                'shop_name' => $product->shop?->name,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'quantity' => $quantity,
                'stock' => $product->stock,
                'line_total' => $lineTotal,
                'is_active' => $product->is_active,
            ];

            $normalized[$product->id] = $quantity;
            $subtotal += $lineTotal;
            $count += $quantity;
        }

        if ($normalized !== $rawItems) {
            $this->persist($normalized);
        }

        return [
            'items' => $items,
            'count' => $count,
            'subtotal_amount' => $subtotal,
            'total_amount' => $subtotal,
            'is_empty' => $items === [],
        ];
    }

    public function count(): int
    {
        return (int) $this->details()['count'];
    }

    /**
     * @return array<string, mixed>
     */
    public function add(Product $product, int $quantity): array
    {
        $this->assertAvailable($product);

        $items = $this->rawItems();
        $newQuantity = ($items[$product->id] ?? 0) + $quantity;

        if ($newQuantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => 'Недостаточно товара на складе для добавления в корзину.',
            ]);
        }

        $items[$product->id] = $newQuantity;
        $this->persist($items);

        return $this->details();
    }

    /**
     * @return array<string, mixed>
     */
    public function update(Product $product, int $quantity): array
    {
        $this->assertAvailable($product);

        if ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => 'Запрошенное количество превышает доступный остаток.',
            ]);
        }

        $items = $this->rawItems();
        $items[$product->id] = $quantity;

        $this->persist($items);

        return $this->details();
    }

    /**
     * @return array<string, mixed>
     */
    public function remove(Product $product): array
    {
        $items = $this->rawItems();
        unset($items[$product->id]);

        $this->persist($items);

        return $this->details();
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    private function assertAvailable(Product $product): void
    {
        if (! $product->is_active || ! $product->shop?->is_active || $product->stock < 1) {
            throw ValidationException::withMessages([
                'product_id' => 'Товар недоступен для добавления в корзину.',
            ]);
        }
    }

    /**
     * @param  array<int, int>  $items
     */
    private function persist(array $items): void
    {
        if ($items === []) {
            $this->clear();

            return;
        }

        $this->session->put(self::SESSION_KEY, $items);
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyCart(): array
    {
        return [
            'items' => [],
            'count' => 0,
            'subtotal_amount' => 0,
            'total_amount' => 0,
            'is_empty' => true,
        ];
    }
}
