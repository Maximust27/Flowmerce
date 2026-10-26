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
  <x-ui.section-header title="Hapus Akun" description="Tindakan ini permanen dan tidak bisa dibatalkan." />
  <p class="fm-note" style="margin:14px 0 20px">Pastikan Anda telah menyimpan data dan laporan bisnis yang diperlukan sebelum menghapus akun.</p>
  <x-ui.button variant="danger" x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"><span class="material-symbols-outlined" aria-hidden="true">delete_outline</span>Hapus Akun</x-ui.button>

  <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
    <form wire:submit="deleteUser" class="fm-modal__body fm-form" style="padding:26px">
      <h2 class="fm-modal__title">Yakin ingin menghapus akun?</h2>
      <p class="fm-note">Akun dan seluruh data terkait akan dihapus secara permanen. Masukkan kata sandi Anda untuk konfirmasi.</p>
      <x-ui.field field="password" label="Kata Sandi Saat Ini" wire:model="password" type="password" placeholder="Kata sandi akun" />
      <div class="fm-form__actions"><x-ui.button variant="secondary" x-on:click="$dispatch('close')">Batal</x-ui.button><x-ui.button type="submit" variant="danger" wire:loading.attr="disabled" wire:target="deleteUser">Ya, Hapus Akun</x-ui.button></div>
    </form>
  </x-modal>
</section>
