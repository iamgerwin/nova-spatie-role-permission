<?php

declare(strict_types=1);

use Iamgerwin\NovaSpatieRolePermission\Policies\PermissionPolicy;
use Iamgerwin\NovaSpatieRolePermission\Policies\RolePolicy;
use Iamgerwin\NovaSpatieRolePermission\Tests\Fixtures\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
});

describe('RolePolicy', function () {
    it('denies actions when user has no permissions', function () {
        $policy = new RolePolicy;
        $role = Role::create(['name' => 'test-role']);

        expect($policy->viewAny($this->user))->toBeFalse()
            ->and($policy->view($this->user, $role))->toBeFalse()
            ->and($policy->create($this->user))->toBeFalse()
            ->and($policy->update($this->user, $role))->toBeFalse()
            ->and($policy->delete($this->user, $role))->toBeFalse()
            ->and($policy->restore($this->user, $role))->toBeFalse()
            ->and($policy->forceDelete($this->user, $role))->toBeFalse();
    });

    it('allows actions when user has specific permissions', function () {
        $policy = new RolePolicy;
        $role = Role::create(['name' => 'test-role']);

        // Create and assign specific permissions
        $viewPermission = Permission::create(['name' => 'view-roles']);
        $createPermission = Permission::create(['name' => 'create-roles']);
        $editPermission = Permission::create(['name' => 'edit-roles']);
        $deletePermission = Permission::create(['name' => 'delete-roles']);

        $this->user->givePermissionTo($viewPermission);
        expect($policy->viewAny($this->user))->toBeTrue()
            ->and($policy->view($this->user, $role))->toBeTrue();

        $this->user->givePermissionTo($createPermission);
        expect($policy->create($this->user))->toBeTrue();

        $this->user->givePermissionTo($editPermission);
        expect($policy->update($this->user, $role))->toBeTrue();

        $this->user->givePermissionTo($deletePermission);
        expect($policy->delete($this->user, $role))->toBeTrue();
    });

    it('allows all actions when user has manage-roles permission', function () {
        $policy = new RolePolicy;
        $role = Role::create(['name' => 'test-role']);
        $permission = Permission::create(['name' => 'manage-roles']);

        $this->user->givePermissionTo($permission);

        expect($policy->viewAny($this->user))->toBeTrue()
            ->and($policy->view($this->user, $role))->toBeTrue()
            ->and($policy->create($this->user))->toBeTrue()
            ->and($policy->update($this->user, $role))->toBeTrue()
            ->and($policy->delete($this->user, $role))->toBeTrue()
            ->and($policy->restore($this->user, $role))->toBeTrue()
            ->and($policy->forceDelete($this->user, $role))->toBeTrue();
    });

    it('allows all actions for super admin', function () {
        $policy = new RolePolicy;
        $role = Role::create(['name' => 'test-role']);
        $superAdminRole = Role::create(['name' => 'super-admin']);

        $this->user->assignRole($superAdminRole);

        expect($policy->viewAny($this->user))->toBeTrue()
            ->and($policy->view($this->user, $role))->toBeTrue()
            ->and($policy->create($this->user))->toBeTrue()
            ->and($policy->update($this->user, $role))->toBeTrue()
            ->and($policy->delete($this->user, $role))->toBeTrue()
            ->and($policy->restore($this->user, $role))->toBeTrue()
            ->and($policy->forceDelete($this->user, $role))->toBeTrue();
    });

    it('allows attach and detach permissions with proper permissions', function () {
        $policy = new RolePolicy;
        $role = Role::create(['name' => 'test-role']);
        $permission = Permission::create(['name' => 'edit posts']);

        // Should deny without permissions
        expect($policy->attachPermission($this->user, $role, $permission))->toBeFalse()
            ->and($policy->detachPermission($this->user, $role, $permission))->toBeFalse();

        // Should allow with specific permissions
        $assignPermission = Permission::create(['name' => 'assign-permissions']);
        $revokePermission = Permission::create(['name' => 'revoke-permissions']);

        $this->user->givePermissionTo($assignPermission);
        expect($policy->attachPermission($this->user, $role, $permission))->toBeTrue();

        $this->user->givePermissionTo($revokePermission);
        expect($policy->detachPermission($this->user, $role, $permission))->toBeTrue();
    });
});

describe('PermissionPolicy', function () {
    it('denies actions when user has no permissions', function () {
        $policy = new PermissionPolicy;
        $permission = Permission::create(['name' => 'test-permission']);

        expect($policy->viewAny($this->user))->toBeFalse()
            ->and($policy->view($this->user, $permission))->toBeFalse()
            ->and($policy->create($this->user))->toBeFalse()
            ->and($policy->update($this->user, $permission))->toBeFalse()
            ->and($policy->delete($this->user, $permission))->toBeFalse()
            ->and($policy->restore($this->user, $permission))->toBeFalse()
            ->and($policy->forceDelete($this->user, $permission))->toBeFalse();
    });

    it('allows actions when user has specific permissions', function () {
        $policy = new PermissionPolicy;
        $permission = Permission::create(['name' => 'test-permission']);

        // Create and assign specific permissions
        $viewPermission = Permission::create(['name' => 'view-permissions']);
        $createPermission = Permission::create(['name' => 'create-permissions']);
        $editPermission = Permission::create(['name' => 'edit-permissions']);
        $deletePermission = Permission::create(['name' => 'delete-permissions']);

        $this->user->givePermissionTo($viewPermission);
        expect($policy->viewAny($this->user))->toBeTrue()
            ->and($policy->view($this->user, $permission))->toBeTrue();

        $this->user->givePermissionTo($createPermission);
        expect($policy->create($this->user))->toBeTrue();

        $this->user->givePermissionTo($editPermission);
        expect($policy->update($this->user, $permission))->toBeTrue();

        $this->user->givePermissionTo($deletePermission);
        expect($policy->delete($this->user, $permission))->toBeTrue();
    });

    it('allows all actions when user has manage-permissions permission', function () {
        $policy = new PermissionPolicy;
        $permission = Permission::create(['name' => 'test-permission']);
        $managePermission = Permission::create(['name' => 'manage-permissions']);

        $this->user->givePermissionTo($managePermission);

        expect($policy->viewAny($this->user))->toBeTrue()
            ->and($policy->view($this->user, $permission))->toBeTrue()
            ->and($policy->create($this->user))->toBeTrue()
            ->and($policy->update($this->user, $permission))->toBeTrue()
            ->and($policy->delete($this->user, $permission))->toBeTrue()
            ->and($policy->restore($this->user, $permission))->toBeTrue()
            ->and($policy->forceDelete($this->user, $permission))->toBeTrue();
    });

    it('allows all actions for super admin', function () {
        $policy = new PermissionPolicy;
        $permission = Permission::create(['name' => 'test-permission']);
        $superAdminRole = Role::create(['name' => 'super-admin']);

        $this->user->assignRole($superAdminRole);

        expect($policy->viewAny($this->user))->toBeTrue()
            ->and($policy->view($this->user, $permission))->toBeTrue()
            ->and($policy->create($this->user))->toBeTrue()
            ->and($policy->update($this->user, $permission))->toBeTrue()
            ->and($policy->delete($this->user, $permission))->toBeTrue()
            ->and($policy->restore($this->user, $permission))->toBeTrue()
            ->and($policy->forceDelete($this->user, $permission))->toBeTrue();
    });

    it('allows attach and detach roles with proper permissions', function () {
        $policy = new PermissionPolicy;
        $permission = Permission::create(['name' => 'test-permission']);
        $role = Role::create(['name' => 'test-role']);

        // Should deny without permissions
        expect($policy->attachRole($this->user, $permission, $role))->toBeFalse()
            ->and($policy->detachRole($this->user, $permission, $role))->toBeFalse();

        // Should allow with specific permissions
        $assignPermission = Permission::create(['name' => 'assign-roles']);
        $revokePermission = Permission::create(['name' => 'revoke-roles']);

        $this->user->givePermissionTo($assignPermission);
        expect($policy->attachRole($this->user, $permission, $role))->toBeTrue();

        $this->user->givePermissionTo($revokePermission);
        expect($policy->detachRole($this->user, $permission, $role))->toBeTrue();
    });
});
