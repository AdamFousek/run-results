<?php

namespace App\Http\Middleware;

use App\Models\MeasurementEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            MeasurementEvent::create([
                'type' => 'pageview',
                'attribute' => $request->path(),
                'useragent' => $request->userAgent(),
                'visitorid' => crypt($request->ip(), 'measurement'),
            ]);

            return $response;
        } catch (\Throwable $e) {
            report($e);
            return $response;
        }
    }
}
