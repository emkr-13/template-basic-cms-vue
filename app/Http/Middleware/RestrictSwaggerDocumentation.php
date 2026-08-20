<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictSwaggerDocumentation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->isProduction() && ! $request->user()?->hasRole(RoleEnum::SUPER_ADMIN->value)) {
            abort(404);
        }

        return $next($request);
    }
}
