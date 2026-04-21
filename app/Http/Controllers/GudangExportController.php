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
        $filter = $request->query('filter', 'all');
        $userId = Auth::id();
        $productIds = \App\Models\Product::where('user_id', $userId)->pluck('id');

        $query = InventoryLog::whereIn('product_id', $productIds);

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
