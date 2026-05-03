<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InventoryExportController extends Controller
{
    public function export(Request $request)
    {
        $userId = Auth::id();
        $products = Product::where('user_id', $userId)->latest()->get();
        
        $title = 'Laporan Inventaris Produk';
        
        $pdf = Pdf::loadView('inventaris.pdf', compact('products', 'title'));
        
        return $pdf->download('laporan-inventaris-'.date('Ymd').'.pdf');
    }
}
