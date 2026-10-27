<div class="fm-page">
  <x-ui.page-header eyebrow="People & Permissions" title="Manajemen Pegawai" description="Kelola akun tim dan peran kasir serta administrator bisnis.">
    <x-slot:actions>
      <x-ui.button variant="secondary" wire:click="exportPdf"><span class="material-symbols-outlined" aria-hidden="true">file_download</span>Ekspor PDF</x-ui.button>
      <x-ui.button wire:click="openModal"><span class="material-symbols-outlined" aria-hidden="true">person_add</span>Tambah Pegawai</x-ui.button>
    </x-slot:actions>
  </x-ui.page-header>
  @if(session()->has('message'))<x-ui.feedback>{{ session('message') }}</x-ui.feedback>@endif
  @if(session()->has('error'))<x-ui.feedback tone="danger">{{ session('error') }}</x-ui.feedback>@endif
  <section class="fm-kpi-grid" aria-label="Ringkasan pegawai">
    <x-ui.stat-card label="Total Pegawai" :value="number_format($totalStaff, 0, ',', '.')" icon="groups" hint="Akun terdaftar di usaha" />
    <x-ui.stat-card label="Administrator" :value="number_format($totalAdmins, 0, ',', '.')" icon="admin_panel_settings" tone="success" hint="Akses manajemen bisnis" />
    <x-ui.stat-card label="Kasir" :value="number_format($totalCashiers, 0, ',', '.')" icon="point_of_sale" variant="accent" hint="Akses terminal kasir" />
  </section>
  <x-ui.section-header title="Daftar Tim" description="Ubah nama, email, peran, dan akses setiap pegawai.">
    <x-slot:actions><x-ui.badge tone="accent">{{ number_format($totalStaff) }} akun</x-ui.badge></x-slot:actions>
  </x-ui.section-header>
  <x-ui.table label="Daftar akun pegawai">
    <x-slot:head><tr><th scope="col">Pegawai</th><th scope="col">Hak akses</th><th scope="col">Bergabung</th><th scope="col" class="text-right">Aksi</th></tr></x-slot:head>
    @forelse($employees as $employee)
      <tr wire:key="employee-{{ $employee->id }}">
        <td><div class="fm-data-table__product">
          @if($employee->image)<img class="fm-data-table__img" src="{{ Storage::url($employee->image) }}" alt="Foto {{ $employee->name }}" loading="lazy">
          @else<span class="fm-avatar fm-avatar--small">{{ mb_strtoupper(mb_substr($employee->name, 0, 2)) }}</span>@endif
          <div><p class="fm-data-table__product-name">{{ $employee->name }}</p><p class="fm-data-table__secondary">{{ $employee->email }}</p></div></div></td>
        <td><x-ui.badge :tone="$employee->role === 'admin' ? 'success' : 'accent'">{{ $employee->role === 'admin' ? 'Admin' : 'Kasir' }}</x-ui.badge></td>
        <td class="fm-mono fm-nowrap" data-muted="true">{{ $employee->created_at->format('d M Y') }}</td>
        <td><div class="fm-data-table__actions">
          <button type="button" class="fm-icon-button" wire:click="edit({{ $employee->id }})" title="Ubah pegawai" aria-label="Ubah {{ $employee->name }}"><span class="material-symbols-outlined" aria-hidden="true">edit</span></button>
          <button type="button" class="fm-icon-button" data-tone="danger" wire:click="delete({{ $employee->id }})" wire:confirm="Yakin ingin menghapus pegawai ini?" title="Hapus pegawai" aria-label="Hapus {{ $employee->name }}"><span class="material-symbols-outlined" aria-hidden="true">delete_outline</span></button>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="4"><x-ui.empty-state icon="group_add" title="Belum ada pegawai" description="Tambahkan anggota tim untuk mengelola transaksi dan operasional." /></td></tr>
    @endforelse
    <x-slot:footer>{{ $employees->links('vendor.pagination.custom', data: ['scrollTo' => false]) }}</x-slot:footer>
  </x-ui.table>

  @if($isModalOpen)
  <x-ui.modal :title="$employee_id ? 'Ubah Pegawai' : 'Tambah Pegawai'">
    <form wire:submit.prevent="save" class="fm-form">
      <x-ui.field field="name" label="Nama Lengkap" wire:model="name" placeholder="Nama pegawai" required />
      <x-ui.field field="email" label="Email" type="email" wire:model="email" placeholder="pegawai@bisnis.com" required />
      <x-ui.select-field field="role" label="Hak Akses" wire:model="role" required><option value="admin">Administrator</option><option value="cashier">Kasir</option></x-ui.select-field>
      <x-ui.field field="password" label="{{ $employee_id ? 'Password Baru (opsional)' : 'Password' }}" type="password" wire:model="password" autocomplete="new-password" placeholder="{{ $employee_id ? 'Kosongkan untuk tidak mengganti' : 'Minimal 6 karakter' }}" />
      <p class="fm-note">Pastikan akses pegawai sesuai tugas. Akun kasir akan diarahkan ke terminal POS.</p>
      <div class="fm-form__actions"><x-ui.button variant="secondary" wire:click="closeModal">Batal</x-ui.button><x-ui.button type="submit" wire:loading.attr="disabled" wire:target="save"><span class="material-symbols-outlined" aria-hidden="true">check</span>Simpan Pegawai</x-ui.button></div>
    </form>
  </x-ui.modal>
  @endif
</div>