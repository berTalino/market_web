<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        unset($data['image']);

        Product::query()->create($data);

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
        $data = $request->validated();
        $oldImagePath = $product->getRawOriginal('image_path');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        unset($data['image']);

        $product->update($data);

        if (isset($data['image_path']) && $oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return to_route('admin.products.index')->with('success', 'Товар обновлён.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->getRawOriginal('image_path')) {
            Storage::disk('public')->delete($product->getRawOriginal('image_path'));
        }

        $product->delete();

        return to_route('admin.products.index')->with('success', 'Товар удалён.');
    }
}
