<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class GudangExportController extends Controller
{
    public function export(Request $request)
    {
        $userId = Auth::id();
        $filter = $request->query('filter', 'all');

        $query = InventoryLog::whereHas('product', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if ($filter === 'in') {
            $query->where('type', 'IN');
            $title = 'Laporan Barang Masuk';
        } elseif ($filter === 'out') {
            $query->where('type', 'OUT');
            $title = 'Laporan Barang Keluar';
        } else {
            $title = 'Laporan Arus Barang Keseluruhan';
        }

        $logs = $query->latest()->get();

        $pdf = Pdf::loadView('gudang.pdf', compact('logs', 'title'));
        
        return $pdf->download('laporan-gudang-'.date('Ymd').'.pdf');
    }
}
