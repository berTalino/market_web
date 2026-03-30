<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopRequest;
use App\Http\Requests\Admin\UpdateShopRequest;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Shops/Index', [
            'shops' => Shop::query()
                ->with('owner:id,name,email')
                ->latest()
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Shops/Create', [
            'owners' => User::query()
                ->select(['id', 'name', 'email', 'role'])
                ->orderBy('name')
                ->get(),
            'defaults' => [
                'name' => '',
                'slug' => '',
                'description' => null,
                'owner_id' => null,
                'is_active' => true,
            ],
        ]);
    }

    public function store(StoreShopRequest $request): RedirectResponse
    {
        Shop::query()->create($request->validated());

        return to_route('admin.shops.index')->with('success', 'Магазин создан.');
    }

    public function edit(Shop $shop): Response
    {
        return Inertia::render('Admin/Shops/Edit', [
            'shop' => $shop->load('owner:id,name,email'),
            'owners' => User::query()
                ->select(['id', 'name', 'email', 'role'])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(UpdateShopRequest $request, Shop $shop): RedirectResponse
    {
        $shop->update($request->validated());

        return to_route('admin.shops.index')->with('success', 'Магазин обновлён.');
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $shop->delete();

        return to_route('admin.shops.index')->with('success', 'Магазин удалён.');
    }
}
