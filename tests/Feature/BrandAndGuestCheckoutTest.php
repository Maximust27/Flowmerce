<?php

namespace Tests\Feature;

use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandAndGuestCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_reusable_brand_images_and_browser_favicon_exist(): void
    {
        $this->assertFileExists(public_path('images/branding/flowmerce-icon.webp'));
        $this->assertFileExists(public_path('images/branding/flowmerce-mark.svg'));
        $this->assertFileExists(public_path('images/branding/flowmerce-favicon.png'));

        $this->get('/')
            ->assertOk()
            ->assertSee('images/branding/flowmerce-icon.webp')
            ->assertSee('images/branding/flowmerce-favicon.png');

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('images/branding/flowmerce-icon.webp')
            ->assertSee('images/branding/flowmerce-favicon.png');
    }

    public function test_table_menu_uses_the_same_brand_and_keeps_all_payment_sections(): void
    {
        $owner = User::factory()->create([
            'role' => 'admin',
            'business_name' => 'Kedai Flowmerce',
            'gopay_qr_image' => 'qris-qr/existing.png',
        ]);

        $table = Table::create([
            'user_id' => $owner->id,
            'table_number' => 'Meja 01',
            'table_code' => Table::generateTableCode(),
            'is_active' => true,
        ]);

        $this->get(route('guest.menu', ['tableCode' => $table->table_code]))
            ->assertOk()
            ->assertSee('images/branding/flowmerce-icon.webp')
            ->assertSee('images/branding/flowmerce-favicon.png')
            ->assertSee('data-cart-items', false)
            ->assertSee('data-subtotal', false)
            ->assertSee('data-tax', false)
            ->assertSee('data-qris-preview', false)
            ->assertSee('data-submit-order', false);

        $css = file_get_contents(resource_path('css/flowmerce-qr.css'));
        $this->assertStringContainsString('QR checkout layout fix', $css);
        $this->assertStringContainsString('overscroll-behavior-y: contain;', $css);
        $this->assertStringContainsString('.fm-guest__cart-items > *', $css);
    }
}
