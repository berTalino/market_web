<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function welcome(): Response
    {
        // Загружаем популярные товары (30 товаров со всех магазинов)
        $featuredProducts = Product::query()
            ->with('shop')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit(30)
            ->get()
            ->append('image_url');

        // Промо-баннеры на основе градиентов (без внешних сервисов)
        $promoBanners = [
            [
                'title' => 'Новая коллекция',
                'subtitle' => 'Лучшие товары от проверенных магазинов',
                'badge' => 'Новинки',
                'gradient' => ['from' => '#6237F5', 'to' => '#2563EB'],
                'link' => '/shops',
                'cta' => 'Смотреть товары',
            ],
            [
                'title' => 'Скидки до 70%',
                'subtitle' => 'Успейте купить по выгодным ценам',
                'badge' => 'Распродажа',
                'gradient' => ['from' => '#CB11AB', 'to' => '#7C3AED'],
                'link' => '/shops',
                'cta' => 'За покупками',
            ],
            [
                'title' => 'Бесплатная доставка',
                'subtitle' => 'При заказе от 1 000 ₽ — доставка бесплатно',
                'badge' => 'Акция',
                'gradient' => ['from' => '#0EA5E9', 'to' => '#6366F1'],
                'link' => '/shops',
                'cta' => 'Выбрать товары',
            ],
        ];

        // Моковые данные для категорий
        $categories = [
            ['title' => 'Одежда', 'emoji' => '👗', 'color' => '#FFE5E5', 'link' => '/shops'],
            ['title' => 'Электроника', 'emoji' => '📱', 'color' => '#E5F3FF', 'link' => '/shops'],
            ['title' => 'Дом', 'emoji' => '🏠', 'color' => '#FFF4E5', 'link' => '/shops'],
            ['title' => 'Красота', 'emoji' => '💄', 'color' => '#FFE5FF', 'link' => '/shops'],
            ['title' => 'Спорт', 'emoji' => '⚽', 'color' => '#E5FFE5', 'link' => '/shops'],
            ['title' => 'Еда', 'emoji' => '🍔', 'color' => '#FFFFE5', 'link' => '/shops'],
        ];

        $shops = Shop::query()
            ->where('is_active', true)
            ->withCount([
                'products as active_products_count' => fn ($query) => $query
                    ->where('is_active', true)
                    ->where('stock', '>', 0),
            ])
            ->orderBy('name')
            ->get();

        return Inertia::render('Welcome', [
            'featuredProducts' => $featuredProducts,
            'promoBanners' => $promoBanners,
            'categories' => $categories,
            'shops' => $shops,
        ]);
    }

    public function index(Request $request): Response
    {
        $searchQuery = trim((string) $request->query('q', ''));
        $hasSearch = $searchQuery !== '';

        $shopsQuery = Shop::query()
            ->where('is_active', true)
            ->withCount([
                'products as active_products_count' => fn ($query) => $query
                    ->where('is_active', true)
                    ->where('stock', '>', 0),
            ]);

        if ($hasSearch) {
            $shopsQuery->where(function ($query) use ($searchQuery): void {
                $query
                    ->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('description', 'like', "%{$searchQuery}%")
                    ->orWhereHas('products', function ($productQuery) use ($searchQuery): void {
                        $productQuery
                            ->where('is_active', true)
                            ->where('stock', '>', 0)
                            ->where(function ($productSearchQuery) use ($searchQuery): void {
                                $productSearchQuery
                                    ->where('name', 'like', "%{$searchQuery}%")
                                    ->orWhere('description', 'like', "%{$searchQuery}%");
                            });
                    });
            });
        }

        $shops = $shopsQuery
            ->orderBy('name')
            ->get();

        $featuredProductsQuery = Product::query()
            ->with('shop')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereHas('shop', fn ($query) => $query->where('is_active', true));

        if ($hasSearch) {
            $featuredProductsQuery->where(function ($query) use ($searchQuery): void {
                $query
                    ->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('description', 'like', "%{$searchQuery}%")
                    ->orWhereHas('shop', function ($shopQuery) use ($searchQuery): void {
                        $shopQuery
                            ->where('name', 'like', "%{$searchQuery}%")
                            ->orWhere('description', 'like', "%{$searchQuery}%");
                    });
            });

            $featuredProductsQuery->orderBy('name');
        } else {
            $featuredProductsQuery->inRandomOrder();
        }

        $featuredProducts = $featuredProductsQuery
            ->limit(30)
            ->get()
            ->append('image_url');

        // Промо-баннеры на основе градиентов (без внешних сервисов)
        $promoBanners = [
            [
                'title' => 'Новая коллекция',
                'subtitle' => 'Лучшие товары от проверенных магазинов',
                'badge' => 'Новинки',
                'gradient' => ['from' => '#6237F5', 'to' => '#2563EB'],
                'link' => '/shops',
                'cta' => 'Смотреть товары',
            ],
            [
                'title' => 'Скидки до 70%',
                'subtitle' => 'Успейте купить по выгодным ценам',
                'badge' => 'Распродажа',
                'gradient' => ['from' => '#CB11AB', 'to' => '#7C3AED'],
                'link' => '/shops',
                'cta' => 'За покупками',
            ],
            [
                'title' => 'Бесплатная доставка',
                'subtitle' => 'При заказе от 1 000 ₽ — доставка бесплатно',
                'badge' => 'Акция',
                'gradient' => ['from' => '#0EA5E9', 'to' => '#6366F1'],
                'link' => '/shops',
                'cta' => 'Выбрать товары',
            ],
        ];

        // Моковые данные для категорий
        $categories = [
            ['title' => 'Одежда', 'emoji' => '👗', 'color' => '#FFE5E5', 'link' => '/shops'],
            ['title' => 'Электроника', 'emoji' => '📱', 'color' => '#E5F3FF', 'link' => '/shops'],
            ['title' => 'Дом', 'emoji' => '🏠', 'color' => '#FFF4E5', 'link' => '/shops'],
            ['title' => 'Красота', 'emoji' => '💄', 'color' => '#FFE5FF', 'link' => '/shops'],
            ['title' => 'Спорт', 'emoji' => '⚽', 'color' => '#E5FFE5', 'link' => '/shops'],
            ['title' => 'Еда', 'emoji' => '🍔', 'color' => '#FFFFE5', 'link' => '/shops'],
        ];

        return Inertia::render('Catalog/Shops/Index', [
            'shops' => $shops,
            'featuredProducts' => $featuredProducts,
            'promoBanners' => $promoBanners,
            'categories' => $categories,
            'searchQuery' => $searchQuery,
        ]);
    }

    public function showShop(Shop $shop): Response
    {
        abort_unless($shop->is_active, 404);

        return Inertia::render('Catalog/Shops/Show', [
            'shop' => $shop,
            'products' => $shop->products()
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->orderBy('name')
                ->get()
                ->append('image_url'),
        ]);
    }

    public function showProduct(Shop $shop, string $product): Response
    {
        abort_unless($shop->is_active, 404);

        $productModel = $shop->products()
            ->where('slug', $product)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->firstOrFail();

        return Inertia::render('Catalog/Products/Show', [
            'shop' => $shop,
            'product' => $productModel->append('image_url'),
        ]);
    }
}
