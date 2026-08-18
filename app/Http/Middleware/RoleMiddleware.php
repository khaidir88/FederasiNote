<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleAccessMiddleware
{
    // public function handle(Request $request, Closure $next)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('login');
    //     }

    //     // Hanya Admin dan Super Admin yang diizinkan
    //     if (!Auth::user()->hasAnyRole(['Admin', 'Super Admin'])) {
    //         return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
    //     }

    //     return $next($request);
    // }
}
