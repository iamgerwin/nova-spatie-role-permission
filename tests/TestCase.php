<?php

namespace Iamgerwin\NovaSpatieRolePermission\Tests;

use Iamgerwin\NovaSpatieRolePermission\ToolServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load Nova stubs for testing
        if (! class_exists(\Laravel\Nova\Nova::class)) {
            require_once __DIR__.'/Stubs/Nova.php';
        }

        // Create users table
        $this->app['db']->connection()->getSchemaBuilder()->create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // Run permission migrations
        include_once __DIR__.'/../vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub';
        (new \CreatePermissionTables)->up();
    }

    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
            ToolServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $app['config']->set('auth.providers.users.model', \Iamgerwin\NovaSpatieRolePermission\Tests\Fixtures\User::class);
    }
}
