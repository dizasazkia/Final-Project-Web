<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!in_array(Auth::user()->role, $roles)) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke sumber daya ini.');
        }

        return $next($request);
    }
}

