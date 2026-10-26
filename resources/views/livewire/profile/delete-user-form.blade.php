<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section>
    <div class="fm-account__section-heading fm-account__section-heading--danger">
        <div><span class="fm-overview__micro-label">DANGER ZONE</span><h2>Hapus akun</h2><p>Tindakan ini permanen. Pastikan data yang diperlukan sudah disimpan.</p></div>
        <span class="fm-account__section-icon material-symbols-outlined" aria-hidden="true">delete_forever</span>
    </div>
    <x-ui.button variant="danger" x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        <span class="material-symbols-outlined" aria-hidden="true">delete_outline</span>Hapus akun permanen
    </x-ui.button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" maxWidth="md" focusable>
        <form wire:submit="deleteUser" class="fm-account__delete-dialog">
            <div class="fm-account__delete-icon"><span class="material-symbols-outlined" aria-hidden="true">warning_amber</span></div>
            <h2 class="fm-modal__title">Hapus akun Flowmerce?</h2>
            <p class="fm-account__delete-description">Akun dan data terkait akan dihapus secara permanen. Masukkan kata sandi untuk mengonfirmasi.</p>
            <x-ui.field field="password" label="Kata sandi saat ini" type="password" icon="lock_outline" reveal
                wire:model="password" autocomplete="current-password" placeholder="Kata sandi akun" required />
            <div class="fm-account__delete-actions">
                <x-ui.button variant="secondary" x-on:click="$dispatch('close')">Batal</x-ui.button>
                <x-ui.button type="submit" variant="danger" wire:loading.attr="disabled" wire:target="deleteUser">Ya, hapus akun</x-ui.button>
            </div>
        </form>
    </x-modal>
</section>
