<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    /**
     * Handle the login form POST.
     * Forwards credentials to the backend API, stores the returned
     * Sanctum token as an HttpOnly cookie, and saves user data in session.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $backendUrl = rtrim(config('services.backend.url'), '/');

        $response = Http::post("{$backendUrl}/api/auth/login", [
            'username' => $validated['username'],
            'password' => $validated['password'],
        ]);

        // Backend returned 422 (validation) or 401 (wrong credentials)
        if ($response->failed()) {
            $message = $response->json('message') ?? 'Username atau password salah.';

            return redirect()->route('login')
                ->withInput($request->only('username'))
                ->with('error', $message);
        }

        $data  = $response->json();
        $token = $data['token'];
        $user  = $data['user'];

        // Store user data in the session (non-sensitive)
        session(['api_user' => $user]);

        // Store the token as an HttpOnly cookie (JS cannot read it)
        $cookie = cookie(
            name: 'api_token',
            value: $token,
            minutes: 0,          // 0 = session cookie (expires when browser closes)
            path: '/',
            domain: null,
            secure: app()->isProduction(),
            httpOnly: true,
            raw: false,
            sameSite: 'lax',
        );

        return redirect()->intended('/')->withCookie($cookie);
    }

    /**
     * Log out: revoke the token on the backend, clear the cookie and session.
     */
    public function logout(Request $request)
    {
        $token = $request->cookie('api_token');

        if ($token) {
            $backendUrl = rtrim(config('services.backend.url'), '/');

            // Best-effort: revoke token on the backend; ignore errors
            Http::withToken($token)
                ->post("{$backendUrl}/api/auth/logout");
        }

        // Destroy local session and expire the cookie
        $request->session()->flush();

        $expiredCookie = cookie()->forget('api_token');

        return redirect()->route('login')->withCookie($expiredCookie);
    }
}
