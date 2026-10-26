<?php

namespace Tests\Feature;

use App\Livewire\AiChat;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BusinessAdvisor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class AiAdvisorTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_context_never_leaks_other_tenants_finances_or_stock(): void
    {
        $owner = User::factory()->create(['role' => 'admin', 'business_name' => 'Kedai Pemilik']);
        $other = User::factory()->create(['role' => 'admin', 'business_name' => 'Bisnis Rahasia']);

        Transaction::create(['user_id' => $owner->id, 'type' => 'INCOME', 'amount' => 90000, 'category' => 'Jual']);
        Transaction::create(['user_id' => $owner->id, 'type' => 'EXPENSE', 'amount' => 20000, 'category' => 'Biaya']);
        Transaction::create(['user_id' => $other->id, 'type' => 'INCOME', 'amount' => 999999, 'category' => 'Rahasia']);

        Product::create([
            'user_id' => $owner->id, 'name' => 'Kopi Pemilik',
            'category' => 'Minuman', 'buy_price' => 0, 'sell_price' => 25000,
            'current_stock' => 1, 'min_stock_alert' => 5,
        ]);
        Product::create([
            'user_id' => $other->id, 'name' => 'Rahasia Stok',
            'category' => 'Minuman', 'buy_price' => 0, 'sell_price' => 40000,
            'current_stock' => 1, 'min_stock_alert' => 5,
        ]);

        $context = app(BusinessAdvisor::class)->contextFor($owner);

        $this->assertSame(90000.0, $context['pemasukan_tercatat']);
        $this->assertSame(20000.0, $context['pengeluaran_tercatat']);
        $this->assertSame(70000.0, $context['selisih_pencatatan']);
        $this->assertSame('Kopi Pemilik', $context['stok_perlu_perhatian'][0]['produk']);
        $this->assertCount(1, $context['stok_perlu_perhatian']);
    }

    public function test_advisor_returns_plain_readable_prose_without_markdown_noise(): void
    {
        $advisor = new BusinessAdvisor();
        $text = $advisor->plainText("### Analisis!\n\n* **Pemasukan:** Rp 90.000!\n* **Pengeluaran:** Rp 20.000!\n\n**Catatan:** angkanya belum mencakup semua biaya.");
        $this->assertStringNotContainsString('*', $text);
        $this->assertStringNotContainsString('#', $text);
        $this->assertStringNotContainsString('!', $text);
        $this->assertStringContainsString('Pemasukan: Rp 90.000.', $text);
        $this->assertStringContainsString('belum mencakup semua biaya', $text);
    }

    public function test_livewire_ai_uses_tenant_scoped_context_and_formatter(): void
    {
        config()->set('services.gemini.api_key', 'fake-key');
        $owner = User::factory()->create(['role' => 'admin', 'business_name' => 'Usaha Saya']);
        $other = User::factory()->create(['role' => 'admin', 'business_name' => 'Rahasia Usaha']);
        Transaction::create(['user_id' => $owner->id, 'type' => 'INCOME', 'amount' => 50000, 'category' => 'Penjualan']);
        Transaction::create(['user_id' => $other->id, 'type' => 'INCOME', 'amount' => 9999999, 'category' => 'Rahasia']);

        Http::fake(['*generativelanguage.googleapis.com*' => Http::response([
            'candidates' => [[
                'content' => ['parts' => [['text' => '**Selisih positif** berdasarkan transaksi yang sudah dicatat!']]],
            ]],
        ], 200)]);

        $result = Livewire::actingAs($owner)->test(AiChat::class)
            ->set('message', 'Bagaimana kondisi keuangan?')
            ->call('sendMessage');

        $result->assertSee('Selisih positif berdasarkan transaksi yang sudah dicatat.');
        Http::assertSent(function ($request) {
            $payload = json_encode($request->data(), JSON_UNESCAPED_UNICODE);
            return str_contains($payload, '50000')
                && !str_contains($payload, '9999999')
                && str_contains($payload, 'Selisih positif BUKAN bukti pasti laba bersih');
        });
    }
}
