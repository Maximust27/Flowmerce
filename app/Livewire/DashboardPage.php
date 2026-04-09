<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

#[\Livewire\Attributes\Title('Dashboard')]
class DashboardPage extends Component
{
    public function render()
    {
        $userId = Auth::id();
        $stats = Cache::remember("dashboard_stats_{$userId}", 1800, function () use ($userId) {
            return [
                'revenue' => Transaction::where('user_id', $userId)->where('type', 'INCOME')->sum('amount'),
                'profit' => Transaction::where('user_id', $userId)->where('type', 'INCOME')->sum('amount') - Transaction::where('user_id', $userId)->where('type', 'EXPENSE')->sum('amount'),
                'low_stock' => Product::where('user_id', $userId)->where('current_stock', '<=', 5)->count(),
            ];
        });

        $recentTransactions = Transaction::where('user_id', $userId)->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentTransactions'));
    }
}
