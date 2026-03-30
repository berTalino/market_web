<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Marketplace Admin',
            'role' => UserRole::ADMIN,
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        $owners = User::factory()
            ->count(2)
            ->owner()
            ->create();

        $shops = collect([
            Shop::factory()->active()->create(['owner_id' => $owners[0]->id]),
            Shop::factory()->active()->create(['owner_id' => $owners[1]->id]),
            Shop::factory()->active()->create(['owner_id' => null]),
        ]);

        $shops->each(fn (Shop $shop) => Product::factory()
            ->count(5)
            ->for($shop)
            ->active()
            ->create());

        $products = Product::query()->get();

        Order::factory()
            ->count(3)
            ->create()
            ->each(function (Order $order) use ($products): void {
                $subtotal = 0;

                $products
                    ->shuffle()
                    ->take(fake()->numberBetween(2, 4))
                    ->each(function (Product $product) use ($order, &$subtotal): void {
                        $quantity = fake()->numberBetween(1, 3);
                        $lineTotal = $product->price * $quantity;

                        OrderItem::factory()->create([
                            'order_id' => $order->id,
                            'shop_id' => $product->shop_id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'unit_price' => $product->price,
                            'quantity' => $quantity,
                            'line_total' => $lineTotal,
                        ]);

                        $subtotal += $lineTotal;
                    });

                $order->update([
                    'subtotal_amount' => $subtotal,
                    'total_amount' => $subtotal,
                ]);
            });

        $admin->refresh();
    }
}
