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

    public string $filter = 'all';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function exportPdf()
    {
        return redirect()->route('gudang.pdf', ['filter' => $this->filter]);
    }

    public function render()
    {
        $query = InventoryLog::query();

        if ($this->filter === 'in') {
            $query->where('type', 'IN');
        } elseif ($this->filter === 'out') {
            $query->where('type', 'OUT');
        }

        $logs = $query->latest()->paginate(15);
        
        $totalStock = Product::sum('current_stock');
        $inToday = InventoryLog::whereDate('created_at', today())->where('type', 'IN')->sum('quantity');
        
        $outToday = InventoryLog::whereDate('created_at', today())->where('type', 'OUT')->sum('quantity');

        return view('gudang.index', compact('logs', 'totalStock', 'inToday', 'outToday'));
    }
}
