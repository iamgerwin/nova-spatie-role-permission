<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class VerifyCommand extends Command
{
    protected $signature = 'nova-permission:verify';

    protected $description = 'Verify Nova Spatie Role Permission installation and configuration';

    public function handle(): int
    {
        $this->info('🔍 Checking Nova Spatie Role Permission installation...');
        $this->newLine();

        $checks = [
            'Configuration file published' => $this->checkConfigFile(),
            'Nova installed' => $this->checkNova(),
            'Spatie Permission installed' => $this->checkSpatiePermission(),
            'Permission tables migrated' => $this->checkPermissionTables(),
            'Role model configured' => $this->checkRoleModel(),
            'Permission model configured' => $this->checkPermissionModel(),
            'Nova resources registered' => $this->checkNovaResources(),
        ];

        $allPassed = true;
        foreach ($checks as $check => $result) {
            if ($result['passed']) {
                $this->info("✓ {$check}");
                if (! empty($result['info'])) {
                    $this->line("  └─ {$result['info']}");
                }
            } else {
                $this->error("✗ {$check}");
                if (! empty($result['error'])) {
                    $this->line("  └─ {$result['error']}");
                }
                if (! empty($result['fix'])) {
                    $this->warn("  └─ Fix: {$result['fix']}");
                }
                $allPassed = false;
            }
        }

        $this->newLine();

        if ($allPassed) {
            $this->info('✅ All checks passed! Nova Spatie Role Permission is properly installed.');

            return 0;
        } else {
            $this->error('❌ Some checks failed. Please fix the issues above.');
            $this->newLine();
            $this->warn('Quick fix for most issues:');
            $this->line('php artisan nova-permission:install');

            return 1;
        }
    }

    private function checkConfigFile(): array
    {
        $configPath = config_path('nova-permission.php');
        $exists = file_exists($configPath);

        if ($exists) {
            return [
                'passed' => true,
                'info' => 'Config file found at: '.$configPath,
            ];
        }

        return [
            'passed' => false,
            'error' => 'Configuration file not published',
            'fix' => 'php artisan vendor:publish --provider="Iamgerwin\NovaSpatieRolePermission\ToolServiceProvider"',
        ];
    }

    private function checkNova(): array
    {
        if (class_exists(\Laravel\Nova\Nova::class)) {
            return [
                'passed' => true,
                'info' => 'Laravel Nova is installed',
            ];
        }

        return [
            'passed' => false,
            'error' => 'Laravel Nova not found',
            'fix' => 'Install Laravel Nova: composer require laravel/nova',
        ];
    }

    private function checkSpatiePermission(): array
    {
        if (class_exists(\Spatie\Permission\PermissionServiceProvider::class)) {
            return [
                'passed' => true,
                'info' => 'Spatie Laravel Permission is installed',
            ];
        }

        return [
            'passed' => false,
            'error' => 'Spatie Laravel Permission not found',
            'fix' => 'composer require spatie/laravel-permission',
        ];
    }

    private function checkPermissionTables(): array
    {
        $tables = [
            'permissions' => config('permission.table_names.permissions', 'permissions'),
            'roles' => config('permission.table_names.roles', 'roles'),
            'model_has_permissions' => config('permission.table_names.model_has_permissions', 'model_has_permissions'),
            'model_has_roles' => config('permission.table_names.model_has_roles', 'model_has_roles'),
            'role_has_permissions' => config('permission.table_names.role_has_permissions', 'role_has_permissions'),
        ];

        $missingTables = [];
        foreach ($tables as $name => $tableName) {
            if (! Schema::hasTable($tableName)) {
                $missingTables[] = $tableName;
            }
        }

        if (empty($missingTables)) {
            return [
                'passed' => true,
                'info' => 'All permission tables exist',
            ];
        }

        return [
            'passed' => false,
            'error' => 'Missing tables: '.implode(', ', $missingTables),
            'fix' => 'php artisan migrate',
        ];
    }

    private function checkRoleModel(): array
    {
        $roleModel = config('permission.models.role');

        if ($roleModel && class_exists($roleModel)) {
            return [
                'passed' => true,
                'info' => 'Role model: '.$roleModel,
            ];
        }

        return [
            'passed' => false,
            'error' => 'Role model not found or not configured',
            'fix' => 'Check config/permission.php for role model configuration',
        ];
    }

    private function checkPermissionModel(): array
    {
        $permissionModel = config('permission.models.permission');

        if ($permissionModel && class_exists($permissionModel)) {
            return [
                'passed' => true,
                'info' => 'Permission model: '.$permissionModel,
            ];
        }

        return [
            'passed' => false,
            'error' => 'Permission model not found or not configured',
            'fix' => 'Check config/permission.php for permission model configuration',
        ];
    }

    private function checkNovaResources(): array
    {
        if (! class_exists(\Laravel\Nova\Nova::class)) {
            return [
                'passed' => false,
                'error' => 'Nova not installed, skipping resource check',
                'fix' => 'Install Nova first',
            ];
        }

        $roleResource = config('nova-permission.role_resource', \Iamgerwin\NovaSpatieRolePermission\Nova\Role::class);
        $permissionResource = config('nova-permission.permission_resource', \Iamgerwin\NovaSpatieRolePermission\Nova\Permission::class);

        $issues = [];

        if (! class_exists($roleResource)) {
            $issues[] = "Role resource not found: {$roleResource}";
        }

        if (! class_exists($permissionResource)) {
            $issues[] = "Permission resource not found: {$permissionResource}";
        }

        if (empty($issues)) {
            return [
                'passed' => true,
                'info' => 'Nova resources configured correctly',
            ];
        }

        return [
            'passed' => false,
            'error' => implode(', ', $issues),
            'fix' => 'Check nova-permission.php configuration or run: php artisan nova-permission:install',
        ];
    }
}
