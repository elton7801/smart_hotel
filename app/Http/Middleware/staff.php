<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class staff
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->usertype === 'staff') {
            return $next($request);
        }
        return redirect('/login')->with('error', 'Access denied.');
    }
}
