<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $roles  Single role or array of roles allowed
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            // Not logged in
            return redirect()->route('login');
        }

        // If user role is not in allowed roles, deny access
        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized - Insufficient permissions');
        }

        return $next($request);
    }
}
