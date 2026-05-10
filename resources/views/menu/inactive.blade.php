<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <title>Meja Nonaktif — {{ $table->table_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { 
                extend: { 
                    colors: {
                        primary: '#4edea3', 'primary-container': '#10b981',
                        surface: '#0f131f', 'surface-container': '#1b1f2c',
                        'surface-container-high': '#262a37', 'surface-container-highest': '#313442',
                        'on-surface': '#dfe2f3', 'on-surface-variant': '#bbcabf',
                        error: '#ffb4ab', 'surface-container-lowest': '#0a0e1a',
                    }, 
                    fontFamily: { 
                        body: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    } 
                } 
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }
    </style>
</head>
<body class="bg-surface text-on-surface font-body min-h-screen flex flex-col items-center justify-center p-4 selection:bg-primary selection:text-on-primary">
    
    <div class="w-full max-w-md bg-surface-container rounded-[2rem] p-8 shadow-2xl border border-white/5 flex flex-col items-center text-center relative overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-error/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-surface-container-high rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="w-20 h-20 bg-error/20 rounded-full flex items-center justify-center mb-6 ring-8 ring-error/10 relative z-10">
            <span class="material-symbols-outlined text-[40px] text-error" style="font-variation-settings:'FILL' 1">block</span>
        </div>
        
        <h2 class="text-2xl lg:text-3xl font-bold mb-3 text-on-surface relative z-10 tracking-tight">Meja Nonaktif</h2>
        
        <p class="text-on-surface-variant text-sm mb-6 relative z-10 leading-relaxed">
            Mohon maaf, meja <strong>{{ $table->table_number }}</strong> saat ini sedang dinonaktifkan atau belum dibuka oleh admin.
        </p>

        <div class="w-full bg-surface-container-highest rounded-2xl p-5 mb-6 relative z-10 border border-white/5 flex flex-col items-center gap-3">
            <span class="material-symbols-outlined text-[32px] text-on-surface-variant/50">support_agent</span>
            <p class="text-xs text-center text-on-surface-variant">Silakan hubungi staf atau kasir kami untuk mengaktifkan meja ini jika Anda sudah berada di lokasi.</p>
        </div>

        <button onclick="window.location.reload()" class="w-full py-4 rounded-2xl bg-surface-container-highest hover:bg-surface-container-high text-on-surface font-semibold text-sm transition-all border border-white/10 relative z-10 flex items-center justify-center gap-2 active:scale-[0.98]">
            <span class="material-symbols-outlined text-[18px]">refresh</span>
            Muat Ulang Halaman
        </button>
    </div>

</body>
</html>
