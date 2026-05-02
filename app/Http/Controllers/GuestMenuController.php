<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Product;
use Illuminate\Http\Request;

class GuestMenuController extends Controller
{
    /**
     * Tampilkan halaman menu publik untuk meja tertentu.
     * Tidak memerlukan autentikasi — akses via QR Code.
     */
    public function show(string $tableCode)
    {
        // Cari meja berdasarkan table_code
        $tableModel = Table::where('table_code', $tableCode)
            ->with('user')
            ->first();

        // Jika tidak ditemukan
        if (!$tableModel) {
            abort(404, 'Meja tidak ditemukan.');
        }

        // Jika meja nonaktif
        if (!$tableModel->is_active) {
            return view('menu.inactive', ['table' => $tableModel]);
        }

        $owner = $tableModel->user;

        // Ambil semua produk yang tersedia online
        $products = Product::where('user_id', $owner->id)
            ->where('is_available_online', true)
            ->where('current_stock', '>', 0)
            ->orderBy('category')
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'sell_price', 'image', 'current_stock']);

        // Ambil kategori unik
        $categories = $products->pluck('category')
            ->filter()
            ->unique()
            ->values()
            ->prepend('Semua')
            ->toArray();

        return view('menu.index', [
            'table'      => $tableModel,
            'owner'      => $owner,
            'products'   => $products,
            'categories' => $categories,
            'tableCode'  => $tableCode,
        ]);
    }
}
