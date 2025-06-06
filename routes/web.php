<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\CompanyManager;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\RolePermissionManager;
use App\Livewire\Admin\AuditLogViewer;
use App\Http\Controllers\ProfileController; // Asegurar que está importado
use App\Http\Controllers\AdminController; // Asegurar que está importado

// Rutas Públicas
Route::view('/', 'welcome')->name('welcome');

// Rutas de Autenticación
require __DIR__.'/auth.php';

// Rutas Protegidas
Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    // Eliminar esta línea duplicada del perfil
    // Route::view('profile', 'profile')->name('profile'); // ← ESTA SOBRA
    
    Route::get('/companies', CompanyManager::class)->name('companies.index'); // Cambiado para consistencia
    Route::get('/admin/users', UserManager::class)->name('admin.users.index'); // Cambiado para consistencia
    
    Route::middleware(['permission:view roles'])->group(function () {
        Route::get('/admin/roles', RolePermissionManager::class)->name('admin.roles.index'); // Cambiado
    });
});

// Rutas de Perfil (corregidas y organizadas)
Route::middleware(['auth'])->controller(ProfileController::class)->group(function(){
    Route::get('/perfil', 'index')->name('perfil.index'); // Nombre consistente
    Route::put('/perfil/actualizar', 'update')->name('perfil.update');
});

// Rutas de SuperAdmin
Route::middleware(['auth', 'role:SuperAdmin'])->group(function () {
    Route::get('/admin/logs', AuditLogViewer::class)->name('admin.logs');
    
    // Eliminar esta duplicada:
    // Route::get('/admin/configuracion', [AdminController::class, 'settings'])
    //    ->name('admin.settings')
    //    ->middleware(['auth', 'role:SuperAdmin']); // ← ESTA SOBRA
    
    Route::get('/admin/settings', [AdminController::class, 'settings'])
        ->name('admin.settings');
});

// Logout unificado (correcto)
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