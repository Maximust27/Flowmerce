<x-layouts.app :title="'Pengaturan Profil'">
    <div class="max-w-4xl space-y-8 pb-24">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white mb-2">Pengaturan Akun</h1>
            <p class="text-slate-400">Kelola informasi profil, email, dan keamanan akun Anda.</p>
        </div>

        <div class="glass-card inner-glow p-8 rounded-2xl relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <div class="glass-card inner-glow p-8 rounded-2xl relative overflow-hidden">
            <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <div class="glass-card inner-glow p-8 rounded-2xl border-t-2 border-error/30 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-error/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</x-layouts.app>
