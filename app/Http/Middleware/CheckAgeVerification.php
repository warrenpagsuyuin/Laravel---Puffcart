<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAgeVerification
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('age_verified')) {
            return redirect()->route('age.verify');
        }
        return $next($request);
    }
}
