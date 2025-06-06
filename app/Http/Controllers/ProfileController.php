<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Muestra el perfil del usuario
     */
    public function index()
    {
        return view('perfil.index'); // Cambiado a 'perfil.index' para coincidir con tus rutas
    }

    /**
     * Actualiza el perfil del usuario
     */
    public function update(Request $request)
    {
        // Lógica para actualizar el perfil
        return back()->with('status', 'Perfil actualizado');
    }
}