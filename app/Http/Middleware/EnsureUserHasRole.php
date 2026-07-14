<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Contoh validasi: pastikan user sudah login dan kolom role bernilai 'admin'
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    abort(403, 'Unauthorized action.');
}
}
