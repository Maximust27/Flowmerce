<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@flowmerce.test'],
            [
                'name' => 'Admin Boss',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create Cashier
        $cashier = User::firstOrCreate(
            ['email' => 'cashier@flowmerce.test'],
            [
                'name' => 'Kasir Andalan',
                'password' => Hash::make('password'),
                'role' => 'cashier',
            ]
        );

        // Seed Products for Admin to test POS
        $products = [
            ['name' => 'Ceramic Artisan Mug', 'category' => 'Home & Decor', 'buy_price' => 80000, 'sell_price' => 125000, 'current_stock' => 12, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDAEfXHeH_X-0AHIPSrkMdC3kvF03aWYE6LNOfxW6OunczusFbtKXoW-LqEK30jvLMgRNv1vlRJ2aGAQU3pXSDfHQOoDHMb6234gjpVHpMpqwmVACW5FRzFQ_aE-17_SuAFXHANtwUYFHn0IF2-1W4NpA0BcUaSrvEcKngrVfJRHYg7dkKv0K6w3-xBjXnnNsQs9ji5aW4y9gIy5Xps-VtRpF0Bt4TjnEZwLNWHbrzG1AUj77IIMiNlHPCIKyisz6CNragL6k_gIWFS'],
            ['name' => 'Midnight Cold Brew', 'category' => 'F&B • Beverage', 'buy_price' => 20000, 'sell_price' => 45000, 'current_stock' => 45, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCNXCUH_Va2FhmxjlA2c45pbE4kIYU6NeaApUNSxPVNCpS8S1aM_t2jv5GG1vTDmCoyLDXz3y2RQQM6gfu72C4MWQPuHcTSp_QTUQuvodcvWlzBmkwpHsYvAD8bJ7HaikbJbs82ZeBDAt4xF8_duqECxVb4ma58S9fqGlqFIFiY3uBRnu_e8Atb6uQcYdxb3YlMdmLtPQfRLfoVfG4Q6fBOk9CQOvNlsiQxMpaQTf6uk2qsF8h2BayFUL2fBFeTs-bF1DyR6VOnMXWL'],
            ['name' => 'Sonic-X Headphones', 'category' => 'Electronics', 'buy_price' => 1500000, 'sell_price' => 2499000, 'current_stock' => 2, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAakB-TAIBFRj2-0bGynKb7CIh3WmCIJWmIdih3cZhtJMhgohNcSzvJUAMsUSSMhYr_ikcXooeOPNr13AiLnSWxvCL1Y-TvQv4qgF2DTFxg_blAGv9oDE5UgkVgEx3bntmkQnGc1Rj1IM4gjCuZBrkXRb7dKVC5mGPnALL_dxwoXwALO_HRA-QdS7uIWplfZWSmfOoqs7MpXexCTUwOQRw94mLXL1Y5S7cTgEV-yZN5fQyi2TVcPm5KSNQi6Zo2ht60MVQ8wbUWkl-F'],
            ['name' => 'Nebula Table Lamp', 'category' => 'Home & Living', 'buy_price' => 500000, 'sell_price' => 850000, 'current_stock' => 8, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAJajHhcuCd1JTEEwLpS-tg2a6SvLbmvMHftDGCmWTOgNWqVSnmSx40Dc9FGXN8EbCTkXvvePm7W_bxaKw9JQd6MKGUjiPRBkVEyxwwHPPXiHnPip_9lK1YuIP66LD1-XtDqfFj3uVG_PSZi_6Tizf9qgcSuR4baMSQHuJmQ3AixiJCj6gdY-TpnX8166gFbB-DbcBnwF69_2x2y8GR0a3w_JV8Saz-8QC4DghF5hr1kbJBdc_XGo2lSzAA9-gKdl7av0BWMBtczGO-'],
            ['name' => 'Titan Smartwatch v2', 'category' => 'Electronics', 'buy_price' => 2000000, 'sell_price' => 3120000, 'current_stock' => 21, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDrxWgRwd1e67IejqulzFEMonkpneTh2C-BASm-Eiqc6T6GgrAbYmk_LKeXF2e219ljIMzFcYyhyDFDQOK0lssnvCrF6R4mZGz8vrf7kmvla4jMnmtI6vmfAKJBAL9AjMQFzJzQu-2XBM6RQNgHZXoA1TWqE_cX6-1Bn5RGQwlwbAN6of4urrAryIHfaTJbirNvA1sXzgX7Q9kABJuF40J9TSa11GbdFgHmdvxjqH4CaB-_l35mt42b33F2C-hSrz7Db9rxFRF3AAMY'],
            ['name' => 'Carbon Leather Wallet', 'category' => 'Accessories', 'buy_price' => 200000, 'sell_price' => 420000, 'current_stock' => 15, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDP6Psqw_ptzKt1ba6bWV20oJvcyOhj_pA0ibwVQt9HrhFZekpt2KQ933ki3fBtpFSXw-nbe0Zru-haE0G0hq377nFzMccLQI_vEpjiSCeaKdyJWn4awbROxT4KhF0ubuNpAu_PNLpOJJcDMlZdLugcHdzcUlpXhbi7SMElGczPXzpoH0ciS9WHNbb1oO7_4-B42zDtaVfPLKsHFI9_8z-m6JabnIOH8W9PLBsf0hQmOA23qk_YzPMYBioMDXvG8f7nkn73HG_ePSq7'],
            ['name' => 'Hydro-Elite 1L', 'category' => 'Lifestyle', 'buy_price' => 100000, 'sell_price' => 195000, 'current_stock' => 50, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBo0al80SBhf88hggJXPf8CfpTRFSbAHCZCNSyD9kJ1meblzsNujaCW3Ih1q2NvUTW7mXzFdApFtpKZAfV9bP_QLfxEVscX4gCUh0xgIS9Tff-hDuvSTYwZIT1phOd0xmf8mfQMsYPTORvLdsF1Arcprx5FqP-mCkbGruz9pSD6kq3PplTm-GMkYhJe9144m_FJ_IiLyoHmh6KImmknRLH5WPveBuxoEzwvBfKtYXOuKCvtrjAGiIwXQPMbL8bR3ilh-un09QANdoEg'],
            ['name' => 'Pulsar Running Shoes', 'category' => 'Apparel', 'buy_price' => 1000000, 'sell_price' => 1850000, 'current_stock' => 5, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGkCJLwkJoR_sOp5vgoucFfGqqVFaW4X_snMOZqyKS75elyrrfCCeuP54qglxoF4nEYbToiYIUuSbVPN7HOgOR-x285qsoK4_7ISVseSCUGHiYrRGwLiwGTUphsh8yDBwFq1CYn1ISy7zbzo-W3vbn9VEerJ1W1b4z-b9-FzqigFrSp7svBprAkjpA_iXwl1uUNBO48Yd8eYpEDy4fXHcCGaqInXIPtnQ0g8UqNpQF_I1bBmtV3_Ep37nCV90C0MWfAKVuhVdBIMui'],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['name' => $productData['name']],
                [
                    'user_id' => $admin->id,
                    'category' => $productData['category'],
                    'buy_price' => $productData['buy_price'],
                    'sell_price' => $productData['sell_price'],
                    'current_stock' => $productData['current_stock'],
                    'min_stock_alert' => 5,
                    'image' => $productData['image'],
                ]
            );

            // Create inventory log for each product
            $product = Product::where('name', $productData['name'])->first();
            if ($product && $product->inventoryLogs()->count() === 0) {
                $product->inventoryLogs()->create([
                    'type' => 'IN',
                    'quantity' => $product->current_stock,
                    'reason' => 'Stok awal produk baru',
                ]);
            }
        }
    }
}
