# Laravel Nova Spatie Role Permission

[![Latest Version on Packagist](https://img.shields.io/packagist/v/iamgerwin/nova-spatie-role-permission.svg?style=flat-square)](https://packagist.org/packages/iamgerwin/nova-spatie-role-permission)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/iamgerwin/nova-spatie-role-permission/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/iamgerwin/nova-spatie-role-permission/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/iamgerwin/nova-spatie-role-permission.svg?style=flat-square)](https://packagist.org/packages/iamgerwin/nova-spatie-role-permission)

A comprehensive Laravel Nova tool for managing roles and permissions with Spatie's Laravel Permission package. Built for Laravel 11-12, Nova 5, and PHP 8.3+.

## Features

- 🔐 **Complete Role & Permission Management** - Full CRUD operations for roles and permissions
- 🎨 **Nova 5 Compatibility** - Built specifically for the latest Laravel Nova
- 🚀 **Laravel 12 Ready** - Supports both Laravel 11 and 12
- 🔧 **Flexible Configuration** - Customize resources, policies, and guards
- 🌍 **Multi-language Support** - Ready for internationalization
- ⚡ **Performance Optimized** - Automatic cache management for permissions
- ✅ **Fully Tested** - Comprehensive test coverage with Pest
- 📱 **User-friendly Interface** - Intuitive boolean groups and select fields

## Requirements

- PHP 8.3 or higher
- Laravel 11.0 or 12.0
- Laravel Nova 5.0 (required at runtime, not during installation)
- Spatie Laravel Permission 6.0

## Installation

Install the package via composer:

```bash
composer require iamgerwin/nova-spatie-role-permission
```

> **Note:** Laravel Nova must be installed and configured in your application before using this package's features. The package can be installed without Nova for development/testing purposes.

Publish and run the migrations from Spatie Laravel Permission if you haven't already:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

## Usage

### Basic Setup

Register the tool in your `NovaServiceProvider`:

```php
// app/Providers/NovaServiceProvider.php

use Iamgerwin\NovaSpatieRolePermission\NovaSpatieRolePermissionTool;

public function tools()
{
    return [
        new NovaSpatieRolePermissionTool(),
    ];
}
```

### Adding Roles and Permissions to User Resource

You can add role and permission fields to your User Nova resource in multiple ways:

#### Using MorphToMany Field (Recommended)

```php
// app/Nova/User.php

use Laravel\Nova\Fields\MorphToMany;
use Iamgerwin\NovaSpatieRolePermission\Nova\Role;
use Iamgerwin\NovaSpatieRolePermission\Nova\Permission;

public function fields(NovaRequest $request)
{
    return [
        // ... other fields

        MorphToMany::make('Roles', 'roles', Role::class),
        MorphToMany::make('Permissions', 'permissions', Permission::class),
    ];
}
```

#### Using Boolean Group Fields

For a checkbox-style interface:

```php
use Iamgerwin\NovaSpatieRolePermission\Fields\RoleBooleanGroup;
use Iamgerwin\NovaSpatieRolePermission\Fields\PermissionBooleanGroup;

public function fields(NovaRequest $request)
{
    return [
        // ... other fields

        RoleBooleanGroup::make('Roles', 'roles')
            ->options(app(config('permission.models.role'))->pluck('name', 'id')->toArray()),

        PermissionBooleanGroup::make('Permissions', 'permissions')
            ->options(app(config('permission.models.permission'))->pluck('name', 'id')->toArray()),
    ];
}
```

#### Using Select Field for Single Role

```php
use Iamgerwin\NovaSpatieRolePermission\Fields\RoleSelect;

public function fields(NovaRequest $request)
{
    return [
        // ... other fields

        RoleSelect::make('Role', 'role')
            ->options(app(config('permission.models.role'))->pluck('name', 'id')->toArray())
            ->displayUsingLabels(),
    ];
}
```

### Advanced Configuration

#### Custom Resources

You can use your own Nova resources:

```php
use App\Nova\CustomRole;
use App\Nova\CustomPermission;

public function tools()
{
    return [
        (new NovaSpatieRolePermissionTool())
            ->roleResource(CustomRole::class)
            ->permissionResource(CustomPermission::class),
    ];
}
```

#### Custom Policies

Define your own authorization policies:

```php
use App\Policies\CustomRolePolicy;
use App\Policies\CustomPermissionPolicy;

public function tools()
{
    return [
        (new NovaSpatieRolePermissionTool())
            ->rolePolicy(CustomRolePolicy::class)
            ->permissionPolicy(CustomPermissionPolicy::class),
    ];
}
```

#### Guard Configuration

Specify custom guards for roles and permissions:

```php
public function tools()
{
    return [
        (new NovaSpatieRolePermissionTool())
            ->roleGuard('admin')
            ->permissionGuard('api'),
    ];
}
```

### Middleware

The package includes automatic permission cache clearing. To enable it, add the middleware to your Nova middleware group:

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'nova' => [
        // ... other middleware
        \Iamgerwin\NovaSpatieRolePermission\Http\Middleware\ForgetCachedPermissions::class,
    ],
];
```

### Bulk Actions

The package includes a bulk action to attach permissions to roles:

```php
use Iamgerwin\NovaSpatieRolePermission\Actions\AttachToRole;

public function actions(NovaRequest $request)
{
    return [
        new AttachToRole,
    ];
}
```

## Localization

The package supports internationalization. Publish the language files:

```bash
php artisan vendor:publish --tag="nova-spatie-role-permission-translations"
```

Available languages:
- English (en)

You can add your own translations in the `resources/lang/vendor/nova-spatie-role-permission` directory.

## Authorization

By default, all actions are allowed. To implement custom authorization logic:

1. Create custom policy classes:

```php
// app/Policies/RolePolicy.php
namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view roles');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create roles');
    }

    // ... other methods
}
```

2. Register your custom policies with the tool:

```php
public function tools()
{
    return [
        (new NovaSpatieRolePermissionTool())
            ->rolePolicy(App\Policies\RolePolicy::class)
            ->permissionPolicy(App\Policies\PermissionPolicy::class),
    ];
}
```

## Testing

```bash
composer test
```

## Code Quality

Run code formatting:

```bash
composer format
```

Run static analysis:

```bash
composer analyse
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [iamgerwin](https://github.com/iamgerwin)
- [All Contributors](../../contributors)

This package is based on the original work by [vyuldashev/nova-permission](https://github.com/vyuldashev/nova-permission) and has been updated and enhanced for modern Laravel, Nova, and PHP versions.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
