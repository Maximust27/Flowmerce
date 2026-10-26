<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $business_name = '';
    public string $business_category = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'business_name' => ['required', 'string', 'max:255'],
            'business_category' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'admin';

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
  <p class="fm-section-eyebrow">Mulai bisnis</p><h1>Buat Akun Baru</h1><p style="margin-top:10px">Daftarkan usaha dan mulai mengelola operasional dalam satu workspace.</p>
  <form wire:submit="register" class="fm-form" style="margin-top:24px">
    <div class="fm-form__grid"><x-ui.field field="name" label="Nama Lengkap" wire:model="name" placeholder="Nama pemilik" autocomplete="name" required autofocus /><x-ui.field field="email" label="Email" type="email" wire:model="email" placeholder="email@bisnis.com" autocomplete="username" required /></div>
    <x-ui.field field="business_name" label="Nama Usaha" wire:model="business_name" placeholder="Contoh: Warung Barokah" required />
    <x-ui.field field="business_category" label="Kategori Usaha" wire:model="business_category" placeholder="Contoh: Coffee Shop" required />
    <div class="fm-form__grid"><x-ui.field field="password" label="Kata Sandi" type="password" wire:model="password" autocomplete="new-password" required /><x-ui.field field="password_confirmation" label="Konfirmasi Kata Sandi" type="password" wire:model="password_confirmation" autocomplete="new-password" required /></div>
    <x-ui.button type="submit" class="fm-auth__submit" wire:loading.attr="disabled" wire:target="register">Daftarkan Usaha <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button>
  </form>
  <p style="margin-top:22px;text-align:center">Sudah punya akun? <a href="{{ route('login') }}" wire:navigate class="fm-auth__link">Masuk</a></p>
</div>
