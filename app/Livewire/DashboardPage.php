<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Carbon\Carbon;

#[\Livewire\Attributes\Title('Dashboard')]
class DashboardPage extends Component
{
    public function render()
    {
        $user = Auth::user();

        // 1. Ambil 5 Transaksi Terakhir
        $recentTransactions = $user->transactions()
            ->latest()
            ->take(5)
            ->get();

        // 2. Generate Data Grafik (7 Hari Terakhir untuk Pemasukan)
        $labels = [];
        $bars = [];
        $rawTotals = [];
        $maxTotal = 0;

        $hariIndo = ['Mon' => 'SEN', 'Tue' => 'SEL', 'Wed' => 'RAB', 'Thu' => 'KAM', 'Fri' => 'JUM', 'Sat' => 'SAB', 'Sun' => 'MIN'];

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