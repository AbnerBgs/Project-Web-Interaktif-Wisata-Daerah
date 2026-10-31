<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware: set locale aplikasi dari session/cookie.
 * Dijalankan pada setiap request agar terjemahan konsisten.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Cek locale di session, default ke 'id'
        $locale = session('locale', config('app.locale', 'id'));

        // Validasi hanya bahasa yang didukung
        if (!in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        app()->setLocale($locale);
        return $next($request);
    }
}
