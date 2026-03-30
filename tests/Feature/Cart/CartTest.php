<?php

namespace Tests\Feature\Cart;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_product_to_cart(): void
    {
        $product = $this->availableProduct([
            'stock' => 5,
        ]);

        $response = $this->from(route('catalog.index'))
            ->post(route('cart.items.store'), [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('catalog.index'))
            ->assertSessionHas('cart.items', function (array $items) use ($product): bool {
                return (int) ($items[$product->id] ?? 0) === 2;
            });
    }

    public function test_guest_can_update_product_quantity_in_cart(): void
    {
        $product = $this->availableProduct([
            'stock' => 6,
        ]);

        $response = $this->withSession([
            'cart.items' => [$product->id => 1],
        ])->from(route('cart.index'))
            ->patch(route('cart.items.update', $product), [
                'quantity' => 4,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('cart.items', function (array $items) use ($product): bool {
                return (int) ($items[$product->id] ?? 0) === 4;
            });
    }

    public function test_guest_can_remove_product_from_cart(): void
    {
        $product = $this->availableProduct();

        $response = $this->withSession([
            'cart.items' => [$product->id => 2],
        ])->from(route('cart.index'))
            ->delete(route('cart.items.destroy', $product));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('cart.index'))
            ->assertSessionMissing('cart.items');
    }

    public function test_cart_rejects_unavailable_product(): void
    {
        $shop = Shop::factory()->create([
            'is_active' => false,
        ]);
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'is_active' => true,
            'stock' => 5,
        ]);

        $response = $this->from(route('catalog.index'))
            ->post(route('cart.items.store'), [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

        $response
            ->assertRedirect(route('catalog.index'))
            ->assertSessionHasErrors('product_id');
    }

    public function test_cart_rejects_quantity_above_stock_when_adding_or_updating(): void
    {
        $product = $this->availableProduct([
            'stock' => 2,
        ]);

        $addResponse = $this->from(route('catalog.index'))
            ->post(route('cart.items.store'), [
                'product_id' => $product->id,
                'quantity' => 3,
            ]);

        $addResponse
            ->assertRedirect(route('catalog.index'))
            ->assertSessionHasErrors('quantity');

        $updateResponse = $this->withSession([
            'cart.items' => [$product->id => 1],
        ])->from(route('cart.index'))
            ->patch(route('cart.items.update', $product), [
                'quantity' => 3,
            ]);

        $updateResponse
            ->assertRedirect(route('cart.index'))
            ->assertSessionHasErrors('quantity');
    }

    private function availableProduct(array $attributes = []): Product
    {
        $shop = Shop::factory()->create([
            'is_active' => true,
        ]);

        return Product::factory()->create([
            'shop_id' => $shop->id,
            'is_active' => true,
            'stock' => 5,
            ...$attributes,
        ]);
    }
}
