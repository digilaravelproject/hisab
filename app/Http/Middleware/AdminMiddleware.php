<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Sirf logged-in admins ko allow karo.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Admin guard check
        if (! Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthorized. Admin login required.',
                ], 401);
            }

            return redirect()
                ->route('admin.login')
                ->with('error', 'Please login to access the admin panel.');
        }

        return $next($request);
    }
}
