<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Rute untuk halaman welcome (dapat diakses tanpa login)
Route::get('/', function () {
    // Jika sudah login, alihkan ke dashboard
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    // Jika belum login, tampilkan halaman welcome
    return view('welcome');
})->name('welcome');

// Rute yang memerlukan autentikasi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Route untuk manajemen user
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class, [
        'as' => 'admin'
    ]);
});

require __DIR__.'/auth.php';
