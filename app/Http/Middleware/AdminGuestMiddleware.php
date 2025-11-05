<?php

namespace App\Http\Middleware;

use App\Lib\Constants;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminGuestMiddleware
{
    /**
     * If user is authenticated, do not allow access to admin auth pages.
     * - Admins -> redirect to admin dashboard
     * - Normal users -> redirect to front home
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user) {
            if ((int) $user->role === Constants::ADMIN_ROLE) {
                return redirect()->route('admin.dashboard');
            }
            // Normal authenticated user
            return redirect()->route('home');
        }

        return $next($request);
    }
}
