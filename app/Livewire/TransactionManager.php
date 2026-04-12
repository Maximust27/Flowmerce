<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TransactionManager extends Component
{
    use WithPagination;

    public $type = 'INCOME', $amount, $category, $notes;
    public $isModalOpen = false;

    protected $rules = [
        'type' => 'required|in:INCOME,EXPENSE',
        'amount' => 'required|numeric|min:1',
        'category' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ];

    public function openModal($type = 'INCOME')
    {
        $this->resetValidation();
        $this->type = $type;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['type', 'amount', 'category', 'notes']);
        $this->type = 'INCOME';
    }

    public function save()
    {
        $this->validate();

        Transaction::create([
            'user_id' => Auth::id(),
            'type' => $this->type,
            'amount' => $this->amount,
            'category' => $this->category,
            'notes' => $this->notes,
        ]);

        $this->closeModal();
        Cache::forget("dashboard_stats_" . Auth::id());
        session()->flash('message_tx', 'Transaksi berhasil dicatat.');
        $this->dispatch('transaction-saved'); // Untuk refresh dasbor secara asinkron (opsional)
    }

    public function render()
    {
        $transactions = Transaction::where('user_id', Auth::id())->latest()->paginate(10);
        return view('livewire.transaction-manager', compact('transactions'));
    }
}
