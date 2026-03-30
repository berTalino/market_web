<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product_and_bind_it_to_shop(): void
    {
        $admin = User::factory()->admin()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'shop_id' => $shop->id,
            'name' => 'Тестовый товар',
            'slug' => 'testovyy-tovar',
            'description' => 'Описание товара',
            'price' => 15990,
            'stock' => 7,
            'is_active' => true,
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'shop_id' => $shop->id,
            'name' => 'Тестовый товар',
            'slug' => 'testovyy-tovar',
            'price' => 15990,
            'stock' => 7,
            'is_active' => true,
        ]);
    }

    public function test_product_creation_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), [
                'shop_id' => 999999,
                'name' => '',
                'slug' => 'bad slug',
                'description' => ['invalid'],
                'price' => 0,
                'stock' => -1,
            ]);

        $response
            ->assertRedirect(route('admin.products.create'))
            ->assertSessionHasErrors(['shop_id', 'name', 'slug', 'description', 'price', 'stock', 'is_active']);
    }

    public function test_admin_can_update_product_and_move_it_to_another_shop(): void
    {
        $admin = User::factory()->admin()->create();
        $oldShop = Shop::factory()->create();
        $newShop = Shop::factory()->create();
        $product = Product::factory()->create([
            'shop_id' => $oldShop->id,
            'name' => 'Старый товар',
            'slug' => 'staryy-tovar',
            'price' => 1000,
            'stock' => 3,
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product), [
            'shop_id' => $newShop->id,
            'name' => 'Новый товар',
            'slug' => 'novyy-tovar',
            'description' => 'Обновлённое описание',
            'price' => 2500,
            'stock' => 12,
            'is_active' => true,
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'shop_id' => $newShop->id,
            'name' => 'Новый товар',
            'slug' => 'novyy-tovar',
            'price' => 2500,
            'stock' => 12,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
