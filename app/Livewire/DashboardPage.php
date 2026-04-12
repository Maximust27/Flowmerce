<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Carbon\Carbon;

#[\Livewire\Attributes\Title('Dashboard')]
class DashboardPage extends Component
{
    public string $chartFilter = 'weekly';

    public function setChartFilter($filter)
    {
        $this->chartFilter = $filter;
    }

    public function render()
    {
        $user = Auth::user();

        // 1. Ambil 5 Transaksi Terakhir
        $recentTransactions = $user->transactions()
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
                
                $monthlyIncome = $user->transactions()
                    ->where('type', 'INCOME')
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
                
                $dailyIncome = $user->transactions()
                    ->where('type', 'INCOME')
                    ->whereDate('created_at', $date)
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