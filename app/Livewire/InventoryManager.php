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

    // Presentation-only filters: do not mutate inventory or stock rules.
    public string $search = '';
    public string $stockFilter = 'all';
    public string $categoryFilter = 'all';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStockFilter(): void { $this->resetPage(); }
    public function updatedCategoryFilter(): void { $this->resetPage(); }

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

        $wasEditing = (bool) $this->product_id;

        if ($this->product_id) {
            $product = Product::where('user_id', Auth::id())->findOrFail($this->product_id);
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
        session()->flash('message', $wasEditing ? 'Produk berhasil diubah.' : 'Produk berhasil ditambahkan.');
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
        $userId = Auth::id();
        $search = trim($this->search);

        $query = Product::query()->where('user_id', $userId)
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            }))
            ->when($this->categoryFilter !== 'all',
                fn ($query) => $query->where('category', $this->categoryFilter))
            ->when($this->stockFilter === 'out',
                fn ($query) => $query->where('current_stock', '<=', 0))
            ->when($this->stockFilter === 'low',
                fn ($query) => $query->where('current_stock', '>', 0)
                    ->whereColumn('current_stock', '<=', 'min_stock_alert'))
            ->when($this->stockFilter === 'normal',
                fn ($query) => $query->whereColumn('current_stock', '>', 'min_stock_alert'));

        $products = $query->latest()->paginate(10);
        $categories = Product::where('user_id', $userId)
            ->whereNotNull('category')->where('category', '!=', '')
            ->distinct()->orderBy('category')->pluck('category');

        return view('livewire.inventory-manager', compact('products', 'categories'));
    }
}
