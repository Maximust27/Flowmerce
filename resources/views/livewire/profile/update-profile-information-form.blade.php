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
    <div class="fm-account__section-heading">
        <div><span class="fm-overview__micro-label">PERSONAL DETAILS</span><h2>Informasi pribadi</h2><p>Ubah nama dan alamat email yang digunakan untuk masuk.</p></div>
        <span class="fm-account__section-icon material-symbols-outlined" aria-hidden="true">badge</span>
    </div>
    <form wire:submit="updateProfileInformation" class="fm-form fm-account__form">
        <div class="fm-form__grid">
            <x-ui.field field="name" label="Nama lengkap" icon="person_outline" wire:model="name" autocomplete="name" required />
            <x-ui.field field="email" label="Alamat email" type="email" icon="alternate_email" wire:model="email" autocomplete="username" required />
        </div>
        @if(auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
            <div class="fm-account__verify">
                <span class="material-symbols-outlined" aria-hidden="true">mark_email_unread</span>
                <div><strong>Email belum terverifikasi</strong><p>Periksa kotak masukmu atau minta tautan verifikasi baru.</p>
                    <button type="button" wire:click="sendVerification" class="fm-auth-link">Kirim ulang verifikasi</button>
                </div>
            </div>
            @if(session('status') === 'verification-link-sent')
                <x-ui.feedback>Tautan verifikasi baru berhasil dikirim.</x-ui.feedback>
            @endif
        @endif
        <div class="fm-account__form-actions">
            <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="updateProfileInformation">
                <span class="material-symbols-outlined" aria-hidden="true">check</span>Simpan perubahan
            </x-ui.button>
            <x-action-message on="profile-updated"><span class="fm-account__saved">Profil berhasil diperbarui</span></x-action-message>
        </div>
    </form>
</section>
