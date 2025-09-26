<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Iamgerwin\NovaSpatieRolePermission\NovaSpatieRolePermissionTool;
use Laravel\Nova\Nova;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    public function handle(Request $request, Closure $next): Response
    {
        $tool = collect(Nova::registeredTools())->first([$this, 'matchesTool']);

        return optional($tool)->authorize($request) ? $next($request) : abort(403);
    }

    public function matchesTool($tool): bool
    {
        return $tool instanceof NovaSpatieRolePermissionTool;
    }
}