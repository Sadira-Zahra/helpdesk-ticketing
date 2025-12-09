<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\C45TrainingController;
use App\Http\Controllers\GantiProfilController;
use App\Http\Controllers\Tiket\TiketController;
use App\Http\Controllers\Master_User\UserController;
use App\Http\Controllers\Master_User\AdminController;
use App\Http\Controllers\Tiket\LaporanTiketController;
use App\Http\Controllers\Master_Data\UrgencyController;
use App\Http\Controllers\Master_User\TeknisiController;
use App\Http\Controllers\Master_Data\DepartemenController;
use App\Http\Controllers\Master_User\AdministratorController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () { 
    return view('welcome'); 
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest Routes (Authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginUser'])->name('login');
    Route::get('login_user', [AuthController::class, 'showLoginUser'])->name('login_user');
    Route::post('login_user', [AuthController::class, 'loginUserPost'])->name('login_user.post');
    
    Route::get('register_user', [AuthController::class, 'showRegisterUser'])->name('register_user');
    Route::post('register_user', [AuthController::class, 'registerUserPost'])->name('register_user.post');
    
    Route::get('login_petugas', [AuthController::class, 'showLoginPetugas'])->name('login_petugas');
    Route::post('login_petugas', [AuthController::class, 'loginPetugasPost'])->name('login_petugas.post');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil Management
    Route::prefix('profil')->name('ganti_profil.')->group(function () {
        Route::get('/', [GantiProfilController::class, 'index'])->name('index');
        Route::put('/update', [GantiProfilController::class, 'update'])->name('update');
        Route::put('/password', [GantiProfilController::class, 'updatePassword'])->name('update_password');
    });
    
    // Master Data - Departemen
    Route::prefix('departemen')->name('departemen.')->group(function () {
        Route::get('/', [DepartemenController::class, 'index'])->name('index');
        Route::post('/', [DepartemenController::class, 'store'])->name('store');
        Route::put('/{id}', [DepartemenController::class, 'update'])->name('update');
        Route::delete('/{id}', [DepartemenController::class, 'destroy'])->name('destroy');
    });
    
    // Master Data - Urgency
    Route::prefix('urgency')->name('urgency.')->group(function () {
        Route::get('/', [UrgencyController::class, 'index'])->name('index');
        Route::post('/', [UrgencyController::class, 'store'])->name('store');
        Route::put('/{id}', [UrgencyController::class, 'update'])->name('update');
        Route::delete('/{id}', [UrgencyController::class, 'destroy'])->name('destroy');
    });
    
    // Master User Management
    Route::prefix('master-user')->name('master_user.')->group(function () {
        
        // Administrator
        Route::prefix('administrator')->name('administrator.')->group(function () {
            Route::get('/', [AdministratorController::class, 'index'])->name('index');
            Route::post('/', [AdministratorController::class, 'store'])->name('store');
            Route::put('/{id}', [AdministratorController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdministratorController::class, 'destroy'])->name('destroy');
        });
        
        // Admin
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');
            Route::post('/', [AdminController::class, 'store'])->name('store');
            Route::put('/{id}', [AdminController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');
        });
        
        // Teknisi
        Route::prefix('teknisi')->name('teknisi.')->group(function () {
            Route::get('/', [TeknisiController::class, 'index'])->name('index');
            Route::post('/', [TeknisiController::class, 'store'])->name('store');
            Route::put('/{id}', [TeknisiController::class, 'update'])->name('update');
            Route::delete('/{id}', [TeknisiController::class, 'destroy'])->name('destroy');
        });
        
        // User
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | TIKET MANAGEMENT
    |--------------------------------------------------------------------------
    | ⚠️ PENTING: Route spesifik HARUS DI ATAS route dengan parameter {id}
    */
    
    Route::prefix('tiket')->name('tiket.')->group(function () {
        
        // ==========================================
        // LAPORAN & ANALYTICS (TARUH PALING ATAS)
        // ==========================================
        
        // ✅ Laporan Tiket (FIXED: tanpa /tiket/ karena sudah dalam prefix)
        Route::get('laporan', [LaporanTiketController::class, 'index'])->name('laporan');
        Route::get('laporan/export', [LaporanTiketController::class, 'export'])->name('laporan.export');
        
        // Analytics
        Route::get('analytics', [TiketController::class, 'analytics'])->name('analytics');
        
        // ==========================================
        // C4.5 TRAINING & MACHINE LEARNING
        // ==========================================
        
        Route::prefix('training')->name('training.')->group(function () {
            Route::get('/', [C45TrainingController::class, 'index'])->name('index');
            Route::post('import', [C45TrainingController::class, 'import'])->name('import');
            Route::post('train', [C45TrainingController::class, 'train'])->name('train');
            Route::get('tree', [C45TrainingController::class, 'viewTree'])->name('tree');
            Route::get('rules', [C45TrainingController::class, 'viewTree'])->name('rules');
            Route::get('template', [C45TrainingController::class, 'downloadTemplate'])->name('template');
            Route::delete('delete-all', [C45TrainingController::class, 'deleteAll'])->name('deleteAll');
        });
        
        // ==========================================
        // BASIC CRUD TIKET
        // ==========================================
        
        Route::get('/', [TiketController::class, 'index'])->name('index');
        Route::post('/', [TiketController::class, 'store'])->name('store');
        
        // ==========================================
        // ADMIN ACTIONS (Assign, Close, etc)
        // ==========================================
        
        Route::post('{id}/assign', [TiketController::class, 'assign'])->name('assign');
        Route::post('{id}/unassign', [TiketController::class, 'unassign'])->name('unassign');
        Route::post('{id}/close', [TiketController::class, 'close'])->name('close');
        
        // ==========================================
        // TEKNISI ACTIONS (Accept, Reject, Complete)
        // ==========================================
        
        Route::post('{id}/accept', [TiketController::class, 'accept'])->name('accept');
        Route::post('{id}/reject', [TiketController::class, 'reject'])->name('reject');
        Route::post('{id}/complete', [TiketController::class, 'complete'])->name('complete');
        
        // ==========================================
        // ADMINISTRATOR ACTIONS
        // ==========================================
        
        Route::post('{id}/reopen', [TiketController::class, 'reopen'])->name('reopen');
        
        // ==========================================
        // DETAIL & DELETE (TARUH PALING BAWAH!)
        // ==========================================
        
        Route::get('{id}', [TiketController::class, 'show'])->name('show');
        Route::delete('{id}', [TiketController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Fallback Route (404 Handler)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    abort(404, 'Halaman tidak ditemukan');
});
