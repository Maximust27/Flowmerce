<?php

namespace Tests\Feature;

use App\Livewire\Pos\Terminal;
use App\Livewire\TableManager;
use App\Models\GuestOrder;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TableQrTest extends TestCase
{
    use RefreshDatabase;

    private function owner(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'business_name' => 'Kedai Studio',
        ]);
    }

    private function product(User $owner, string $name = 'Kopi Studio', int $stock = 15): Product
    {
        return Product::create([
            'user_id' => $owner->id,
            'name' => $name,
            'category' => 'Minuman',
            'buy_price' => 8000,
            'sell_price' => 20000,
            'current_stock' => $stock,
            'min_stock_alert' => 5,
            'is_available_online' => true,
        ]);
    }

    private function table(User $owner, string $number = 'Meja 01'): Table
    {
        return Table::create([
            'user_id' => $owner->id,
            'table_number' => $number,
            'table_code' => Table::generateTableCode(),
            'is_active' => true,
        ]);
    }

    public function test_admin_can_manage_tables_and_cashier_cannot(): void
    {
        $owner = $this->owner();
        $this->actingAs($owner)->get(route('meja.index'))->assertOk()->assertSee('Meja &amp; QR', false);

        Livewire::actingAs($owner)->test(TableManager::class)
            ->call('openAdd')
            ->set('table_number', 'Meja 03')
            ->set('is_active', true)
            ->call('save')
            ->assertHasNoErrors();

        $table = Table::where('user_id', $owner->id)->firstOrFail();
        $this->assertSame('Meja 03', $table->table_number);

        Livewire::actingAs($owner)->test(TableManager::class)
            ->call('toggleActive', $table->id);

        $this->assertFalse($table->fresh()->is_active);

        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier)->get(route('meja.index'))
            ->assertRedirect(route('pos.index'));
    }

    public function test_qr_menu_is_public_and_never_shows_other_business_products(): void
    {
        $owner = $this->owner();
        $other = User::factory()->create(['role' => 'admin']);
        $table = $this->table($owner);
        $this->product($owner);
        $this->product($other, 'Rahasia Bisnis Lain');
        $unlisted = $this->product($owner, 'Menu Disembunyikan');
        $unlisted->update(['is_available_online' => false]);

        $this->get(route('guest.menu', ['tableCode' => $table->table_code]))
            ->assertOk()
            ->assertSee('Kopi Studio')
            ->assertDontSee('Rahasia Bisnis Lain')
            ->assertDontSee('Menu Disembunyikan')
            ->assertSee('fm-guest__catalog');

        $table->update(['is_active' => false]);
        $this->get(route('guest.menu', ['tableCode' => $table->table_code]))
            ->assertOk()->assertSee('Meja sedang tidak aktif');
    }

    public function test_guest_order_uses_server_price_and_starts_unpaid(): void
    {
        $owner = $this->owner();
        $table = $this->table($owner);
        $product = $this->product($owner);

        $this->postJson(route('guest.order.store', ['tableCode' => $table->table_code]), [
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
            'payment_method' => 'CASHIER',
            'total' => 1,
        ])->assertCreated()->assertJsonPath('success', true);

        $order = GuestOrder::firstOrFail();
        $this->assertSame('UNPAID', $order->payment_status);
        $this->assertSame('PENDING', $order->order_status);
        $this->assertEquals(40000, $order->subtotal);
        $this->assertEquals(44400, $order->total);
    }

    public function test_guest_order_rejects_products_from_another_tenant(): void
    {
        $owner = $this->owner();
        $other = User::factory()->create(['role' => 'admin']);
        $table = $this->table($owner);
        $foreign = $this->product($other);

        $this->postJson(route('guest.order.store', ['tableCode' => $table->table_code]), [
            'items' => [['product_id' => $foreign->id, 'quantity' => 1]],
            'payment_method' => 'CASHIER',
        ])->assertStatus(422);
        $this->assertDatabaseCount('guest_orders', 0);
    }

    public function test_qris_is_not_auto_confirmed_by_guest_submission(): void
    {
        $owner = $this->owner();
        $owner->update(['gopay_qr_image' => 'qris-qr/test.png']);
        $table = $this->table($owner);
        $product = $this->product($owner);

        $this->postJson(route('guest.order.store', ['tableCode' => $table->table_code]), [
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
            'payment_method' => 'QRIS',
        ])->assertCreated();

        $this->assertSame('UNPAID', GuestOrder::first()->payment_status);
    }

    public function test_pos_processes_table_order_once_and_updates_stock_and_income(): void
    {
        $owner = $this->owner();
        $table = $this->table($owner);
        $product = $this->product($owner);

        $this->postJson(route('guest.order.store', ['tableCode' => $table->table_code]), [
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
            'payment_method' => 'CASHIER',
        ])->assertCreated();

        $order = GuestOrder::firstOrFail();

        Livewire::actingAs($owner)->test(Terminal::class)
            ->call('loadGuestOrderById', $order->id)
            ->assertSet('activeTab', 'online')
            ->call('checkout');

        $this->assertSame('COMPLETED', $order->fresh()->order_status);
        $this->assertSame('PAID', $order->fresh()->payment_status);
        $this->assertSame(13, $product->fresh()->current_stock);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $owner->id,
            'type' => 'INCOME',
            'amount' => 44400,
        ]);
    }

    public function test_qris_pos_checkout_requires_staff_confirmation(): void
    {
        $owner = $this->owner();
        $owner->update(['gopay_qr_image' => 'qris-qr/test.png']);
        $table = $this->table($owner);
        $product = $this->product($owner);

        $this->postJson(route('guest.order.store', ['tableCode' => $table->table_code]), [
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
            'payment_method' => 'QRIS',
        ])->assertCreated();

        $order = GuestOrder::firstOrFail();

        $pos = Livewire::actingAs($owner)->test(Terminal::class)
            ->call('loadGuestOrderById', $order->id)
            ->call('checkout');

        $this->assertSame('UNPAID', $order->fresh()->payment_status);
        $this->assertSame(15, $product->fresh()->current_stock);

        $pos->set('qrPaymentVerified', true)->call('checkout');
        $this->assertSame('PAID', $order->fresh()->payment_status);
        $this->assertSame(14, $product->fresh()->current_stock);
    }

    public function test_deleted_meja_cannot_access_guest_menu(): void
    {
        $owner = $this->owner();
        $table = $this->table($owner);
        $code = $table->table_code;
        $table->delete();

        $this->get(route('guest.menu', ['tableCode' => $code]))->assertNotFound();
    }
}
