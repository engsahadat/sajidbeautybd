<?php

namespace App\Http\Middleware;

use App\Lib\Constants;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user) {
            if ($user->role == Constants::ADMIN_ROLE) {
                return $next($request);
            }
            if ($user->role == Constants::USER_ROLE) {
                Auth::logout();

                return redirect('/login');
            }
        }

        return redirect('/admin');
    }
}
