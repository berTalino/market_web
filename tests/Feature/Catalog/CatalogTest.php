<?php

namespace Tests\Feature\Catalog;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_only_active_shops_in_catalog(): void
    {
        $visibleShop = Shop::factory()->create([
            'name' => 'Видимый магазин',
            'slug' => 'vidimyy-magazin',
            'is_active' => true,
        ]);
        Shop::factory()->create([
            'name' => 'Скрытый магазин',
            'slug' => 'skrytyy-magazin',
            'is_active' => false,
        ]);

        Product::factory()->create([
            'shop_id' => $visibleShop->id,
            'is_active' => true,
            'stock' => 5,
        ]);

        $this->get(route('catalog.index'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Catalog/Shops/Index')
                ->has('shops', 1)
                ->where('shops.0.name', 'Видимый магазин')
            );
    }

    public function test_guest_does_not_see_inactive_products_on_shop_page(): void
    {
        $shop = Shop::factory()->create([
            'name' => 'Активный магазин',
            'slug' => 'aktivnyy-magazin',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'shop_id' => $shop->id,
            'name' => 'Активный товар',
            'slug' => 'aktivnyy-tovar',
            'is_active' => true,
            'stock' => 4,
        ]);
        Product::factory()->create([
            'shop_id' => $shop->id,
            'name' => 'Неактивный товар',
            'slug' => 'neaktivnyy-tovar',
            'is_active' => false,
            'stock' => 4,
        ]);

        $this->get(route('shops.show', $shop))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Catalog/Shops/Show')
                ->has('products', 1)
                ->where('products.0.name', 'Активный товар')
            );
    }

    public function test_inactive_shop_and_inactive_product_are_not_available_publicly(): void
    {
        $inactiveShop = Shop::factory()->create([
            'slug' => 'inactive-shop',
            'is_active' => false,
        ]);

        $shop = Shop::factory()->create([
            'slug' => 'public-shop',
            'is_active' => true,
        ]);
        $inactiveProduct = Product::factory()->create([
            'shop_id' => $shop->id,
            'slug' => 'inactive-product',
            'is_active' => false,
            'stock' => 5,
        ]);

        $this->get(route('shops.show', $inactiveShop))
            ->assertNotFound();

        $this->get(route('products.show', [$shop, $inactiveProduct->slug]))
            ->assertNotFound();
    }

    public function test_guest_can_search_shops_and_products_in_catalog(): void
    {
        $matchedShop = Shop::factory()->create([
            'name' => 'Техника 24',
            'slug' => 'tehnika-24',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'shop_id' => $matchedShop->id,
            'name' => 'Пылесос Turbo',
            'slug' => 'pylesos-turbo',
            'is_active' => true,
            'stock' => 3,
        ]);

        $otherShop = Shop::factory()->create([
            'name' => 'Дом и уют',
            'slug' => 'dom-i-uyut',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'shop_id' => $otherShop->id,
            'name' => 'Подушка Basic',
            'slug' => 'podushka-basic',
            'is_active' => true,
            'stock' => 5,
        ]);

        $this->get(route('catalog.index', ['q' => 'turbo']))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Catalog/Shops/Index')
                ->where('searchQuery', 'turbo')
                ->has('shops', 1)
                ->where('shops.0.name', 'Техника 24')
                ->has('featuredProducts', 1)
                ->where('featuredProducts.0.name', 'Пылесос Turbo')
            );
    }

    public function test_catalog_search_returns_empty_results_for_unknown_query(): void
    {
        $shop = Shop::factory()->create([
            'name' => 'Электроника плюс',
            'slug' => 'elektronika-plyus',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'shop_id' => $shop->id,
            'name' => 'Наушники Pro',
            'slug' => 'naushniki-pro',
            'is_active' => true,
            'stock' => 6,
        ]);

        $this->get(route('catalog.index', ['q' => 'nesuschestvuet']))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Catalog/Shops/Index')
                ->where('searchQuery', 'nesuschestvuet')
                ->has('shops', 0)
                ->has('featuredProducts', 0)
            );
    }
}
