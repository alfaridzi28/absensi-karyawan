<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (
            ($role === 'admin' && !$user->isAdmin()) ||
            ($role === 'employee' && !$user->isEmployee())
        ) {
            // Logout dan redirect ke login
            Auth::logout();
            return redirect()->route('login')->withErrors(['unauthorized' => 'Akses tidak diizinkan.']);
        }

        return $next($request);
    }
}