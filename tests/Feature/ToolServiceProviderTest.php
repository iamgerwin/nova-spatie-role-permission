<?php

declare(strict_types=1);

use Iamgerwin\NovaSpatieRolePermission\Nova\Permission;
use Iamgerwin\NovaSpatieRolePermission\Nova\Role;
use Iamgerwin\NovaSpatieRolePermission\NovaSpatieRolePermissionTool;
use Laravel\Nova\Nova;

it('registers the tool with Nova', function () {
    Nova::tools([
        new NovaSpatieRolePermissionTool,
    ]);

    expect(Nova::registeredTools())->toHaveCount(1)
        ->and(Nova::registeredTools()[0])->toBeInstanceOf(NovaSpatieRolePermissionTool::class);
});

it('registers Nova resources', function () {
    $tool = new NovaSpatieRolePermissionTool;
    $tool->boot();

    $resources = Nova::registeredResources();

    expect($resources)->toContain(Role::class)
        ->and($resources)->toContain(Permission::class);
});

it('allows custom role resource', function () {
    $tool = new NovaSpatieRolePermissionTool;
    $tool->roleResource('CustomRoleResource');

    expect($tool->getRoleResource())->toBe('CustomRoleResource');
});

it('allows custom permission resource', function () {
    $tool = new NovaSpatieRolePermissionTool;
    $tool->permissionResource('CustomPermissionResource');

    expect($tool->getPermissionResource())->toBe('CustomPermissionResource');
});

it('allows custom role policy', function () {
    $tool = new NovaSpatieRolePermissionTool;
    $tool->rolePolicy('CustomRolePolicy');

    expect($tool->getRolePolicy())->toBe('CustomRolePolicy');
});

it('allows custom permission policy', function () {
    $tool = new NovaSpatieRolePermissionTool;
    $tool->permissionPolicy('CustomPermissionPolicy');

    expect($tool->getPermissionPolicy())->toBe('CustomPermissionPolicy');
});

it('allows custom guards', function () {
    $tool = new NovaSpatieRolePermissionTool;
    $tool->roleGuard('admin')->permissionGuard('api');

    expect($tool->getRoleGuard())->toBe('admin')
        ->and($tool->getPermissionGuard())->toBe('api');
});
