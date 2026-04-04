<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Inventaris
    Route::view('inventaris', 'inventaris.index')->name('inventaris.index');

    // Gudang
    Route::view('gudang', 'gudang.index')->name('gudang.index');

    // Keuangan
    Route::view('keuangan', 'keuangan.index')->name('keuangan.index');

    // Chat AI
    Route::view('chat', 'chat.index')->name('chat.index');
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
