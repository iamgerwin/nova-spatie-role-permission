<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission;

use Iamgerwin\NovaSpatieRolePermission\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ToolServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('nova-spatie-role-permission')
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        $this->routes();

        Nova::serving(function (ServingNova $event) {
            Nova::provideToScript([
                'nova-spatie-role-permission' => [
                    'role_resource' => config('nova-spatie-role-permission.role_resource'),
                    'permission_resource' => config('nova-spatie-role-permission.permission_resource'),
                ],
            ]);
        });
    }

    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authorize::class], 'nova-spatie-role-permission')
            ->group(__DIR__.'/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-spatie-role-permission')
            ->group(__DIR__.'/../routes/api.php');
    }
}
