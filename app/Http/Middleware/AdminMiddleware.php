<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is an admin, for example, using a role check
        if (auth()->user() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Redirect to home page or access denied if not an admin
        return redirect('/home')->with('error', 'Access denied');
    }
}
