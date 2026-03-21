<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->status === 'suspended') {
            // Allow the user to visit the suspended page or logout
            if (!$request->is('suspended') && !$request->is('logout*') && !$request->routeIs('filament.admin.auth.logout')) {
                return redirect()->route('suspended.notice');
            }
        }

        return $next($request);
    }
}
