<?php

namespace App\Http\Controllers;

use App\Actions\CreateOrderFromCart;
use App\Http\Requests\Checkout\CheckoutRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CreateOrderFromCart $createOrderFromCart,
    ) {
    }

    public function create(): Response
    {
        return Inertia::render('Checkout/Create', [
            'cart' => $this->cartService->details(),
            'defaults' => [
                'customer_name' => '',
                'customer_phone' => '',
                'customer_email' => null,
                'comment' => null,
            ],
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $order = $this->createOrderFromCart->handle(
            $request->validated(),
            $request->session()->getId(),
        );

        return to_route('checkout.create')->with('success', "Заказ #{$order->id} создан.");
    }
}
