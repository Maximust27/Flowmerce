<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class InventoryManager extends Component
{
    use WithPagination, WithFileUploads;

    public $product_id;
    public $name, $category, $buy_price, $sell_price, $current_stock, $min_stock_alert = 5;
    public $image;
    public $old_image;
    public $isModalOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'category' => 'nullable|string|max:255',
        'buy_price' => 'required|numeric|min:0',
        'sell_price' => 'required|numeric|min:0',
        'current_stock' => 'required|integer|min:0',
        'min_stock_alert' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048',
    ];

    public function openModal()
    {
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['product_id', 'name', 'category', 'buy_price', 'sell_price', 'current_stock', 'min_stock_alert', 'image', 'old_image']);
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $imagePath = $this->old_image;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        if ($this->product_id) {
            $product = Product::findOrFail($this->product_id);
            $product->update([
                'name' => $this->name,
                'category' => $this->category,
                'buy_price' => $this->buy_price,
                'sell_price' => $this->sell_price,
                'current_stock' => $this->current_stock,
                'min_stock_alert' => $this->min_stock_alert,
                'image' => $imagePath,
            ]);
        } else {
            Product::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'category' => $this->category,
                'buy_price' => $this->buy_price,
                'sell_price' => $this->sell_price,
                'current_stock' => $this->current_stock,
                'min_stock_alert' => $this->min_stock_alert,
                'image' => $imagePath,
            ]);
        }

        $this->closeModal();
        Cache::forget('dashboard_stats_' . Auth::id());
        session()->flash('message', $this->product_id ? 'Produk berhasil diubah.' : 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->category = $product->category;
        $this->buy_price = $product->buy_price;
        $this->sell_price = $product->sell_price;
        $this->current_stock = $product->current_stock;
        $this->min_stock_alert = $product->min_stock_alert;
        $this->old_image = $product->image;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $product->delete();
        Cache::forget('dashboard_stats_' . Auth::id());
        session()->flash('message', 'Produk berhasil dihapus.');
    }

    public function render()
    {
        $products = Product::where('user_id', Auth::id())->latest()->paginate(10);
        return view('livewire.inventory-manager', compact('products'));
    }
}
