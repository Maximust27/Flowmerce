<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Table;

class GuestMenuController extends Controller
{
    public function show(string $tableCode)
    {
        $table = Table::query()->where('table_code', $tableCode)->with('user')->firstOrFail();

        if (!$table->is_active) {
            return response()->view('menu.inactive', compact('table'), 200);
        }

        $owner = $table->user;
        abort_unless($owner, 404);

        $products = Product::query()
            ->where('user_id', $owner->id)
            ->where('is_available_online', true)
            ->where('current_stock', '>', 0)
            ->orderBy('category')
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'sell_price', 'image', 'current_stock']);

        $categories = $products->pluck('category')
            ->filter()->unique()->values()->prepend('Semua')->all();

        return view('menu.index', [
            'table' => $table,
            'owner' => $owner,
            'products' => $products,
            'categories' => $categories,
            'tableCode' => $tableCode,
        ]);
    }
}
