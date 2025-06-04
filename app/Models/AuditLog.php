<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    // Campos que se pueden rellenar masivamente
    protected $fillable = [
        'entity_type',     // Clase del modelo afectado (User, Role, etc.)
        'entity_id',       // ID del modelo afectado
        'action',          // created, updated, deleted, login, etc.
        'old_values',      // Valores anteriores (como array)
        'new_values',      // Nuevos valores (como array)
        'actor_id',        // Usuario que ejecutó la acción
        'user_id',         // Alternativa para compatibilidad
        'ip_address',      // IP del usuario
        'user_agent',      // Navegador/dispositivo
        'description',     // Descripción libre del evento
        'url',             // URL donde se ejecutó
        'method',          // Método HTTP (POST, PUT, DELETE...)
    ];

    // Casts automáticos
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Relación con el usuario que ejecutó la acción
     */
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Alias por compatibilidad (puedes usar $log->user también)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Entidad afectada: relación polimórfica
     */
    public function entity()
    {
        return $this->morphTo();
    }
}
