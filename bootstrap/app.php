<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/v1',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $isApi = fn (Request $request) => $request->is('api/*') || $request->expectsJson();

        $exceptions->shouldRenderJsonWhen($isApi);

        $exceptions->render(function (Throwable $t, Request $request) use ($isApi) {
            if (! $isApi($request)) {
                return null;
            }

            if ($t instanceof AuthenticationException) {
                $title = 'Unauthorized';
                $status = 401;
            } elseif ($t instanceof ValidationException) {
                $status = 422;
                $title = Response::$statusTexts[$status];
                $body['errors'] = $t->errors();
            } elseif ($t instanceof HttpExceptionInterface) {
                $status = $t->getStatusCode();
                $title = Response::$statusTexts[$status] ?? 'Unknown Error';
            } else {
                $title = 'Internal Server Error';
                $status = 500;
            }

            $detail = $status !== 500 ? $t->getMessage() : '';

            return response()->json([
                'type' => 'about:blank',
                'title' => $title,
                'status' => $status,
                ...($detail !== '' ? ['detail' => $detail] : []),
                ...$body ?? [],
            ], $status, ['content-type' => 'application/problem+json']);

        });
    })->create();
