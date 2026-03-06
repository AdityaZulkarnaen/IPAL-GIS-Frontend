<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsApiAdmin
{
    /**
     * Only allow users whose role is 'admin' (as returned by the backend
     * /api/user endpoint and stored in request attributes by
     * CheckApiAuthenticated).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->attributes->get('api_user');

        if (empty($user) || ($user['role'] ?? null) !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya dapat diakses oleh admin.');
        }

        return $next($request);
    }
}
