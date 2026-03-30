<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_routes_to_login(): void
    {
        foreach ([
            route('admin.shops.index'),
            route('admin.products.index'),
            route('admin.orders.index'),
        ] as $url) {
            $this->get($url)
                ->assertRedirect(route('login'));
        }
    }

    public function test_non_admin_user_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create();

        foreach ([
            route('admin.shops.index'),
            route('admin.products.index'),
            route('admin.orders.index'),
        ] as $url) {
            $this->actingAs($user)
                ->get($url)
                ->assertForbidden();
        }
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.shops.index'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page->component('Admin/Shops/Index'));

        $this->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page->component('Admin/Products/Index'));

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page->component('Admin/Orders/Index'));
    }
}
