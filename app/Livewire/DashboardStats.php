<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

#[Lazy]
class DashboardStats extends Component
{
    public $revenue = 0;
    public $profit = 0;
    public $lowStockCount = 0;

    public function mount()
    {
        $userId = Auth::id();
        
        $stats = Cache::remember("dashboard_stats_{$userId}", 60 * 30, function () use ($userId) {
            $revenue = Transaction::where('user_id', $userId)
                ->where('type', 'INCOME')
                ->sum('amount');
                
            $expenses = Transaction::where('user_id', $userId)
                ->where('type', 'EXPENSE')
                ->sum('amount');
                
            $profit = $revenue - $expenses;
            
            $lowStockCount = Product::where('user_id', $userId)
                ->whereColumn('current_stock', '<=', 'min_stock_alert')
                ->count();

            return compact('revenue', 'profit', 'lowStockCount');
        });

        $this->revenue = $stats['revenue'];
        $this->profit = $stats['profit'];
        $this->lowStockCount = $stats['lowStockCount'];
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-pulse">
            <div class="bg-gray-800 rounded-xl p-6 h-32"></div>
            <div class="bg-gray-800 rounded-xl p-6 h-32"></div>
            <div class="bg-gray-800 rounded-xl p-6 h-32"></div>
        </div>
        HTML;
    }
}
