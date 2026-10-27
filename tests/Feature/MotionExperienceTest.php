<?php

namespace Tests\Feature;

use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MotionExperienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_gsap_motion_module_is_bundled_and_lifecycle_safe(): void
    {
        $package = json_decode(file_get_contents(base_path('package.json')), true);
        $app = file_get_contents(resource_path('js/app.js'));
        $motion = file_get_contents(resource_path('js/flowmerce-motion.js'));
        $stylesheet = file_get_contents(resource_path('css/flowmerce-motion.css'));

        $this->assertArrayHasKey('gsap', $package['dependencies']);
        $this->assertStringContainsString("import './flowmerce-motion'", $app);
        $this->assertStringContainsString("from 'gsap'", $motion);
        $this->assertStringContainsString("from 'gsap/ScrollTrigger'", $motion);
        $this->assertStringContainsString("gsap.context(", $motion);
        $this->assertStringContainsString('sceneContext.revert()', $motion);
        $this->assertStringContainsString("'livewire:navigating'", $motion);
        $this->assertStringContainsString("'livewire:navigated'", $motion);
        $this->assertStringContainsString("'morph.added'", $motion);
        $this->assertStringContainsString('prefers-reduced-motion: reduce', $motion);
        $this->assertStringContainsString('fm-gsap-enabled', $stylesheet);
        $this->assertStringContainsString('fm-modal__panel', $stylesheet);
        $this->assertStringNotContainsString('animation: infinite', $stylesheet);
    }

    public function test_landing_and_authentication_use_motion_roots_without_changing_routes(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('data-fm-motion-root="public"', false);
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('data-fm-motion-root="auth"', false);
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('data-fm-motion-root="auth"', false);
    }

    public function test_landing_orbit_contains_six_features_and_two_floating_shapes(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('data-fm-orbit', false)
            ->assertSee('data-fm-orbit-track="outer"', false)
            ->assertSee('data-fm-orbit-track="inner"', false)
            ->assertSee('data-fm-float="square"', false)
            ->assertSee('data-fm-float="circle"', false)
            ->assertSee('fm-public-showcase__brand-image');

        $this->assertSame(6, substr_count($response->getContent(), 'data-fm-orbit-satellite'));

        $motion = file_get_contents(resource_path('js/flowmerce-motion.js'));
        $this->assertStringContainsString('function orbitTimeline(', $motion);
        $this->assertStringContainsString('function floatHeroDecor(', $motion);
        $this->assertStringContainsString('function watchAmbient(', $motion);
        $this->assertStringContainsString('visibilitychange', $motion);
        $this->assertStringContainsString("orbitTimeline(outer, 32, 'clockwise')", $motion);
    }

    public function test_internal_modules_share_subtle_transitions_without_css_flash(): void
    {
        $owner = User::factory()->create([
            'role' => 'admin',
            'business_name' => 'Studio Tanpa Kedip',
        ]);

        foreach ([
            'dashboard',
            'inventaris.index',
            'gudang.index',
            'keuangan.index',
            'pegawai.index',
            'meja.index',
        ] as $route) {
            $this->actingAs($owner)
                ->get(route($route))
                ->assertOk()
                ->assertSee('data-fm-motion-root="app"', false);
        }

        $motion = file_get_contents(resource_path('js/flowmerce-motion.js'));
        $styles = file_get_contents(resource_path('css/flowmerce-motion.css'));
        $this->assertStringContainsString('function softEntrance(', $motion);
        $this->assertStringContainsString('subtle: true', $motion);
        $this->assertStringContainsString('immediateRender: !subtle', $motion);
        $this->assertStringContainsString('.fm-app .fm-page', $styles);
        $this->assertStringContainsString('animation: none !important', $styles);
        $this->assertStringContainsString('transform: none !important', $styles);
    }

    public function test_dashboard_pos_and_guest_menu_use_isolated_motion_roots(): void
    {
        $owner = User::factory()->create([
            'role' => 'admin',
            'business_name' => 'Kedai Motion',
        ]);

        $this->actingAs($owner)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('data-fm-motion-root="app"', false);

        $this->actingAs($owner)->get(route('pos.index'))
            ->assertOk()
            ->assertSee('data-fm-motion-root="pos"', false);

        $table = Table::create([
            'user_id' => $owner->id,
            'table_number' => 'Meja 07',
            'table_code' => Table::generateTableCode(),
            'is_active' => true,
        ]);

        $this->get(route('guest.menu', ['tableCode' => $table->table_code]))
            ->assertOk()
            ->assertSee('data-fm-motion-root="guest"', false)
            ->assertSee('data-qris', false);
    }
}
