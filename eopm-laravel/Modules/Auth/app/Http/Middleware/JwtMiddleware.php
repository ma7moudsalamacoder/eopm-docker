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

class JwtMiddleware
{
    
    public function handle(Request $request, Closure $next): ActionsResponse
    {
       
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return ActionsResponse::notFound('User not found');
            }

            if (!$user->isActive()) {
                return ActionsResponse::forbidden('Your account is not active');
             
            }

        } catch (TokenExpiredException $e) {
            return ActionsResponse::failed(message:'Token has expired', statusCode:401,errors:['token_expired' => $e->getMessage()]);
        } catch (TokenInvalidException $e) {
            return ActionsResponse::failed(message:'Token is invalid', statusCode:401,errors:['token_invalid' => $e->getMessage()]);
            
        } catch (Exception $e) {
            return ActionsResponse::failed(message:'Authorization token not found', statusCode:401,errors:['authorization_token_not_found' => $e->getMessage()]);
        }

        return $next($request);
    }
}