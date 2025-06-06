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
        $approvedMonitorings = \App\Models\Monitoring::with('user')
            ->where(function($query) {
                $query->where('status', 'approved')
                    ->orWhere('status', 'in_progress')
                    ->orWhere('status', 'completed');
            })
            ->latest()
            ->get();
        return view('dashboard', compact('approvedMonitorings'));
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk user
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::resource('monitorings', \App\Http\Controllers\User\MonitoringController::class);
});

// Route untuk admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Route untuk manajemen user
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class, [
        'as' => 'admin'
    ]);
    
    // Route untuk monitoring
    Route::get('/monitorings/pending', [\App\Http\Controllers\Admin\MonitoringController::class, 'pending'])->name('admin.monitorings.pending');
    Route::post('/monitorings/{monitoring}/approve', [\App\Http\Controllers\Admin\MonitoringController::class, 'approve'])->name('admin.monitorings.approve');
    Route::post('/monitorings/{monitoring}/reject', [\App\Http\Controllers\Admin\MonitoringController::class, 'reject'])->name('admin.monitorings.reject');
    Route::resource('monitorings', \App\Http\Controllers\Admin\MonitoringController::class, [
        'as' => 'admin'
    ]);
});

require __DIR__.'/auth.php';
