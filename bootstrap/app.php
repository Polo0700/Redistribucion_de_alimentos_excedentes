<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api/v1/*')) {
                return null;
            }

            if ($e instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los datos enviados no son válidos',
                    'data' => null,
                    'errors' => $e->errors(),
                ], 422);
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            if ($status >= 500) {
                report($e);
            }

            $mensajes = [
                400 => 'La solicitud no es válida',
                401 => 'Debe autenticarse para acceder a este recurso',
                403 => 'No tiene autorización para realizar esta operación',
                404 => 'El recurso solicitado no existe',
                405 => 'El método HTTP no está permitido para este endpoint',
                413 => 'El archivo o la solicitud supera el tamaño permitido',
                415 => 'El tipo de contenido enviado no está permitido',
                422 => 'Los datos enviados no son válidos',
                429 => 'Se realizaron demasiadas solicitudes',
            ];

            return response()->json([
                'success' => false,
                'message' => $mensajes[$status] ?? 'Ocurrió un error interno al procesar la solicitud',
                'data' => null,
                'errors' => null,
            ], $status);
        });
    })->create();
