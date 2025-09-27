<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Guard
    |--------------------------------------------------------------------------
    |
    | The default guard to use for roles and permissions.
    |
    */
    'guard' => env('NOVA_PERMISSION_GUARD', 'web'),

    /*
    |--------------------------------------------------------------------------
    | Super Admin
    |--------------------------------------------------------------------------
    |
    | The name of the super admin role. Users with this role
    | bypass all permission checks.
    |
    */
    'super_admin_role' => env('NOVA_PERMISSION_SUPER_ADMIN', 'super-admin'),

    /*
    |--------------------------------------------------------------------------
    | Enable Permissions
    |--------------------------------------------------------------------------
    |
    | Enable or disable permission checking. When disabled, all
    | authorization checks will pass. Useful for development.
    |
    */
    'enable_permissions' => env('NOVA_PERMISSION_ENABLE', true),

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | By default, Spatie Laravel Permission caches permissions and roles.
    | When making changes via Nova, the cache is automatically flushed.
    |
    */
    'cache' => [
        'enabled' => true,
        'key' => 'spatie.permission.cache',
        'expiration_time' => 60 * 24, // 24 hours
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Permissions
    |--------------------------------------------------------------------------
    |
    | These permissions will be created when running the install command.
    | You can add or remove permissions as needed for your application.
    |
    */
    'default_permissions' => [
        // Role management
        'view-roles',
        'create-roles',
        'edit-roles',
        'delete-roles',
        'restore-roles',
        'force-delete-roles',
        'manage-roles',
        'assign-permissions',
        'revoke-permissions',

        // Permission management
        'view-permissions',
        'create-permissions',
        'edit-permissions',
        'delete-permissions',
        'restore-permissions',
        'force-delete-permissions',
        'manage-permissions',
        'assign-roles',
        'revoke-roles',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Override the default models if you need to extend them.
    |
    */
    'models' => [
        'role' => \Spatie\Permission\Models\Role::class,
        'permission' => \Spatie\Permission\Models\Permission::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Nova Resources
    |--------------------------------------------------------------------------
    |
    | Custom Nova resources for roles and permissions.
    |
    */
    'resources' => [
        'role' => \Iamgerwin\NovaSpatieRolePermission\Nova\Role::class,
        'permission' => \Iamgerwin\NovaSpatieRolePermission\Nova\Permission::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Policies
    |--------------------------------------------------------------------------
    |
    | Custom policies for roles and permissions.
    |
    */
    'policies' => [
        'role' => \Iamgerwin\NovaSpatieRolePermission\Policies\RolePolicy::class,
        'permission' => \Iamgerwin\NovaSpatieRolePermission\Policies\PermissionPolicy::class,
    ],
];