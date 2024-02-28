<?php

namespace App\Http\Middleware;

use App\Models\Illuminate\MeasurementEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
            $path = $request->path();

            if (App::environment('local') || str_contains($path, 'admin/'))  {
                return $response;
            }

            MeasurementEvent::create([
                'type' => 'pageview',
                'attribute' => $path,
                'useragent' => $request->userAgent(),
                'visitorid' => crypt($request->ip() ?? 'not-recognized', 'measurement'),
            ]);

            return $response;
        } catch (\Throwable $e) {
            report($e);
            return $response;
        }
    }
}
