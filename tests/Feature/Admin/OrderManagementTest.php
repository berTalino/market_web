<?php

namespace Tests\Feature\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_order_list_and_order_card(): void
    {
        $admin = User::factory()->admin()->create();
        $shop = Shop::factory()->create(['name' => 'Магазин для заказа']);
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'name' => 'Товар для заказа',
            'slug' => 'tovar-dlya-zakaza',
        ]);
        $order = Order::factory()->create([
            'customer_name' => 'Иван Покупатель',
            'status' => OrderStatus::PENDING,
            'subtotal_amount' => 2400,
            'total_amount' => 2400,
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'shop_id' => $shop->id,
            'product_name' => $product->name,
            'unit_price' => 1200,
            'quantity' => 2,
            'line_total' => 2400,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Admin/Orders/Index')
                ->where('orders.0.customer_name', 'Иван Покупатель')
            );

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Admin/Orders/Show')
                ->where('order.customer_name', 'Иван Покупатель')
                ->where('order.items.0.shop.name', 'Магазин для заказа')
                ->where('order.items.0.product_name', 'Товар для заказа')
            );
    }

    public function test_non_admin_user_cannot_view_orders(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.orders.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.orders.show', $order))
            ->assertForbidden();
    }
}
