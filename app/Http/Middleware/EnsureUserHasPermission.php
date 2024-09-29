<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * Ensures that a user has specific permission
     */
    public function handle(Request $request, Closure $next, string $permissionName) : Response
    {
        if (! $request->user()->hasPermission($permissionName))
            return Response(
                ['message' => "You don't have required permission: '$permissionName'"],
                Response::HTTP_FORBIDDEN
            );

        return $next($request);
    }
}
