<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Catalog/Shops/Index', [
            'shops' => Shop::query()
                ->where('is_active', true)
                ->withCount([
                    'products as active_products_count' => fn ($query) => $query
                        ->where('is_active', true)
                        ->where('stock', '>', 0),
                ])
                ->orderBy('name')
                ->get(),
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
                ->get(),
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
            'product' => $productModel,
        ]);
    }
}
