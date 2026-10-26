<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
  <x-ui.section-header title="Ubah Kata Sandi" description="Lindungi akun dengan kata sandi yang kuat dan unik." />
  <form wire:submit="updatePassword" class="fm-form" style="margin-top:22px">
    <x-ui.field field="current_password" label="Kata Sandi Saat Ini" type="password" wire:model="current_password" autocomplete="current-password" required />
    <x-ui.field field="password" label="Kata Sandi Baru" type="password" wire:model="password" autocomplete="new-password" required />
    <x-ui.field field="password_confirmation" label="Konfirmasi Kata Sandi" type="password" wire:model="password_confirmation" autocomplete="new-password" required />
    <div class="fm-form__actions" style="justify-content:flex-start">
      <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="updatePassword"><span class="material-symbols-outlined" aria-hidden="true">lock_reset</span>Perbarui Password</x-ui.button>
      <x-action-message on="password-updated"><span class="fm-muted" style="font-size:12px">Password diperbarui</span></x-action-message>
    </div>
  </form>
</section>
