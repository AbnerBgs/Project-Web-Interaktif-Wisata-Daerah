<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware: pastikan user yang login memiliki role 'admin'.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin yang diizinkan.');
        }
        return $next($request);
    }
}
