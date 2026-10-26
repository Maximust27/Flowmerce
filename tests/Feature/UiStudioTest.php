<?php

namespace Tests\Feature;

use App\Livewire\InventoryManager;
use App\Livewire\TransactionManager;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UiStudioTest extends TestCase
{
    use RefreshDatabase;

    private function product(User $user, string $name, string $category, int $stock): Product
    {
        return Product::create([
            'user_id' => $user->id,
            'name' => $name,
            'category' => $category,
            'buy_price' => 10000,
            'sell_price' => 15000,
            'current_stock' => $stock,
            'min_stock_alert' => 5,
        ]);
    }

    public function test_studio_dashboard_uses_real_income_and_stock_information(): void
    {
        $owner = User::factory()->create(['role' => 'admin', 'business_name' => 'Toko Studio']);
        $other = User::factory()->create(['role' => 'admin', 'business_name' => 'Toko Lain']);

        $this->product($owner, 'Teh Premium', 'Minuman', 2);
        $this->product($other, 'Produk Tenant Lain', 'Minuman', 1);
        Transaction::create([
            'user_id' => $owner->id, 'type' => 'INCOME',
            'amount' => 90000, 'category' => 'Penjualan',
        ]);

        $this->actingAs($owner)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('fm-overview-grid')
            ->assertSee('Teh Premium')
            ->assertDontSee('Produk Tenant Lain')
            ->assertSee('90.000')
            ->assertSee('fm-command');
    }

    public function test_inventory_live_search_filter_and_tenant_separation(): void
    {
        $owner = User::factory()->create(['role' => 'admin']);
        $other = User::factory()->create(['role' => 'admin']);
        $this->product($owner, 'Kopi Arabika', 'Minuman', 0);
        $this->product($owner, 'Roti Tawar', 'Makanan', 21);
        $this->product($other, 'Kopi Tenant Lain', 'Minuman', 0);

        $this->actingAs($owner);
        Livewire::test(InventoryManager::class)
            ->assertSee('Kopi Arabika')
            ->assertSee('Roti Tawar')
            ->assertDontSee('Kopi Tenant Lain')
            ->set('search', 'Kopi')
            ->assertSee('Kopi Arabika')
            ->assertDontSee('Roti Tawar')
            ->set('stockFilter', 'out')
            ->assertSee('Kopi Arabika')
            ->set('categoryFilter', 'Makanan')
            ->assertDontSee('Kopi Arabika');
    }

    public function test_transaction_search_and_type_filters_are_user_scoped(): void
    {
        $owner = User::factory()->create(['role' => 'admin']);
        $other = User::factory()->create(['role' => 'admin']);

        Transaction::create(['user_id' => $owner->id, 'type' => 'INCOME', 'amount' => 20000, 'category' => 'Kopi']);
        Transaction::create(['user_id' => $owner->id, 'type' => 'EXPENSE', 'amount' => 7000, 'category' => 'Listrik']);
        Transaction::create(['user_id' => $other->id, 'type' => 'EXPENSE', 'amount' => 8000, 'category' => 'Rahasia Tenant']);

        $this->actingAs($owner);
        Livewire::test(TransactionManager::class)
            ->assertSee('Kopi')
            ->assertSee('Listrik')
            ->assertDontSee('Rahasia Tenant')
            ->set('typeFilter', 'income')
            ->assertSee('Kopi')
            ->assertDontSee('Listrik')
            ->set('typeFilter', 'all')
            ->set('search', 'Listrik')
            ->assertSee('Listrik')
            ->assertDontSee('Kopi');
    }
}
