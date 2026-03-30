<?php

namespace Tests\Feature\Admin;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_shop(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();

        $response = $this->actingAs($admin)->post(route('admin.shops.store'), [
            'name' => 'Первый магазин',
            'slug' => 'perviy-magazin',
            'description' => 'Описание магазина',
            'owner_id' => $owner->id,
            'is_active' => true,
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.shops.index'));

        $this->assertDatabaseHas('shops', [
            'name' => 'Первый магазин',
            'slug' => 'perviy-magazin',
            'owner_id' => $owner->id,
            'is_active' => true,
        ]);
    }

    public function test_shop_creation_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.shops.create'))
            ->post(route('admin.shops.store'), [
                'name' => '',
                'slug' => 'bad slug',
                'description' => ['invalid'],
                'owner_id' => 999999,
            ]);

        $response
            ->assertRedirect(route('admin.shops.create'))
            ->assertSessionHasErrors(['name', 'slug', 'description', 'owner_id', 'is_active']);
    }

    public function test_admin_can_update_shop(): void
    {
        $admin = User::factory()->admin()->create();
        $oldOwner = User::factory()->owner()->create();
        $newOwner = User::factory()->owner()->create();
        $shop = Shop::factory()->create([
            'owner_id' => $oldOwner->id,
            'name' => 'Старое имя',
            'slug' => 'staryy-slug',
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.shops.update', $shop), [
            'name' => 'Новое имя',
            'slug' => 'novoe-imya',
            'description' => 'Новое описание',
            'owner_id' => $newOwner->id,
            'is_active' => true,
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.shops.index'));

        $this->assertDatabaseHas('shops', [
            'id' => $shop->id,
            'name' => 'Новое имя',
            'slug' => 'novoe-imya',
            'description' => 'Новое описание',
            'owner_id' => $newOwner->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_shop(): void
    {
        $admin = User::factory()->admin()->create();
        $shop = Shop::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.shops.destroy', $shop));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.shops.index'));

        $this->assertDatabaseMissing('shops', [
            'id' => $shop->id,
        ]);
    }
}
