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
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        config()->set('auth.providers.users.model', \Iamgerwin\NovaSpatieRolePermission\Tests\Fixtures\User::class);

        // Run migrations
        $this->loadMigrationsFrom(__DIR__.'/../vendor/spatie/laravel-permission/database/migrations');

        // Create users table for testing
        $this->artisan('migrate', ['--database' => 'testing'])->run();

        // Create users table
        \Illuminate\Support\Facades\Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
}
