<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="fm-auth-flow">
    <div class="fm-auth-flow__symbol"><span class="material-symbols-outlined" aria-hidden="true">mark_email_unread</span></div>
    <x-ui.auth-heading eyebrow="PULIHKAN AKUN" title="Lupa kata sandi?" description="Masukkan email yang terhubung ke akun. Kami akan mengirim tautan untuk membuat kata sandi baru." />
    @if(session('status'))
        <x-ui.feedback class="fm-auth-flow__feedback">{{ session('status') }}</x-ui.feedback>
    @endif
    <form wire:submit="sendPasswordResetLink" class="fm-auth-form">
        <x-ui.field field="email" label="Alamat email" type="email" icon="alternate_email" wire:model="email"
            autocomplete="username" placeholder="nama@email.com" required autofocus />
        <x-ui.button type="submit" class="fm-auth-form__submit" wire:loading.attr="disabled" wire:target="sendPasswordResetLink">
            <span wire:loading.remove wire:target="sendPasswordResetLink">Kirim tautan pemulihan</span>
            <span wire:loading wire:target="sendPasswordResetLink">Mengirim tautan...</span>
            <span class="material-symbols-outlined" aria-hidden="true" wire:loading.remove wire:target="sendPasswordResetLink">arrow_forward</span>
        </x-ui.button>
    </form>
    <p class="fm-auth-flow__switch"><a href="{{ route('login') }}" wire:navigate><span aria-hidden="true">←</span> Kembali ke halaman masuk</a></p>
</div>
