<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class InventoryManager extends Component
{
    use WithPagination;

    public $product_id;
    public $name, $buy_price, $sell_price, $current_stock, $min_stock_alert = 5;
    public $isModalOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'buy_price' => 'required|numeric|min:0',
        'sell_price' => 'required|numeric|min:0',
        'current_stock' => 'required|integer|min:0',
        'min_stock_alert' => 'required|integer|min:0',
    ];

    public function openModal()
    {
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['product_id', 'name', 'buy_price', 'sell_price', 'current_stock', 'min_stock_alert']);
    }

    public function save()
    {
        $this->validate();

        if ($this->product_id) {
            $product = Product::findOrFail($this->product_id);
            $product->update([
                'name' => $this->name,
                'buy_price' => $this->buy_price,
                'sell_price' => $this->sell_price,
                'current_stock' => $this->current_stock,
                'min_stock_alert' => $this->min_stock_alert,
            ]);
        } else {
            Product::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'buy_price' => $this->buy_price,
                'sell_price' => $this->sell_price,
                'current_stock' => $this->current_stock,
                'min_stock_alert' => $this->min_stock_alert,
            ]);
        }

        $this->closeModal();
        Cache::flush();
        session()->flash('message', $this->product_id ? 'Produk berhasil diubah.' : 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->buy_price = $product->buy_price;
        $this->sell_price = $product->sell_price;
        $this->current_stock = $product->current_stock;
        $this->min_stock_alert = $product->min_stock_alert;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        Cache::flush();
        session()->flash('message', 'Produk berhasil dihapus.');
    }

    public function render()
    {
        $products = Product::latest()->paginate(10);
        return view('livewire.inventory-manager', compact('products'));
    }
}
