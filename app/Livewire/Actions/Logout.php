<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Cierra la sesión del usuario de manera segura con registro en logs.
     */
    public function __invoke(): void
    {
        // Obtener información del usuario antes de cerrar sesión
        $userId = Auth::id();
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        
        // Proceso de cierre de sesión
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();
        
        // Registrar el evento
        logger()->info('User logged out (Livewire)', [
            'user_id' => $userId,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'method' => 'Livewire/Volt'
        ]);
    }
}