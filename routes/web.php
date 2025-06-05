<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\CompanyManager;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\RolePermissionManager;
use App\Livewire\Admin\AuditLogViewer;

// Rutas Públicas
Route::view('/', 'welcome')->name('welcome');

// Rutas de Autenticación
require __DIR__.'/auth.php';

// Rutas Protegidas
Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
    Route::get('/companies', CompanyManager::class)->name('companies');
    Route::get('/admin/users', UserManager::class)->name('admin.users');
    
    Route::middleware(['permission:view roles'])->group(function () {
        Route::get('/admin/roles', RolePermissionManager::class)->name('admin.roles');
    });
});

// Rutas de SuperAdmin
Route::middleware(['auth', 'role:SuperAdmin'])->group(function () {
    Route::get('/admin/logs', AuditLogViewer::class)->name('admin.logs');
});

// Logout unificado
Route::post('/logout', function () {
    $userId = Auth::id();
    
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    logger()->info('User logged out', [
        'user_id' => $userId,
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent()
    ]);
    
    return redirect('/');
})->name('logout');