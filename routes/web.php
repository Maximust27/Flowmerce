<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;
use App\Livewire\DashboardPage;
use App\Livewire\InventoryManager;
use App\Livewire\GudangManager;
use App\Livewire\TransactionManager;
use App\Livewire\AiChat;

Route::view('/', 'welcome');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardPage::class)->name('dashboard');

    // Inventaris
    Route::get('inventaris', InventoryManager::class)->name('inventaris.index');

    // Gudang
    Route::get('gudang', GudangManager::class)->name('gudang.index');
    Route::get('gudang/export', [App\Http\Controllers\GudangExportController::class, 'export'])->name('gudang.pdf');

    // Keuangan
    Route::get('keuangan', TransactionManager::class)->name('keuangan.index');

    // Chat AI
    Route::get('chat', AiChat::class)->name('chat.index');
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
