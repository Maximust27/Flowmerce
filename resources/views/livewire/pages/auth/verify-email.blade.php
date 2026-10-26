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

<div><p class="fm-section-eyebrow">Verifikasi Akun</p><h1>Verifikasi Email Anda</h1><p style="margin-top:11px">Buka tautan verifikasi yang telah kami kirim ke email Anda. Tidak menerimanya? Kirim ulang di bawah.</p>
  @if(session('status') === 'verification-link-sent')<x-ui.feedback style="margin-top:17px">Tautan verifikasi baru telah dikirim.</x-ui.feedback>@endif
  <div class="fm-form" style="margin-top:25px"><x-ui.button wire:click="sendVerification" class="fm-auth__submit" wire:loading.attr="disabled" wire:target="sendVerification">Kirim Ulang Verifikasi</x-ui.button><x-ui.button variant="secondary" wire:click="logout" class="fm-auth__submit">Keluar</x-ui.button></div>
</div>
