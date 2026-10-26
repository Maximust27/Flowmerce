<x-layouts.app :title="'Pengaturan Profil'">
  <div class="fm-page">
    <x-ui.page-header eyebrow="Account & Security" title="Pengaturan Akun" description="Perbarui identitas, kata sandi, dan preferensi keamanan akun." />
    <div class="fm-split">
      <div class="fm-stack"><x-ui.card padding="lg"><livewire:profile.update-profile-information-form /></x-ui.card><x-ui.card padding="lg"><livewire:profile.update-password-form /></x-ui.card></div>
      <div class="fm-stack"><x-ui.card padding="lg"><x-ui.section-header title="Profil Bisnis" description="Informasi akun saat ini." /><div class="fm-list" style="margin-top:19px"><div class="fm-list__row">Nama Usaha <span>{{ auth()->user()->business_name ?: 'Belum diisi' }}</span></div><div class="fm-list__row">Kategori Usaha <span>{{ auth()->user()->business_category ?: 'Belum diisi' }}</span></div><div class="fm-list__row">Hak akses <span>{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</span></div></div></x-ui.card><x-ui.card padding="lg"><livewire:profile.delete-user-form /></x-ui.card></div>
    </div>
  </div>
</x-layouts.app>