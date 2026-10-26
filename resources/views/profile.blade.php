<x-layouts.app :title="'Pengaturan Akun'">
    <div class="fm-page fm-account">
        <x-ui.page-header eyebrow="PREFERENSI / AKUN" title="Pengaturan Akun" description="Identitas, keamanan, dan informasi ruang kerja bisnismu." />

        <section class="fm-account__identity" aria-label="Identitas akun">
            <span class="fm-account__avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
            <div class="fm-account__identity-info">
                <span class="fm-account__eyebrow">AKUN AKTIF</span>
                <h2>{{ auth()->user()->name }}</h2>
                <p>{{ auth()->user()->email }}</p>
            </div>
            <x-ui.badge :tone="auth()->user()->role === 'admin' ? 'success' : 'accent'">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</x-ui.badge>
        </section>

        <div class="fm-account__layout">
            <div class="fm-account__primary">
                <x-ui.card padding="lg" class="fm-account__card">
                    <livewire:profile.update-profile-information-form />
                </x-ui.card>
                <x-ui.card padding="lg" class="fm-account__card">
                    <livewire:profile.update-password-form />
                </x-ui.card>
            </div>

            <aside class="fm-account__secondary" aria-label="Informasi dan keamanan akun">
                <x-ui.card padding="lg" class="fm-account__card">
                    <div class="fm-account__card-heading">
                        <span class="fm-account__card-icon"><span class="material-symbols-outlined" aria-hidden="true">storefront</span></span>
                        <div><p class="fm-overview__micro-label">WORKSPACE</p><h2>Profil usaha</h2></div>
                    </div>
                    <dl class="fm-account__details">
                        <div><dt>Nama usaha</dt><dd>{{ auth()->user()->business_name ?: 'Belum tersedia' }}</dd></div>
                        <div><dt>Kategori usaha</dt><dd>{{ auth()->user()->business_category ?: 'Belum tersedia' }}</dd></div>
                        <div><dt>Jenis akun</dt><dd>{{ auth()->user()->role === 'admin' ? 'Administrator bisnis' : 'Kasir' }}</dd></div>
                    </dl>
                </x-ui.card>

                <x-ui.card padding="lg" class="fm-account__card fm-account__danger">
                    <livewire:profile.delete-user-form />
                </x-ui.card>
            </aside>
        </div>
    </div>
</x-layouts.app>