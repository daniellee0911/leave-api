<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Models\LogError;
use Illuminate\Support\Facades\DB;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware
                    ->api(
                        prepend: [
                            \App\Http\Middleware\PassCookieToHeader::class,
                        ],
                        append: [
                            \App\Http\Middleware\LogRequest::class,
                        ],
                    
                    )
                    ->alias([
                        'admin' => \App\Http\Middleware\CheckIsAdmin::class,
                    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->report(function(Throwable $e){
            $user = auth()->user();
            $log_error = new LogError([
                'user_id' => $user ? $user->id : 0,
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'line' => $e->getLine(),
                'trace' => array_map(function($trace){
                    unset($trace['args']);
                    return $trace;
                },$e->getTrace()),
                'method' => request()->getMethod(),
                'ip' => request()->ip(),
                'uri' => request()->getPathInfo(),
                'user_agent' => request()->userAgent(),
                'header' => request()->headers->all(),
            ]);
            $log_error->save();
           
        });
    })->create();
