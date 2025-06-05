<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class Logout
{
    public function __invoke(): void
    {
        try {
            $user = Auth::user();
            Auth::guard('web')->logout();
            
            Session::flush();  // Más agresivo que invalidate()
            Session::regenerateToken();
            
            Log::info('Sesión cerrada', [
                'user_id' => $user?->id,
                'ip' => request()->ip()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cerrar sesión', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}