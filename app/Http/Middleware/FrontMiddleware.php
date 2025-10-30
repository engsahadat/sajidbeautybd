<?php

namespace App\Http\Middleware;

use App\Lib\Constants;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FrontMiddleware
{
    /**
     * Handle an incoming request.
     * - If authenticated and role is admin, redirect to admin dashboard.
     * - Otherwise allow.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && (int) $user->role === Constants::ADMIN_ROLE) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
