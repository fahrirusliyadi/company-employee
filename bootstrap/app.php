<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle custom application exceptions
        $exceptions->render(function (\App\Exceptions\ApplicationException $e, \Illuminate\Http\Request $request) {
            return $e->render($request);
        });

        // Handle validation exceptions for Inertia requests
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, \Illuminate\Http\Request $request) {
            if ($request->header('X-Inertia')) {
                return redirect()->back()
                    ->with('error', 'Please check the form for errors and try again.')
                    ->withErrors($e->errors())
                    ->withInput();
            }
        });

        // Handle general exceptions for Inertia requests
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->header('X-Inertia') && !($e instanceof \Illuminate\Validation\ValidationException)) {
                return redirect()->back()
                    ->with('error', 'An unexpected error occurred. Please try again.')
                    ->withInput();
            }
        });
    })->create();
