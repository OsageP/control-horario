<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class LogUserActivity
{
    /**
     * Maneja la petición y registra la actividad del usuario si corresponde.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check() && $this->shouldLog($request)) {
            AuditLog::create([
                'user_id'     => auth()->id(),
                'action'      => $request->method(), // POST, PUT, DELETE...
                'entity_type' => null,
                'entity_id'   => null,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'url'         => $request->fullUrl(),
                'method'      => $request->method(),
                'old_values'  => null,
                'new_values'  => null,
            ]);
        }

        return $response;
    }

    /**
     * Determina si la petición debe ser registrada (evita GET, OPTIONS...).
     */
    protected function shouldLog(Request $request)
    {
        return !in_array($request->method(), ['GET', 'HEAD', 'OPTIONS']);
    }
}
