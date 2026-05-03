<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;
use App\Livewire\DashboardPage;
use App\Livewire\InventoryManager;
use App\Livewire\GudangManager;
use App\Livewire\TransactionManager;
use App\Livewire\AiChat;
use App\Livewire\Pos\Terminal;
use App\Livewire\TableManager;
use App\Http\Controllers\GuestMenuController;
use App\Http\Controllers\GuestOrderController;

Route::view('/', 'welcome');

// ===== PUBLIC ROUTES — Scan to Order (No Auth Required) =====
Route::get('/menu/{tableCode}', [GuestMenuController::class, 'show'])->name('guest.menu');
Route::post('/menu/{tableCode}/order', [GuestOrderController::class, 'store'])->name('guest.order.store');
Route::get('/order/{orderCode}/status', [GuestOrderController::class, 'status'])->name('guest.order.status');

// Management routes — Only admin can access
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('dashboard', DashboardPage::class)->name('dashboard');

    // Inventaris
    Route::get('inventaris', InventoryManager::class)->name('inventaris.index');
    Route::get('inventaris/export', [App\Http\Controllers\InventoryExportController::class, 'export'])->name('inventaris.pdf');

    // Gudang
    Route::get('gudang', GudangManager::class)->name('gudang.index');
    Route::get('gudang/export', [App\Http\Controllers\GudangExportController::class, 'export'])->name('gudang.pdf');

    // Keuangan
    Route::get('keuangan', TransactionManager::class)->name('keuangan.index');
    Route::get('keuangan/export', [App\Http\Controllers\TransactionExportController::class, 'export'])->name('keuangan.excel');

    // Pegawai
    Route::get('pegawai', App\Livewire\EmployeeManager::class)->name('pegawai.index');
    Route::get('pegawai/export', [App\Http\Controllers\EmployeeExportController::class, 'export'])->name('pegawai.export');

    // Manajemen Meja (Scan to Order)
    Route::get('meja', TableManager::class)->name('meja.index');

    // Chat AI
    Route::get('chat', AiChat::class)->name('chat.index');
});

// POS — Both admin and cashier can access
Route::middleware(['auth', 'role:admin,cashier'])->group(function () {
    Route::get('pos', Terminal::class)->name('pos.index');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Logout
Route::post('logout', function (Logout $logout) {
    $logout();
    return redirect('/');
})->middleware('auth')->name('logout');

require __DIR__.'/auth.php';
