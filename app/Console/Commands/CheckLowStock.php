<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for products with low stock and alert';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock_alert')->get();

        if ($lowStockProducts->isEmpty()) {
            $this->info('All product stocks are sufficient.');
            return;
        }

        // Output to console
        foreach ($lowStockProducts as $product) {
            $this->warn("Low stock alert: {$product->name} (Current: {$product->current_stock}, Min: {$product->min_stock_alert}) belonging to User ID #{$product->user_id}");
            // Optional: send notification or email here
        }

        $this->info('Low stock check completed.');
    }
}
