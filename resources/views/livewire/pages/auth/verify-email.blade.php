<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="fm-auth-flow">
    <div class="fm-auth-flow__symbol"><span class="material-symbols-outlined" aria-hidden="true">forward_to_inbox</span></div>
    <x-ui.auth-heading eyebrow="VERIFIKASI EMAIL" title="Periksa kotak masukmu." description="Kami telah mengirim tautan verifikasi ke alamat email akun. Buka tautannya untuk memverifikasi email." />
    <div class="fm-auth-flow__info">
        <span class="material-symbols-outlined" aria-hidden="true">mail</span>
        <span>{{ auth()->user()->email }}</span>
    </div>
    @if(session('status') === 'verification-link-sent')
        <x-ui.feedback class="fm-auth-flow__feedback">Tautan verifikasi baru berhasil dikirim. Periksa emailmu.</x-ui.feedback>
    @endif
    <div class="fm-auth-form">
        <x-ui.button type="button" wire:click="sendVerification" class="fm-auth-form__submit" wire:loading.attr="disabled" wire:target="sendVerification">
            <span wire:loading.remove wire:target="sendVerification">Kirim ulang verifikasi</span>
            <span wire:loading wire:target="sendVerification">Mengirim tautan...</span>
            <span class="material-symbols-outlined" aria-hidden="true" wire:loading.remove wire:target="sendVerification">arrow_forward</span>
        </x-ui.button>
        <x-ui.button type="button" variant="secondary" wire:click="logout" wire:loading.attr="disabled" wire:target="logout" class="fm-auth-form__secondary">
            Keluar dari akun
        </x-ui.button>
    </div>
    <p class="fm-auth-flow__note"><span class="material-symbols-outlined" aria-hidden="true">info</span> Tidak menerima email? Periksa folder spam sebelum mengirim ulang.</p>
</div>
