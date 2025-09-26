<?php

declare(strict_types=1);

use Iamgerwin\NovaSpatieRolePermission\Fields\PermissionBooleanGroup;
use Iamgerwin\NovaSpatieRolePermission\Fields\RoleBooleanGroup;
use Iamgerwin\NovaSpatieRolePermission\Fields\RoleSelect;

it('creates PermissionBooleanGroup field', function () {
    $field = PermissionBooleanGroup::make('Permissions');

    expect($field)->toBeInstanceOf(PermissionBooleanGroup::class)
        ->and($field->attribute)->toBe('permissions');
});

it('creates RoleBooleanGroup field', function () {
    $field = RoleBooleanGroup::make('Roles');

    expect($field)->toBeInstanceOf(RoleBooleanGroup::class)
        ->and($field->attribute)->toBe('roles');
});

it('creates RoleSelect field', function () {
    $field = RoleSelect::make('Role');

    expect($field)->toBeInstanceOf(RoleSelect::class)
        ->and($field->attribute)->toBe('role');
});