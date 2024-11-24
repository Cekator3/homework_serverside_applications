<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\LogRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Middleware для логгирования HTTP-запросов на API-endpoint-ах
 */
class LogApiRequest
{
    private function getUserId(Route $route, Response $response): int | null
    {
        if (auth()->check())
            return auth()->user()->id;

        $isResponseHasAuthToken = (
            $route->getControllerClass() === "App\Http\Controllers\AuthController"
            && in_array($route->getActionMethod(), ["register", "login"])
        );
        if ($response->isSuccessful() && $isResponseHasAuthToken)
        {
            $publicAuthToken = json_decode($response->getContent())->token;
            $privateAuthToken = PersonalAccessToken::findToken($publicAuthToken);
            return $privateAuthToken->tokenable_id;
        }

        return null;
    }

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
            'user_id' => $this->getUserId($route, $response),
            'user_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'response_status' => $response->getStatusCode(),
            'response_body' => $response->getContent(),
            'response_headers' => $response->headers->all(),
        ]);

        return $response;
    }
}
