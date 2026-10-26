<?php

namespace App\Livewire;

use App\Models\GuestOrder;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class TableManager extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    public bool $showQrModal = false;
    public bool $showQrisModal = false;
    public ?int $editingId = null;
    public ?array $selectedTable = null;
    public string $table_number = '';
    public bool $is_active = true;
    public $qrisFile;

    public function openAdd(): void
    {
        $this->resetValidation();
        $this->reset('editingId', 'table_number');
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $table = Table::where('user_id', Auth::id())->findOrFail($id);
        $this->editingId = $table->id;
        $this->table_number = $table->table_number;
        $this->is_active = $table->is_active;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showQrModal = false;
        $this->showQrisModal = false;
        $this->reset('editingId', 'table_number', 'selectedTable', 'qrisFile');
        $this->resetValidation();
    }

    public function save(): void
    {
        $ownerId = Auth::id();
        $this->table_number = trim($this->table_number);
        $this->validate([
            'table_number' => [
                'required', 'string', 'max:50',
                Rule::unique('tables', 'table_number')->where('user_id', $ownerId)->ignore($this->editingId),
            ],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($this->editingId) {
            Table::where('user_id', $ownerId)->findOrFail($this->editingId)
                ->update(['table_number' => $this->table_number, 'is_active' => $this->is_active]);
            session()->flash('message', 'Informasi meja diperbarui.');
        } else {
            if (Table::where('user_id', $ownerId)->count() >= 20) {
                $this->addError('table_number', 'Maksimal 20 meja untuk setiap usaha.');
                return;
            }
            Table::create([
                'user_id' => $ownerId,
                'table_number' => $this->table_number,
                'table_code' => Table::generateTableCode(),
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Meja baru berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function toggleActive(int $id): void
    {
        $table = Table::where('user_id', Auth::id())->findOrFail($id);
        $table->update(['is_active' => !$table->is_active]);
    }

    public function delete(int $id): void
    {
        Table::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('message', 'Meja berhasil dihapus.');
    }

    public function openQr(int $id): void
    {
        $this->selectedTable = Table::where('user_id', Auth::id())->findOrFail($id)
            ->only(['id', 'table_number', 'table_code', 'is_active']);
        $this->showQrModal = true;
    }

    public function openQrisUpload(): void
    {
        $this->qrisFile = null;
        $this->resetValidation();
        $this->showQrisModal = true;
    }

    public function saveQrisQr(): void
    {
        $this->validate(['qrisFile' => ['required', 'image', 'max:2048']]);
        $owner = Auth::user();
        $previous = $owner->gopay_qr_image;
        $newPath = $this->qrisFile->store('qris-qr', 'public');
        $owner->update(['gopay_qr_image' => $newPath]);

        if ($previous) Storage::disk('public')->delete($previous);
        $this->closeModal();
        session()->flash('message', 'QR pembayaran disimpan. Pembayaran masih perlu diverifikasi kasir.');
    }

    public function getMenuUrl(string $tableCode): string
    {
        return route('guest.menu', ['tableCode' => $tableCode]);
    }

    public function render()
    {
        $ownerId = Auth::id();
        return view('livewire.table-manager', [
            'tables' => Table::where('user_id', $ownerId)->orderBy('table_number')->get(),
            'qrisImage' => Auth::user()->gopay_qr_image,
            'pendingCount' => GuestOrder::where('user_id', $ownerId)->where('order_status', 'PENDING')->count(),
        ])->layout('components.layouts.app', ['title' => 'Meja & QR']);
    }
}
