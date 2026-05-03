<div class="max-w-[1400px] mx-auto w-full">
    <!-- Page Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white font-headline mb-2">Manajemen Pegawai</h1>
            <p class="text-slate-400 font-light">Monitor organizational health and workforce distributions.</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="exportPdf()" class="btn btn-primary">
                <span class="material-symbols-outlined">download</span>
                Download Report
            </button>
        </div>
    </header>

    @if (session()->has('message'))
        <div class="glass-card p-4 rounded-xl mb-6 border border-primary/30 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">check_circle</span>
            <span class="text-sm font-medium text-primary">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="glass-card p-4 rounded-xl mb-6 border border-error/30 flex items-center gap-3">
            <span class="material-symbols-outlined text-error">error</span>
            <span class="text-sm font-medium text-error">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Summary Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 stagger-enter">
        <div class="glass-card inner-glow p-6 rounded-2xl ai-glow-emerald relative overflow-hidden group border border-white/5">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-7xl">groups</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">groups</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Total Pegawai</p>
            <h3 class="text-3xl font-bold font-jb text-white tracking-tighter">{{ number_format($totalStaff) }}</h3>
        </div>

        <div class="glass-card inner-glow p-6 rounded-2xl relative overflow-hidden group border border-white/5">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-7xl">admin_panel_settings</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">admin_panel_settings</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Total Admin</p>
            <h3 class="text-3xl font-bold font-jb text-white tracking-tighter">{{ number_format($totalAdmins) }}</h3>
        </div>

        <div class="glass-card inner-glow p-6 rounded-2xl ai-glow-violet relative overflow-hidden group border border-white/5">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity text-tertiary">
                <span class="material-symbols-outlined text-7xl">payments</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-tertiary/10 flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined">payments</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Total Kasir</p>
            <h3 class="text-3xl font-bold font-jb text-white tracking-tighter">{{ number_format($totalCashiers) }}</h3>
        </div>

        <div class="glass-card inner-glow p-6 rounded-2xl relative overflow-hidden group border border-white/5">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity text-white/20">
                <span class="material-symbols-outlined text-7xl">analytics</span>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">analytics</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Retensi</p>
            <div class="flex items-center gap-2">
                <h3 class="text-3xl font-bold font-jb text-white tracking-tighter">100%</h3>
                <span class="text-[10px] px-2 py-0.5 bg-primary/20 text-primary rounded-full font-bold uppercase tracking-widest shadow-sm shadow-primary/20 border border-primary/30">Optimal</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-8 mb-8">
        <div class="glass-card p-6 rounded-xl border-l-4 border-emerald-500 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <h2 class="text-lg font-bold text-white">Pintasan</h2>
            <div class="flex gap-4">
                <button wire:click="openModal" class="bg-surface-container-high hover:bg-surface-container-highest py-3 px-6 rounded-xl flex items-center justify-between group transition-all">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-400">person_add</span>
                        <span class="font-medium text-sm text-white">Tambah Pegawai</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Activity Panel (Main Employee Table) -->
    <div class="glass-card rounded-xl overflow-hidden mb-24">
        <div class="p-8 border-b border-white/5 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white font-headline">Daftar Pegawai</h2>
                <p class="text-slate-500 text-sm">Gunakan tombol aksi untuk mengatur peran dan akses.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low/50">
                <tr>
                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Akun Pegawai</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Peran (Role)</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Terdaftar Sejak</th>
                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                @forelse($employees as $employee)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                @if($employee->image)
                                    <img src="{{ Storage::url($employee->image) }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center {{ $employee->role == 'admin' ? 'text-emerald-400' : 'text-sky-400' }} font-bold text-xs">
                                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="text-white font-bold block">{{ $employee->name }}</span>
                                    <span class="text-xs text-slate-500">{{ $employee->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            @if($employee->role == 'admin')
                                <span class="bg-emerald-500/10 text-emerald-400 text-[10px] px-3 py-1.5 rounded-full uppercase font-bold tracking-widest border border-emerald-500/20">Admin</span>
                            @else
                                <span class="bg-sky-500/10 text-sky-400 text-[10px] px-3 py-1.5 rounded-full uppercase font-bold tracking-widest border border-sky-500/20">Kasir</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 font-mono text-xs text-slate-500">
                            {{ $employee->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $employee->id }})" class="p-2 text-slate-400 hover:text-emerald-400 transition-colors" title="Ubah"><span class="material-symbols-outlined text-lg">edit</span></button>
                                <button wire:click="delete({{ $employee->id }})" wire:confirm="Yakin ingin menghapus pegawai ini?" class="p-2 text-slate-400 hover:text-error transition-colors" title="Hapus"><span class="material-symbols-outlined text-lg">delete</span></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-16 text-center text-slate-500">
                            Belum ada pegawai yang terdaftar.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-white/5">
        {{ $employees->links('vendor.pagination.custom', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- Livewire Modal --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
            
            <div class="glass-card-strong w-full max-w-lg rounded-2xl p-6 relative z-10 shadow-2xl border border-white/10 m-4">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">{{ $employee_id ? 'Edit Pegawai' : 'Tambah Pegawai Baru' }}</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="Paijo Kusumo">
                        @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Email</label>
                        <input type="email" wire:model="email" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="paijo@warung.com">
                        @error('email') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">Peran (Role)</label>
                        <select wire:model="role" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30">
                            <option value="admin">Admin</option>
                            <option value="cashier">Kasir</option>
                        </select>
                        @error('role') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-slate-300 mb-2 uppercase tracking-widest text-[10px]">{{ $employee_id ? 'Password Baru (Biarkan kosong jika tidak diubah)' : 'Kata Sandi' }}</label>
                        <input type="password" wire:model="password" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/30" placeholder="Minimal 6 karakter">
                        @error('password') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="pt-4 flex gap-3 justify-end">
                        <button type="button" wire:click="closeModal" class="btn btn-ghost border border-white/10">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Pegawai</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
