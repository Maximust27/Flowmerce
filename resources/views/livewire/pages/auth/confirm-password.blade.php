<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="fm-auth-flow">
    <div class="fm-auth-flow__symbol"><span class="material-symbols-outlined" aria-hidden="true">verified_user</span></div>
    <x-ui.auth-heading eyebrow="VERIFIKASI KEAMANAN" title="Konfirmasi identitasmu." description="Untuk melanjutkan aktivitas sensitif, masukkan kembali kata sandi akun Flowmerce." />
    <form wire:submit="confirmPassword" class="fm-auth-form">
        <x-ui.field field="password" label="Kata sandi saat ini" type="password" icon="lock_outline" reveal
            wire:model="password" autocomplete="current-password" placeholder="Kata sandi akun" required autofocus />
        <x-ui.button type="submit" class="fm-auth-form__submit" wire:loading.attr="disabled" wire:target="confirmPassword">
            <span wire:loading.remove wire:target="confirmPassword">Konfirmasi dan lanjutkan</span>
            <span wire:loading wire:target="confirmPassword">Memverifikasi...</span>
            <span class="material-symbols-outlined" aria-hidden="true" wire:loading.remove wire:target="confirmPassword">arrow_forward</span>
        </x-ui.button>
    </form>
    <p class="fm-auth-flow__note"><span class="material-symbols-outlined" aria-hidden="true">lock</span> Konfirmasi ini diperlukan untuk menjaga keamanan akun.</p>
</div>
