<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CompanyManager;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\RolePermissionManager;

Route::middleware(['auth'])->group(function () {
    // ✅ Ahora protegida por permiso 'view roles', no por rol
    Route::middleware(['permission:view roles'])->group(function () {
        Route::get('/admin/roles', RolePermissionManager::class)->name('admin.roles');
    });

    Route::get('/admin/users', UserManager::class)->name('admin.users');
});

Route::get('/companies', CompanyManager::class)->middleware('auth');

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

require __DIR__.'/auth.php';
