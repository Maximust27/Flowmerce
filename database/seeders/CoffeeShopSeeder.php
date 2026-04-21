<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CoffeeShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@flowmerce.test'],
            [
                'name' => 'Budi Owner',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'business_name' => 'Kopi Senja Utama',
                'business_category' => 'Coffee Shop',
            ]
        );

        $cashier = User::firstOrCreate(
            ['email' => 'kasir@flowmerce.test'],
            [
                'name' => 'Siti Kasir',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'business_name' => 'Kopi Senja Utama',
                'business_category' => 'Coffee Shop',
            ]
        );

        // 2. Define Products
        $productsData = [
            // Coffee
            ['name' => 'Espresso Double', 'category' => 'Coffee', 'buy_price' => 8000, 'sell_price' => 18000, 'current_stock' => 100, 'image' => 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Cappuccino Hot', 'category' => 'Coffee', 'buy_price' => 12000, 'sell_price' => 28000, 'current_stock' => 50, 'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Ice Caramel Macchiato', 'category' => 'Coffee', 'buy_price' => 15000, 'sell_price' => 35000, 'current_stock' => 40, 'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Vietnam Drip', 'category' => 'Coffee', 'buy_price' => 10000, 'sell_price' => 22000, 'current_stock' => 60, 'image' => 'https://images.unsplash.com/photo-1544787210-22c60205765c?q=80&w=400&auto=format&fit=crop'],
            
            // Non-Coffee
            ['name' => 'Matcha Latte Ice', 'category' => 'Non-Coffee', 'buy_price' => 18000, 'sell_price' => 32000, 'current_stock' => 30, 'image' => 'https://images.unsplash.com/photo-1515822665710-d172a1d3bc2c?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Signature Chocolate', 'category' => 'Non-Coffee', 'buy_price' => 15000, 'sell_price' => 30000, 'current_stock' => 45, 'image' => 'https://images.unsplash.com/photo-1544787210-22c60205765c?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Lychee Tea', 'category' => 'Non-Coffee', 'buy_price' => 8000, 'sell_price' => 20000, 'current_stock' => 100, 'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=400&auto=format&fit=crop'],

            // Food & Pastry
            ['name' => 'Butter Croissant', 'category' => 'Pastry', 'buy_price' => 12000, 'sell_price' => 25000, 'current_stock' => 20, 'image' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Pain au Chocolat', 'category' => 'Pastry', 'buy_price' => 14000, 'sell_price' => 28000, 'current_stock' => 15, 'image' => 'https://images.unsplash.com/photo-1530610476181-d83430964dca?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Fudge Brownies', 'category' => 'Snack', 'buy_price' => 10000, 'sell_price' => 22000, 'current_stock' => 25, 'image' => 'https://images.unsplash.com/photo-1606312619070-d48b4c652a52?q=80&w=400&auto=format&fit=crop'],
            ['name' => 'Truffle Fries', 'category' => 'Snack', 'buy_price' => 15000, 'sell_price' => 30000, 'current_stock' => 30, 'image' => 'https://images.unsplash.com/photo-1630384060421-cb20d0e0649d?q=80&w=400&auto=format&fit=crop'],
        ];

        $products = [];
        foreach ($productsData as $data) {
            $products[] = Product::create(array_merge($data, ['user_id' => $admin->id]));
        }

        // 3. Create Historical Orders (Last 30 Days)
        $this->command->info('Seeding historical orders...');
        
        for ($i = 0; $i < 60; $i++) {
            $date = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 24))->subMinutes(rand(0, 60));
            
            $orderItems = [];
            $subtotal = 0;
            $numItems = rand(1, 4);
            
            // Pick random products
            $selectedProducts = array_rand($products, $numItems);
            if (!is_array($selectedProducts)) $selectedProducts = [$selectedProducts];

            foreach ($selectedProducts as $idx) {
                $p = $products[$idx];
                $qty = rand(1, 2);
                $itemSubtotal = $p->sell_price * $qty;
                
                $orderItems[] = [
                    'product_id' => $p->id,
                    'product_name' => $p->name,
                    'quantity' => $qty,
                    'unit_price' => $p->sell_price,
                    'subtotal' => $itemSubtotal,
                ];
                $subtotal += $itemSubtotal;
            }

            $taxRate = 0.11; // 11% tax
            $taxAmount = $subtotal * $taxRate;
            $total = $subtotal + $taxAmount;

            $order = PosOrder::create([
                'user_id' => $admin->id,
                'order_number' => 'FLW-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'subtotal' => $subtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'payment_method' => collect(['CASH', 'QRIS', 'TRANSFER'])->random(),
                'status' => 'COMPLETED',
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Also create a Transaction entry for each order (Income)
            Transaction::create([
                'user_id' => $admin->id,
                'type' => 'INCOME',
                'amount' => $total,
                'category' => 'Sales',
                'notes' => 'POS Order ' . $order->order_number,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // 4. Create some Expenses
        $expenses = [
            ['category' => 'Bahan Baku', 'notes' => 'Restock Bijih Kopi Arabica', 'amount' => 1500000],
            ['category' => 'Bahan Baku', 'notes' => 'Restock Susu UHT 2 Karton', 'amount' => 450000],
            ['category' => 'Operasional', 'notes' => 'Bayar Listrik Bulanan', 'amount' => 1200000],
            ['category' => 'Operasional', 'notes' => 'Internet & Telepon', 'amount' => 350000],
            ['category' => 'Lainnya', 'notes' => 'Beli Tissue & Sedotan', 'amount' => 100000],
        ];

        foreach ($expenses as $exp) {
            $date = Carbon::now()->subDays(rand(1, 20));
            Transaction::create(array_merge($exp, [
                'user_id' => $admin->id,
                'type' => 'EXPENSE',
                'created_at' => $date,
                'updated_at' => $date,
            ]));
        }

        $this->command->info('Coffee Shop Seeding completed!');
    }
}
