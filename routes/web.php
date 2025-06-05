<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\CompanyManager;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\RolePermissionManager;
use App\Livewire\Admin\AuditLogViewer;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('welcome');

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (requieren autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    // Perfil
    Route::view('profile', 'profile')->name('profile');
    
    // Companies
    Route::get('/companies', CompanyManager::class)->name('companies');
    
    // Admin - Users
    Route::get('/admin/users', UserManager::class)->name('admin.users');
    
    // Admin - Roles (protegido por permiso)
    Route::middleware(['permission:view roles'])->group(function () {
        Route::get('/admin/roles', RolePermissionManager::class)->name('admin.roles');
    });
});

/*
|--------------------------------------------------------------------------
| Rutas de SuperAdmin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:SuperAdmin'])->group(function () {
    Route::get('/admin/logs', AuditLogViewer::class)->name('admin.logs');
});

/*
|--------------------------------------------------------------------------
| Logout (versión mejorada con logging)
|--------------------------------------------------------------------------
| Mantenemos ambas implementaciones por compatibilidad:
| 1. La original mediante controlador
| 2. La nueva con logging mejorado (opcional)
*/
// Implementación original (se mantiene)
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Implementación alternativa con logging mejorado
Route::post('/logout-enhanced', function () {
    $userId = Auth::id();
    $userEmail = Auth::user()?->email;
    $ip = request()->ip();
    $userAgent = request()->userAgent();

    // Registrar antes de cerrar sesión
    logger()->info('Solicitud de cierre de sesión', [
        'user_id' => $userId,
        'email' => $userEmail,
        'ip' => $ip,
        'user_agent' => $userAgent,
        'type' => 'enhanced'
    ]);

    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    // Registrar después de cerrar sesión
    logger()->info('Sesión finalizada correctamente', [
        'user_id' => $userId,
        'ip' => $ip,
        'type' => 'enhanced'
    ]);

    return redirect('/');
})->middleware('auth')->name('logout.enhanced');