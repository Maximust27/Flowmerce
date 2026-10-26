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

<div class="fm-auth-flow fm-auth-flow--register">
    <x-ui.auth-heading eyebrow="MULAI DENGAN FLOWMERCE" title="Bangun workspace-mu." description="Daftarkan informasi dasar, lalu kelola produk, transaksi, dan laporan dari satu aplikasi." />

    <form wire:submit="register" class="fm-auth-form">
        <fieldset class="fm-auth-form__section">
            <legend class="fm-auth-form__section-head"><span>01</span> Data pemilik</legend>
            <div class="fm-auth-form__grid">
                <x-ui.field field="name" label="Nama lengkap" icon="person_outline" wire:model="name" autocomplete="name" placeholder="Nama pemilik usaha" required autofocus />
                <x-ui.field field="email" label="Alamat email" type="email" icon="alternate_email" wire:model="email" autocomplete="username" placeholder="nama@email.com" required />
            </div>
        </fieldset>

        <fieldset class="fm-auth-form__section">
            <legend class="fm-auth-form__section-head"><span>02</span> Tentang usahamu</legend>
            <div class="fm-auth-form__grid">
                <x-ui.field field="business_name" label="Nama usaha" icon="storefront" wire:model="business_name" placeholder="Contoh: Kedai Kopi Kita" required />
                <x-ui.field field="business_category" label="Kategori usaha" icon="category" wire:model="business_category" placeholder="Contoh: Kuliner" required />
            </div>
        </fieldset>

        <fieldset class="fm-auth-form__section">
            <legend class="fm-auth-form__section-head"><span>03</span> Keamanan akun</legend>
            <div class="fm-auth-form__grid">
                <x-ui.field field="password" label="Kata sandi" icon="lock_outline" type="password" wire:model="password" autocomplete="new-password" placeholder="Buat kata sandi" reveal required />
                <x-ui.field field="password_confirmation" label="Konfirmasi kata sandi" icon="lock_reset" type="password" wire:model="password_confirmation" autocomplete="new-password" placeholder="Ulangi kata sandi" reveal required />
            </div>
            <p class="fm-auth-form__helper"><span class="material-symbols-outlined" aria-hidden="true">info</span>Akun pertama akan menjadi administrator usaha. Gunakan kata sandi yang unik dan aman.</p>
        </fieldset>

        <x-ui.button type="submit" class="fm-auth-form__submit" wire:loading.attr="disabled" wire:target="register">
            <span wire:loading.remove wire:target="register">Buat workspace bisnis</span>
            <span wire:loading wire:target="register">Membuat akun...</span>
            <span class="material-symbols-outlined" aria-hidden="true" wire:loading.remove wire:target="register">arrow_forward</span>
        </x-ui.button>
    </form>
    <p class="fm-auth-flow__switch">Sudah memiliki akun? <a href="{{ route('login') }}" wire:navigate>Masuk ke Flowmerce <span aria-hidden="true">↗</span></a></p>
</div>
