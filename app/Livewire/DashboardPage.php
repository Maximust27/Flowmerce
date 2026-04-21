<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;

#[\Livewire\Attributes\Title('Dashboard')]
class DashboardPage extends Component
{
    public string $chartFilter = 'weekly';
    public $revenue = 0;
    public $profit = 0;
    public $lowStockCount = 0;

    public function setChartFilter($filter)
    {
        $this->chartFilter = $filter;
    }

    public function mount()
    {
        $userId = Auth::id();
        $stats = Cache::remember("dashboard_stats_{$userId}", 60 * 30, function () use ($userId) {
            $revenue = Transaction::where('user_id', $userId)->where('type', 'INCOME')->sum('amount');
            $expenses = Transaction::where('user_id', $userId)->where('type', 'EXPENSE')->sum('amount');
            $profit = $revenue - $expenses;
            $lowStockCount = Product::where('user_id', $userId)->whereColumn('current_stock', '<=', 'min_stock_alert')->count();

            return compact('revenue', 'profit', 'lowStockCount');
        });

        $this->revenue = $stats['revenue'];
        $this->profit = $stats['profit'];
        $this->lowStockCount = $stats['lowStockCount'];
    }

    public function render()
    {
        $userId = Auth::id();

        // 1. Ambil 5 Transaksi Terakhir
        $recentTransactions = Transaction::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // 2. Generate Data Grafik
        $labels = [];
        $bars = [];
        $rawTotals = [];
        $maxTotal = 0;

        $hariIndo = ['Mon' => 'SEN', 'Tue' => 'SEL', 'Wed' => 'RAB', 'Thu' => 'KAM', 'Fri' => 'JUM', 'Sat' => 'SAB', 'Sun' => 'MIN'];
        $bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        if ($this->chartFilter === 'monthly') {
            $monthsCount = 6;
            for ($i = $monthsCount - 1; $i >= 0; $i--) {
                $date = Carbon::today()->startOfMonth()->subMonths($i);
                $labels[] = strtoupper($bulanIndo[$date->month - 1]);
                
                $monthlyIncome = Transaction::where('user_id', $userId)->where('type', 'INCOME')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount');
                    
                $rawTotals[] = $monthlyIncome;
                if ($monthlyIncome > $maxTotal) {
                    $maxTotal = $monthlyIncome;
                }
            }
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dayName = $date->format('D');
                $labels[] = $hariIndo[$dayName] ?? strtoupper($dayName);
                
                $dailyIncome = Transaction::where('user_id', $userId)->where('type', 'INCOME')
                    ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                    ->sum('amount');
                    
                $rawTotals[] = $dailyIncome;
                if ($dailyIncome > $maxTotal) {
                    $maxTotal = $dailyIncome;
                }
            }
        }

        foreach ($rawTotals as $total) {
            if ($maxTotal > 0) {
                $bars[] = max(5, round(($total / $maxTotal) * 60)); 
            } else {
                $bars[] = 5;
            }
        }

        // Mengembalikan ke nama view aslimu ('dashboard' atau 'livewire.dashboard')
        return view('dashboard', [
            'recentTransactions' => $recentTransactions,
            'chartLabels' => $labels,
            'chartBars' => $bars,
        ]);
    }
}