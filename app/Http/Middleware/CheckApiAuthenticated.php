<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CheckApiAuthenticated
{
    /**
     * Check whether the request carries a valid Sanctum token issued by
     * the backend.  The token is read from the HttpOnly `api_token` cookie,
     * forwarded to GET /api/user, and — if valid — the user object is stored
     * as a request attribute so controllers/views can access it.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('api_token');

        // No token at all — send to login
        if (empty($token)) {
            return $this->redirectToLogin($request);
        }

        $backendUrl = rtrim(config('services.backend.url'), '/');

        $response = Http::withToken($token)
            ->get("{$backendUrl}/api/user");

        // Token is invalid or expired
        if ($response->unauthorized() || $response->failed()) {
            return $this->redirectToLogin($request)
                ->withCookie(cookie()->forget('api_token'));
        }

        // Attach the user data to the request for downstream use
        $user = $response->json();
        $request->attributes->set('api_user', $user);

        // Keep session in sync
        session(['api_user' => $user]);

        return $next($request);
    }

    private function redirectToLogin(Request $request)
    {
        return redirect()->route('login');
    }
}
