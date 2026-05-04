<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgeVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (config('app.age_gate_enabled', true) && !session('age_verified')) {
            return redirect()->route('age.verify');
        }

        return $next($request);
    }
}
