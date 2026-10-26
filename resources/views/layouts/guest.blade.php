<x-ui.html-document :title="$title ?? 'Akun'" body-class="fm-auth" description="Masuk, daftar, atau kelola keamanan akun Flowmerce dengan antarmuka yang terintegrasi.">
    <x-ui.auth-shell>{{ $slot }}</x-ui.auth-shell>
</x-ui.html-document>