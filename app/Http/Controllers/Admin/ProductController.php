<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Products/Index', [
            'products' => Product::query()
                ->with('shop:id,name,slug,is_active')
                ->latest()
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Create', [
            'shops' => Shop::query()
                ->select(['id', 'name', 'slug', 'is_active'])
                ->orderBy('name')
                ->get(),
            'defaults' => [
                'shop_id' => null,
                'name' => '',
                'slug' => '',
                'description' => null,
                'price' => 0,
                'stock' => 0,
                'is_active' => true,
            ],
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::query()->create($request->validated());

        return to_route('admin.products.index')->with('success', 'Товар создан.');
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product->load('shop:id,name,slug,is_active'),
            'shops' => Shop::query()
                ->select(['id', 'name', 'slug', 'is_active'])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return to_route('admin.products.index')->with('success', 'Товар обновлён.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('admin.products.index')->with('success', 'Товар удалён.');
    }
}
