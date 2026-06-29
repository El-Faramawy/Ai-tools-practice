<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('admin')->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'غير مصرح بالدخول'], 401);
            }
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
