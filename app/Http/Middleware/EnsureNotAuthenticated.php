<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotAuthenticated
{
    /**
     * Ensures that a user isn't authenticated with API token
     */
    public function handle(Request $request, Closure $next) : Response
    {
        if ($request->bearerToken())
            return Response(
                ['message' => "Authenticated users can't use this route"],
                Response::HTTP_UNAUTHORIZED
            );

        return $next($request);
    }
}
