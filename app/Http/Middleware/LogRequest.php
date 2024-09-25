<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(config('app.env')!=='testing'){
            Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/request.log'),
                'days' => env('LOG_DAILY_DAYS', 14),
                'replace_placeholders' => true,
              ])->info(json_encode([
                'app_env' => config('app.env'),
                'datetime' => Carbon::now()->toIso8601String(),
                'user_id' => auth()->user() ? auth()->user()->id : 0,
                'method' => request()->getMethod(),
                'ip' => request()->ip(),
                'uri' => request()->getPathInfo(),
                'user_agent' => request()->userAgent(),
            ]));
        }
        return $next($request);
    }
}
