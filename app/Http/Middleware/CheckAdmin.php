<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role === 'admin') {
            return $next($request);
        } else {
            return response()->json(['message' => 'Вы не админ'], 403);
        }
    }
}
