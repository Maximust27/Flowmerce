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
    <div class="fm-account__section-heading">
        <div><span class="fm-overview__micro-label">ACCOUNT SECURITY</span><h2>Keamanan akun</h2><p>Perbarui kata sandi secara berkala untuk menjaga akun tetap aman.</p></div>
        <span class="fm-account__section-icon material-symbols-outlined" aria-hidden="true">shield_lock</span>
    </div>
    <form wire:submit="updatePassword" class="fm-form fm-account__form">
        <x-ui.field field="current_password" label="Kata sandi saat ini" type="password" icon="lock_outline" reveal wire:model="current_password" autocomplete="current-password" required />
        <div class="fm-form__grid">
            <x-ui.field field="password" label="Kata sandi baru" type="password" icon="lock_reset" reveal wire:model="password" autocomplete="new-password" required />
            <x-ui.field field="password_confirmation" label="Konfirmasi kata sandi" type="password" icon="lock_outline" reveal wire:model="password_confirmation" autocomplete="new-password" required />
        </div>
        <div class="fm-account__form-actions">
            <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="updatePassword">
                <span class="material-symbols-outlined" aria-hidden="true">lock_reset</span>Perbarui kata sandi
            </x-ui.button>
            <x-action-message on="password-updated"><span class="fm-account__saved">Kata sandi berhasil diperbarui</span></x-action-message>
        </div>
    </form>
</section>
