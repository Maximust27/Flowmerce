<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // Role-based redirect: cashier → POS, admin → Dashboard
        $route = Auth::user()->role === 'cashier' ? 'pos.index' : 'dashboard';

        $this->redirect(route($route, absolute: false), navigate: true);
    }
}; ?>

<div class="fm-auth-flow">
    <x-ui.auth-heading eyebrow="SELAMAT DATANG KEMBALI" title="Masuk ke workspace." description="Kembali kelola bisnis dengan semua informasi penting dalam satu tempat." />

    @if(session('status'))
        <x-ui.feedback class="fm-auth-flow__feedback">{{ session('status') }}</x-ui.feedback>
    @endif

    <form wire:submit="login" class="fm-auth-form">
        <x-ui.field field="form.email" label="Alamat email" type="email" icon="alternate_email"
            wire:model="form.email" autocomplete="username" placeholder="nama@email.com" required autofocus />

        <x-ui.field field="form.password" label="Kata sandi" type="password" icon="lock_outline" reveal
            wire:model="form.password" autocomplete="current-password" placeholder="Masukkan kata sandi" required />

        <div class="fm-auth-form__options">
            <label class="fm-auth-form__remember">
                <input type="checkbox" wire:model="form.remember" name="remember">
                <span>Ingat saya</span>
            </label>
            @if(Route::has('password.request'))
                <a class="fm-auth-link" href="{{ route('password.request') }}" wire:navigate>Lupa kata sandi?</a>
            @endif
        </div>

        <x-ui.button type="submit" class="fm-auth-form__submit" wire:loading.attr="disabled" wire:target="login">
            <span wire:loading.remove wire:target="login">Masuk ke Flowmerce</span>
            <span wire:loading wire:target="login">Memverifikasi akun...</span>
            <span class="material-symbols-outlined" aria-hidden="true" wire:loading.remove wire:target="login">arrow_forward</span>
        </x-ui.button>
    </form>

    <p class="fm-auth-flow__switch">Baru di Flowmerce? <a href="{{ route('register') }}" wire:navigate>Buat akun bisnis <span aria-hidden="true">↗</span></a></p>
    <p class="fm-auth-flow__note"><span class="material-symbols-outlined" aria-hidden="true">verified_user</span> Akses untuk pemilik usaha dan kasir yang terdaftar.</p>
</div>
