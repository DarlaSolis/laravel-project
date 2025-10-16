<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Solo en desarrollo
        if (app()->environment('local')) {
            $response->header(
                'Content-Security-Policy',
                "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://127.0.0.1:8000; style-src 'self' 'unsafe-inline';"
            );
        }

        return $response;
    }
}
