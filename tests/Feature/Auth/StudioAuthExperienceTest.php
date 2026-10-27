<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudioAuthExperienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_uses_shared_brand_and_product_preview(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('fm-public-hero')
            ->assertSee('fm-product-preview')
            ->assertSee('Semua urusan bisnis')
            ->assertSee('Buat akun')
            ->assertSee('flowmerce-experience');
    }

    public function test_login_register_and_forgot_password_share_one_guest_layout(): void
    {
        foreach (['login', 'register', 'password.request'] as $route) {
            $this->get(route($route))
                ->assertOk()
                ->assertSee('fm-auth-layout')
                ->assertSee('fm-auth-panel')
                ->assertSee('fm-product-preview')
                ->assertSee('flowmerce-experience');
        }
    }

    public function test_registration_renders_all_business_fields_without_changing_contract(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('name="business_name"', false)
            ->assertSee('name="business_category"', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('fm-auth-panel--wide');
    }

    public function test_login_has_password_visibility_control_and_role_neutral_copy(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('fm-field__toggle')
            ->assertSee('form.email')
            ->assertSee('form.password')
            ->assertSee('pemilik usaha dan kasir');
    }

    public function test_verify_email_and_confirm_password_use_same_layout(): void
    {
        $user = User::factory()->unverified()->create([
            'role' => 'admin',
            'business_name' => 'Studio Coba',
        ]);

        $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertOk()
            ->assertSee('fm-auth-layout')
            ->assertSee($user->email);

        $this->actingAs($user)
            ->get(route('password.confirm'))
            ->assertOk()
            ->assertSee('fm-auth-layout')
            ->assertSee('fm-field__toggle');
    }

    public function test_profile_is_integrated_and_cashier_does_not_see_admin_navigation(): void
    {
        $cashier = User::factory()->create([
            'role' => 'cashier',
            'business_name' => 'Studio Kasir',
        ]);

        $this->actingAs($cashier)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee('fm-account__identity')
            ->assertSee('Studio Kasir')
            ->assertSee('fm-account__layout')
            ->assertDontSee('href="'.route('inventaris.index').'"', false)
            ->assertDontSee('href="'.route('pegawai.index').'"', false)
            ->assertSee('href="'.route('pos.index').'"', false);
    }
}
