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

        // Create permission tables
        $this->createPermissionTables();
    }

    protected function createPermissionTables(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        $schema = $this->app['db']->connection()->getSchemaBuilder();

        if (! $schema->hasTable($tableNames['permissions'] ?? 'permissions')) {
            $schema->create($tableNames['permissions'] ?? 'permissions', function ($table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
                $table->unique(['name', 'guard_name']);
            });
        }

        if (! $schema->hasTable($tableNames['roles'] ?? 'roles')) {
            $schema->create($tableNames['roles'] ?? 'roles', function ($table) use ($teams) {
                $table->bigIncrements('id');
                if ($teams) {
                    $table->unsignedBigInteger(config('permission.column_names.team_foreign_key', 'team_id'))->nullable();
                    $table->index(config('permission.column_names.team_foreign_key', 'team_id'), 'roles_team_foreign_key_index');
                }
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
                if ($teams) {
                    $table->unique([config('permission.column_names.team_foreign_key', 'team_id'), 'name', 'guard_name']);
                } else {
                    $table->unique(['name', 'guard_name']);
                }
            });
        }

        if (! $schema->hasTable($tableNames['model_has_permissions'] ?? 'model_has_permissions')) {
            $schema->create($tableNames['model_has_permissions'] ?? 'model_has_permissions', function ($table) use ($tableNames, $columnNames, $teams) {
                $table->unsignedBigInteger('permission_id');
                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key'] ?? 'model_id');
                $table->index([$columnNames['model_morph_key'] ?? 'model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
                $table->foreign('permission_id')
                    ->references('id')
                    ->on($tableNames['permissions'] ?? 'permissions')
                    ->onDelete('cascade');
                if ($teams) {
                    $table->unsignedBigInteger(config('permission.column_names.team_foreign_key', 'team_id'));
                    $table->index(config('permission.column_names.team_foreign_key', 'team_id'), 'model_has_permissions_team_foreign_key_index');
                    $table->primary([config('permission.column_names.team_foreign_key', 'team_id'), 'permission_id', $columnNames['model_morph_key'] ?? 'model_id', 'model_type'],
                        'model_has_permissions_permission_model_type_primary');
                } else {
                    $table->primary(['permission_id', $columnNames['model_morph_key'] ?? 'model_id', 'model_type'],
                        'model_has_permissions_permission_model_type_primary');
                }
            });
        }

        if (! $schema->hasTable($tableNames['model_has_roles'] ?? 'model_has_roles')) {
            $schema->create($tableNames['model_has_roles'] ?? 'model_has_roles', function ($table) use ($tableNames, $columnNames, $teams) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key'] ?? 'model_id');
                $table->index([$columnNames['model_morph_key'] ?? 'model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');
                $table->foreign('role_id')
                    ->references('id')
                    ->on($tableNames['roles'] ?? 'roles')
                    ->onDelete('cascade');
                if ($teams) {
                    $table->unsignedBigInteger(config('permission.column_names.team_foreign_key', 'team_id'));
                    $table->index(config('permission.column_names.team_foreign_key', 'team_id'), 'model_has_roles_team_foreign_key_index');
                    $table->primary([config('permission.column_names.team_foreign_key', 'team_id'), 'role_id', $columnNames['model_morph_key'] ?? 'model_id', 'model_type'],
                        'model_has_roles_role_model_type_primary');
                } else {
                    $table->primary(['role_id', $columnNames['model_morph_key'] ?? 'model_id', 'model_type'],
                        'model_has_roles_role_model_type_primary');
                }
            });
        }

        if (! $schema->hasTable($tableNames['role_has_permissions'] ?? 'role_has_permissions')) {
            $schema->create($tableNames['role_has_permissions'] ?? 'role_has_permissions', function ($table) use ($tableNames) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');
                $table->foreign('permission_id')
                    ->references('id')
                    ->on($tableNames['permissions'] ?? 'permissions')
                    ->onDelete('cascade');
                $table->foreign('role_id')
                    ->references('id')
                    ->on($tableNames['roles'] ?? 'roles')
                    ->onDelete('cascade');
                $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
            });
        }

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
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
