<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Modules\System\Transformers\ActionsResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class IsAdminMiddleware
{
    
    public function handle(Request $request, Closure $next): Response | ActionsResponse
    {
        $user = auth("api")->user();
        if (!$user || !$user->hasRole('Administrator')) {
            return ActionsResponse::forbidden('You do not have admin access');
        }
        return $next($request);
    }
}