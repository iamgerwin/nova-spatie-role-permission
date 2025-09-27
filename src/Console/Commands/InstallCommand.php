<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InstallCommand extends Command
{
    protected $signature = 'nova-permission:install
                            {--seed : Seed default permissions and super-admin role}
                            {--super-admin= : Email of the user to assign super-admin role}';

    protected $description = 'Install and configure Nova Spatie Role Permission package';

    protected array $defaultPermissions = [
        // Role permissions
        'view-roles',
        'create-roles',
        'edit-roles',
        'delete-roles',
        'restore-roles',
        'force-delete-roles',
        'manage-roles',
        'assign-permissions',
        'revoke-permissions',

        // Permission permissions
        'view-permissions',
        'create-permissions',
        'edit-permissions',
        'delete-permissions',
        'restore-permissions',
        'force-delete-permissions',
        'manage-permissions',
        'assign-roles',
        'revoke-roles',
    ];

    public function handle(): int
    {
        $this->info('Installing Nova Spatie Role Permission package...');

        // Publish config if requested
        if ($this->confirm('Do you want to publish the configuration file?', true)) {
            $this->publishConfig();
        }

        // Clear permission cache
        $this->call('permission:cache-reset');

        // Seed permissions if requested
        if ($this->option('seed') || $this->confirm('Do you want to seed default permissions?', true)) {
            $this->seedPermissions();
        }

        // Create super admin role
        if ($this->option('seed') || $this->confirm('Do you want to create super-admin role?', true)) {
            $this->createSuperAdminRole();
        }

        // Assign super admin to user if email provided
        if ($email = $this->option('super-admin')) {
            $this->assignSuperAdmin($email);
        } elseif ($this->confirm('Do you want to assign super-admin role to a user?', false)) {
            $email = $this->ask('Enter the user email');
            if ($email) {
                $this->assignSuperAdmin($email);
            }
        }

        $this->info('Installation completed!');
        $this->info('');
        $this->info('Next steps:');
        $this->info('1. Register the tool in your NovaServiceProvider');
        $this->info('2. Add the tool to your Nova menu');
        $this->info('3. Configure guards if needed in config/nova-permission.php');

        return Command::SUCCESS;
    }

    protected function publishConfig(): void
    {
        $this->call('vendor:publish', [
            '--provider' => 'Iamgerwin\NovaSpatieRolePermission\ToolServiceProvider',
            '--tag' => 'nova-permission-config',
        ]);

        $this->info('Configuration file published to config/nova-permission.php');
    }

    protected function seedPermissions(): void
    {
        $this->info('Seeding default permissions...');

        $guard = config('nova-permission.guard', 'web');

        foreach ($this->defaultPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => $guard],
                ['name' => $permission, 'guard_name' => $guard]
            );
            $this->info("  ✓ Created permission: {$permission}");
        }

        $this->info('Default permissions seeded successfully!');
    }

    protected function createSuperAdminRole(): void
    {
        $this->info('Creating super-admin role...');

        $guard = config('nova-permission.guard', 'web');

        $role = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => $guard],
            ['name' => 'super-admin', 'guard_name' => $guard]
        );

        // Sync all permissions to super-admin
        $role->syncPermissions(Permission::where('guard_name', $guard)->get());

        $this->info('  ✓ Super-admin role created and all permissions assigned');
    }

    protected function assignSuperAdmin(string $email): void
    {
        $userModel = config('auth.providers.users.model');

        if (! class_exists($userModel)) {
            $this->error('User model not found');

            return;
        }

        $user = $userModel::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found");

            return;
        }

        $user->assignRole('super-admin');

        $this->info("  ✓ Super-admin role assigned to {$email}");
    }
}
