<?php

namespace App\Http\Middleware;

use App\Models\MeasurementEvent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Crypt;

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
                'visitorid' => Crypt::encryptString($request->ip()),
            ]);

            return $response;
        } catch (\Throwable $e) {
            report($e);
            return $response;
        }
    }
}
