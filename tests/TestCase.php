<?php

namespace Iamgerwin\NovaSpatieRolePermission\Tests;

use Iamgerwin\NovaSpatieRolePermission\ToolServiceProvider;
use Laravel\Nova\NovaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            NovaServiceProvider::class,
            PermissionServiceProvider::class,
            ToolServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        config()->set('auth.providers.users.model', \Iamgerwin\NovaSpatieRolePermission\Tests\Fixtures\User::class);

        $migration = include __DIR__.'/../vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub';
        $migration->up();
    }
}
