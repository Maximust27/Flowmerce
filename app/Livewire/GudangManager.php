<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\InventoryLog;
use App\Models\Product;

#[\Livewire\Attributes\Title('Gudang Digital')]
class GudangManager extends Component
{
    use WithPagination;

    public function render()
    {
        $userId = Auth::id();
        $logs = InventoryLog::whereHas('product', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->latest()->paginate(15);
        
        $totalStock = Product::where('user_id', $userId)->sum('current_stock');
        $inToday = InventoryLog::whereHas('product', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereDate('created_at', today())->where('type', 'IN')->sum('quantity');
        
        $outToday = InventoryLog::whereHas('product', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereDate('created_at', today())->where('type', 'OUT')->sum('quantity');

        return view('gudang.index', compact('logs', 'totalStock', 'inToday', 'outToday'));
    }
}
