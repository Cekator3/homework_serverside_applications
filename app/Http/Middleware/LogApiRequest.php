<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\LogRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware для логгирования HTTP-запросов на API-endpoint-ах
 */
class LogApiRequest
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $route = $request->route();

        LogRequest::create([
            'url' => $request->fullUrl(),
            'http_method' => $request->method(),
            'controller' => $route->getControllerClass(),
            'controller_method' => $route->getActionMethod(),
            'request_body' => $request->all(),
            'request_headers' => $request->header(),
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'user_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'response_status' => $response->status(),
            'response_body' => $response->getContent(),
            'response_headers' => $response->headers->all(),
        ]);

        return $response;
    }
}
