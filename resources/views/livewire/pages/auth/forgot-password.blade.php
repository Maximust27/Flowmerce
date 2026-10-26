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

<div><p class="fm-section-eyebrow">Keamanan Akun</p><h1>Lupa Kata Sandi?</h1><p style="margin-top:11px">Masukkan email akun. Kami akan mengirimkan tautan untuk membuat kata sandi baru.</p>
  @if(session('status'))<x-ui.feedback style="margin-top:18px">{{ session('status') }}</x-ui.feedback>@endif
  <form wire:submit="sendPasswordResetLink" class="fm-form" style="margin-top:25px"><x-ui.field field="email" label="Alamat Email" type="email" wire:model="email" autocomplete="username" placeholder="email@bisnis.com" autofocus required /><x-ui.button type="submit" class="fm-auth__submit" wire:loading.attr="disabled" wire:target="sendPasswordResetLink">Kirim Tautan Pemulihan <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></x-ui.button></form>
  <p style="margin-top:21px;text-align:center"><a class="fm-auth__link" href="{{ route('login') }}" wire:navigate>Kembali ke login</a></p>
</div>
