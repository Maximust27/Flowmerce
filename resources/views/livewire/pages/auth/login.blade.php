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

<div>
  <p class="fm-section-eyebrow">Selamat datang</p><h1>Masuk ke Flowmerce</h1><p style="margin-top:10px">Kelola aktivitas bisnis Anda dari satu tempat.</p>
  @if(session('status'))<x-ui.feedback style="margin-top:17px">{{ session('status') }}</x-ui.feedback>@endif
  <form wire:submit="login" class="fm-form" style="margin-top:25px">
    <x-ui.field field="form.email" label="Alamat Email" type="email" wire:model="form.email" autocomplete="username" placeholder="email@bisnis.com" required autofocus />
    <x-ui.field field="form.password" label="Kata Sandi" type="password" wire:model="form.password" autocomplete="current-password" placeholder="Masukkan kata sandi" required />
    <div class="flex items-center justify-between gap-3">
      <label class="flex items-center gap-2 fm-muted" style="font-size:11px"><input type="checkbox" wire:model="form.remember" name="remember" style="accent-color:var(--fm-accent)">Ingat saya</label>
      @if(Route::has('password.request'))<a class="fm-auth__link" href="{{ route('password.request') }}" wire:navigate>Lupa kata sandi?</a>@endif
    </div>
    <x-ui.button type="submit" class="fm-auth__submit" wire:loading.attr="disabled" wire:target="login">Masuk <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button>
  </form>
  <p style="margin-top:22px;text-align:center">Belum punya akun? <a href="{{ route('register') }}" wire:navigate class="fm-auth__link">Daftar Sekarang</a></p>
</div>
