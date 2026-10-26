<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
  <x-ui.section-header title="Informasi Profil" description="Perbarui identitas dan email akun Anda." />
  <form wire:submit="updateProfileInformation" class="fm-form" style="margin-top:22px">
    <x-ui.field field="name" label="Nama Lengkap" wire:model="name" autocomplete="name" required />
    <x-ui.field field="email" label="Alamat Email" type="email" wire:model="email" autocomplete="username" required />
    @if(auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
      <div class="fm-feedback" data-tone="danger">Email belum diverifikasi. <button type="button" class="fm-text-link" wire:click="sendVerification">Kirim ulang tautan verifikasi</button></div>
      @if(session('status') === 'verification-link-sent')<x-ui.feedback>Link verifikasi baru sudah dikirim.</x-ui.feedback>@endif
    @endif
    <div class="fm-form__actions" style="justify-content:flex-start">
      <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="updateProfileInformation"><span class="material-symbols-outlined" aria-hidden="true">save</span>Simpan Profil</x-ui.button>
      <x-action-message on="profile-updated"><span class="fm-muted" style="font-size:12px">Tersimpan</span></x-action-message>
    </div>
  </form>
</section>
