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
    it('allows all actions by default', function () {
        $policy = new RolePolicy;
        $role = Role::create(['name' => 'admin']);

        expect($policy->viewAny($this->user))->toBeTrue()
            ->and($policy->view($this->user, $role))->toBeTrue()
            ->and($policy->create($this->user))->toBeTrue()
            ->and($policy->update($this->user, $role))->toBeTrue()
            ->and($policy->delete($this->user, $role))->toBeTrue()
            ->and($policy->restore($this->user, $role))->toBeTrue()
            ->and($policy->forceDelete($this->user, $role))->toBeTrue();
    });

    it('allows attach and detach permissions', function () {
        $policy = new RolePolicy;
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'edit posts']);

        expect($policy->attachPermission($this->user, $role, $permission))->toBeTrue()
            ->and($policy->detachPermission($this->user, $role, $permission))->toBeTrue();
    });
});

describe('PermissionPolicy', function () {
    it('allows all actions by default', function () {
        $policy = new PermissionPolicy;
        $permission = Permission::create(['name' => 'edit posts']);

        expect($policy->viewAny($this->user))->toBeTrue()
            ->and($policy->view($this->user, $permission))->toBeTrue()
            ->and($policy->create($this->user))->toBeTrue()
            ->and($policy->update($this->user, $permission))->toBeTrue()
            ->and($policy->delete($this->user, $permission))->toBeTrue()
            ->and($policy->restore($this->user, $permission))->toBeTrue()
            ->and($policy->forceDelete($this->user, $permission))->toBeTrue();
    });

    it('allows attach and detach roles', function () {
        $policy = new PermissionPolicy;
        $permission = Permission::create(['name' => 'edit posts']);
        $role = Role::create(['name' => 'admin']);

        expect($policy->attachRole($this->user, $permission, $role))->toBeTrue()
            ->and($policy->detachRole($this->user, $permission, $role))->toBeTrue();
    });
});
