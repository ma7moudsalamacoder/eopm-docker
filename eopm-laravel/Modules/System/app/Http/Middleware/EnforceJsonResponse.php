<?php

namespace Modules\System\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnforceJsonResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');
        }
        return $next($request);
    }
}
