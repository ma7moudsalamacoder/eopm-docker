<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\System\Transformers\ActionsResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


$fs = new Filesystem();

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\Modules\System\Http\Middleware\EnforceJsonResponse::class);
 
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return null;
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return ActionsResponse::forbidden();
            }
        });
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            return ActionsResponse::forbidden();
        });
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return new ActionsResponse(message: $e->getMessage(), statusCode: 422, errors: $e->errors());
            }
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return ActionsResponse::notFound();
            }
        });
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $throwable): bool {
            return $request->expectsJson() || $request->routeIs('api.*');
        });
    })
    ->create();
