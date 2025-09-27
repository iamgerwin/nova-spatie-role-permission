<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission;

use Iamgerwin\NovaSpatieRolePermission\Console\Commands\InstallCommand;
use Iamgerwin\NovaSpatieRolePermission\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ToolServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('nova-spatie-role-permission')
            ->hasConfigFile('nova-permission')
            ->hasCommand(InstallCommand::class)
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        // Only register Nova routes and listeners if Nova is installed
        if (class_exists(\Laravel\Nova\Nova::class)) {
            $this->routes();

            \Laravel\Nova\Nova::serving(function (\Laravel\Nova\Events\ServingNova $event) {
                \Laravel\Nova\Nova::provideToScript([
                    'nova-spatie-role-permission' => [
                        'role_resource' => config('nova-spatie-role-permission.role_resource'),
                        'permission_resource' => config('nova-spatie-role-permission.permission_resource'),
                    ],
                ]);
            });
        }
    }

    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        \Laravel\Nova\Nova::router(['nova', Authorize::class], 'nova-spatie-role-permission')
            ->group(__DIR__.'/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-spatie-role-permission')
            ->group(__DIR__.'/../routes/api.php');
    }
}
