<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UiRemakeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_landing_page_uses_remade_workspace_design(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('fm-welcome')
            ->assertSee('Flowmerce')
            ->assertSee('Inventaris');
    }

    public function test_admin_can_render_all_primary_workspace_pages(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'business_name' => 'Toko Contoh',
            'business_category' => 'Ritel',
        ]);

        foreach ([
            'dashboard',
            'inventaris.index',
            'gudang.index',
            'keuangan.index',
            'pegawai.index',
            'chat.index',
        ] as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }
    }

    public function test_cashier_can_access_pos_but_not_admin_workspace(): void
    {
        $cashier = User::factory()->create([
            'role' => 'cashier',
            'business_name' => 'Toko Contoh',
            'business_category' => 'Ritel',
        ]);

        $this->actingAs($cashier)->get(route('pos.index'))
            ->assertOk()
            ->assertSee('fm-pos')
            ->assertSee('Kasir', false);

        $this->actingAs($cashier)->get(route('dashboard'))
            ->assertRedirect(route('pos.index'));
    }
}
