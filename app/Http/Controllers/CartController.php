<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(): Response
    {
        return Inertia::render('Cart/Index', [
            'cart' => $this->cartService->details(),
        ]);
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $product = Product::query()->with('shop')->findOrFail($request->integer('product_id'));
        $this->cartService->add($product, $request->integer('quantity'));

        return back()->with('success', 'Товар добавлен в корзину.');
    }

    public function update(UpdateCartItemRequest $request, Product $product): RedirectResponse
    {
        $product->load('shop');
        $this->cartService->update($product, $request->integer('quantity'));

        return back()->with('success', 'Количество товара обновлено.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cartService->remove($product);

        return back()->with('success', 'Товар удалён из корзины.');
    }
}
