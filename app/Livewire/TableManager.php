<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TableManager extends Component
{
    use WithFileUploads;

    public $tables = [];
    public $showModal = false;
    public $showQrModal = false;
    public $editingId = null;
    public $selectedTable = null;

    // Form fields
    public $table_number = '';
    public $is_active = true;

    // QRIS QR upload
    public $qrisFile = null;
    public $showQrisModal = false;

    public function mount()
    {
        $this->loadTables();
    }

    protected function loadTables()
    {
        $this->tables = Table::where('user_id', Auth::id())
            ->orderBy('table_number')
            ->get()
            ->toArray();
    }

    public function openAdd()
    {
        $this->reset(['table_number', 'is_active', 'editingId']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id)
    {
        $table = Table::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $this->editingId     = $table->id;
        $this->table_number  = $table->table_number;
        $this->is_active     = $table->is_active;
        $this->showModal     = true;
    }

    public function save()
    {
        $this->validate([
            'table_number' => 'required|string|max:50',
            'is_active'    => 'boolean',
        ]);

        if ($this->editingId) {
            Table::where('id', $this->editingId)
                ->where('user_id', Auth::id())
                ->update([
                    'table_number' => $this->table_number,
                    'is_active'    => $this->is_active,
                ]);
            $this->dispatch('notify', message: 'Meja berhasil diperbarui!', type: 'success');
        } else {
            if (Table::where('user_id', Auth::id())->count() >= 20) {
                $this->dispatch('notify', message: 'Maksimal 20 meja!', type: 'error');
                return;
            }
            Table::create([
                'user_id'      => Auth::id(),
                'table_number' => $this->table_number,
                'table_code'   => Table::generateTableCode(),
                'is_active'    => $this->is_active,
            ]);
            $this->dispatch('notify', message: 'Meja berhasil ditambahkan!', type: 'success');
        }

        $this->showModal = false;
        $this->loadTables();
    }

    public function toggleActive(int $id)
    {
        $table = Table::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $table->update(['is_active' => !$table->is_active]);
        $this->loadTables();
    }

    public function delete(int $id)
    {
        Table::where('id', $id)->where('user_id', Auth::id())->delete();
        $this->dispatch('notify', message: 'Meja berhasil dihapus!', type: 'success');
        $this->loadTables();
    }

    public function openQr(int $id)
    {
        $this->selectedTable = Table::where('id', $id)->where('user_id', Auth::id())->first()?->toArray();
        $this->showQrModal = true;
    }

    public function openQrisUpload()
    {
        $this->qrisFile  = null;
        $this->showQrisModal = true;
    }

    public function saveQrisQr()
    {
        $this->validate(['qrisFile' => 'required|image|max:2048']);

        $user = Auth::user();

        // Hapus file lama
        if ($user->gopay_qr_image) {
            Storage::disk('public')->delete($user->gopay_qr_image);
        }

        $path = $this->qrisFile->store('qris-qr', 'public');
        $user->update(['gopay_qr_image' => $path]);

        $this->qrisFile   = null;
        $this->showQrisModal = false;
        $this->dispatch('notify', message: 'QR QRIS berhasil disimpan!', type: 'success');
    }

    public function getMenuUrl(string $tableCode): string
    {
        return route('guest.menu', $tableCode);
    }

    public function render()
    {
        return view('livewire.table-manager', [
            'qrisImage' => Auth::user()->gopay_qr_image,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Meja']);
    }
}
