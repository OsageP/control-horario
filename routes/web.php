<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CompanyManager;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\RolePermissionManager;

Route::middleware(['auth', 'role:SuperAdmin'])->group(function () {
    Route::get('/admin/roles', RolePermissionManager::class)->name('admin.roles');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', UserManager::class)->name('admin.users');
});


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/companies', CompanyManager::class)->middleware('auth');

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
