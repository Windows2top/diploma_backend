<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user_role = $request->user()->role;
        if (in_array($user_role, ['teacher', 'admin'])) {
            return $next($request);
        } else {
            return response()->json(['message' => 'вы не учитель'], 403);
        }
    }
}
