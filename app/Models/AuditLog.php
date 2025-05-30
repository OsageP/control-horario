<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'actor_id',
        'action',
        'target_model',
        'target_id',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Usuario afectado
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id'); // Quien hizo el cambio
    }
}
