<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;

class ForgetCachedPermissions
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('nova-api/*/detach') || $request->is('nova-api/*/attach*') || $request->is('nova-api/*/*/attach*')) {
            $permissionKey = config('permission.cache.key');
            Artisan::call('cache:forget', ['key' => $permissionKey]);
        }

        return $response;
    }
}