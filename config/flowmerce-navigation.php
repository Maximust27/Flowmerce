<?php
// Single source of truth for authenticated navigation. POS stays in the header.
return [
    'items' => [
        ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Ringkasan', 'short' => 'Beranda', 'icon' => 'space_dashboard'],
        ['route' => 'inventaris.index', 'match' => 'inventaris.*', 'label' => 'Inventaris', 'short' => 'Produk', 'icon' => 'inventory_2'],
        ['route' => 'gudang.index', 'match' => 'gudang.*', 'label' => 'Gudang', 'short' => 'Gudang', 'icon' => 'warehouse'],
        ['route' => 'keuangan.index', 'match' => 'keuangan.*', 'label' => 'Keuangan', 'short' => 'Uang', 'icon' => 'account_balance_wallet'],
        ['route' => 'pegawai.index', 'match' => 'pegawai.*', 'label' => 'Pegawai', 'short' => 'Tim', 'icon' => 'groups'],
        ['route' => 'meja.index', 'match' => 'meja.*', 'label' => 'Meja & QR', 'short' => 'Meja', 'icon' => 'table_restaurant'],
        ['route' => 'chat.index', 'match' => 'chat.*', 'label' => 'Asisten AI', 'short' => 'AI', 'icon' => 'auto_awesome'],
    ],
];