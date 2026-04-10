<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! in_array(Auth::user()->rol, $roles)) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder.');
        }

        return $next($request);
    }
}
