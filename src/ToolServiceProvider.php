<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission;

use Iamgerwin\NovaSpatieRolePermission\Console\Commands\InstallCommand;
use Iamgerwin\NovaSpatieRolePermission\Console\Commands\VerifyCommand;
use Iamgerwin\NovaSpatieRolePermission\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Log;
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
            ->hasCommands([
                InstallCommand::class,
                VerifyCommand::class,
            ])
            ->hasTranslations();
    }

    public function register()
    {
        parent::register();

        // Merge default config if not published
        $this->mergeConfigFrom(
            __DIR__.'/../config/nova-permission.php',
            'nova-permission'
        );
    }

    public function packageBooted(): void
    {
        // Check if configuration exists and provide helpful error message
        if (! config()->has('nova-permission') && ! file_exists(config_path('nova-permission.php'))) {
            // Configuration will use defaults from mergeConfigFrom, but warn in logs
            Log::warning(
                'Nova Spatie Role Permission: Configuration file not published. '.
                'Using default configuration. To customize, run: '.
                'php artisan vendor:publish --provider="Iamgerwin\NovaSpatieRolePermission\ToolServiceProvider"'
            );
        }

        // Only register Nova routes and listeners if Nova is installed
        if (class_exists(\Laravel\Nova\Nova::class)) {
            $this->routes();

            \Laravel\Nova\Nova::serving(function (\Laravel\Nova\Events\ServingNova $event) {
                try {
                    \Laravel\Nova\Nova::provideToScript([
                        'nova-spatie-role-permission' => [
                            'role_resource' => config('nova-permission.role_resource', \Iamgerwin\NovaSpatieRolePermission\Nova\Role::class),
                            'permission_resource' => config('nova-permission.permission_resource', \Iamgerwin\NovaSpatieRolePermission\Nova\Permission::class),
                        ],
                    ]);
                } catch (\Exception $e) {
                    throw new \Exception(
                        'Nova Spatie Role Permission configuration error: '.$e->getMessage().
                        '. Please ensure configuration is properly set up. '.
                        'Run: php artisan vendor:publish --provider="Iamgerwin\NovaSpatieRolePermission\ToolServiceProvider" && php artisan config:clear'
                    );
                }
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
