<?php

namespace App\Http\Middleware;

use Closure;

class BusinessActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( !auth()->user()->business->status == 'ACTIVE') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access! Business is inactive'
            ], 401);
        }

        return $next($request);
    }
}
