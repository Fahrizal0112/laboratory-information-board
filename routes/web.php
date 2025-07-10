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

Route::get('/dashboard', function () {
    $approvedMonitorings = \App\Models\Monitoring::with('user')
        ->where(function($query) {
            $query->where('status', 'on_queue')           
                ->orWhere('status', 'in_progress')        
                ->orWhere('status', 'approved_finish');   
        })
        ->latest()
        ->get();
    
    $runningTexts = \App\Models\RunningText::where('active', true)
        ->orderBy('order')
        ->get();
        
    return view('dashboard', compact('approvedMonitorings', 'runningTexts'));
})->name('dashboard');

// Rute yang memerlukan autentikasi
Route::middleware(['auth', 'verified'])->group(function () {
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
    Route::get('/monitorings/completed', [\App\Http\Controllers\Admin\MonitoringController::class, 'completed'])->name('admin.monitorings.completed');
    Route::get('/monitorings/archived', [\App\Http\Controllers\Admin\MonitoringController::class, 'archived'])->name('admin.monitorings.archived');
    Route::post('/monitorings/{monitoring}/approve', [\App\Http\Controllers\Admin\MonitoringController::class, 'approve'])->name('admin.monitorings.approve');
    Route::post('/monitorings/{monitoring}/reject', [\App\Http\Controllers\Admin\MonitoringController::class, 'reject'])->name('admin.monitorings.reject');
    Route::delete('/monitorings/{monitoring}/archive', [\App\Http\Controllers\Admin\MonitoringController::class, 'archive'])->name('admin.monitorings.archive');
    Route::post('/monitorings/{monitoring}/restore', [\App\Http\Controllers\Admin\MonitoringController::class, 'restore'])->name('admin.monitorings.restore');
    Route::delete('/monitorings/{monitoring}/force-delete', [\App\Http\Controllers\Admin\MonitoringController::class, 'forceDelete'])->name('admin.monitorings.force-delete');
    Route::resource('monitorings', \App\Http\Controllers\Admin\MonitoringController::class, [
        'as' => 'admin'
    ]);

    // Route untuk running text
    Route::resource('running-texts', \App\Http\Controllers\Admin\RunningTextController::class, [
        'as' => 'admin'
    ]);
    Route::post('running-texts/reorder', [\App\Http\Controllers\Admin\RunningTextController::class, 'reorder'])->name('admin.running-texts.reorder');
});

// Update route dashboard/data (baris 77-89)
Route::get('/dashboard/data', function () {
    $approvedMonitorings = \App\Models\Monitoring::with('user')
        ->where(function($query) {
            $query->where('status', 'on_queue')           // Status baru: On Queue
                ->orWhere('status', 'in_progress')        // Tetap sama
                ->orWhere('status', 'approved_finish');   // Status baru: Approved & Finish
        })
        ->latest()
        ->get();
    
    $runningTexts = \App\Models\RunningText::where('active', true)
        ->orderBy('order')
        ->get();
        
    return response()->json([
        'approvedMonitorings' => $approvedMonitorings,
        'runningTexts' => $runningTexts
    ]);
});

require __DIR__.'/auth.php';
