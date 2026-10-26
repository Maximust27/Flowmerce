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

<div><p class="fm-section-eyebrow">Konfirmasi Keamanan</p><h1>Konfirmasi Kata Sandi</h1><p style="margin-top:11px">Untuk melanjutkan ke area sensitif, verifikasi kembali kata sandi akun Anda.</p>
  <form wire:submit="confirmPassword" class="fm-form" style="margin-top:25px"><x-ui.field field="password" label="Kata Sandi" type="password" wire:model="password" autocomplete="current-password" required /><x-ui.button type="submit" class="fm-auth__submit" wire:loading.attr="disabled" wire:target="confirmPassword">Konfirmasi Identitas</x-ui.button></form>
</div>
