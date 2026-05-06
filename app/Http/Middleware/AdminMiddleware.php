<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'You do not have access to the admin area.');
        }

        return $next($request);
    }
}
