<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(Authenticatable $user): bool
    {
        return true;
    }

    public function view(Authenticatable $user, $permission): bool
    {
        return true;
    }

    public function create(Authenticatable $user): bool
    {
        return true;
    }

    public function update(Authenticatable $user, $permission): bool
    {
        return true;
    }

    public function delete(Authenticatable $user, $permission): bool
    {
        return true;
    }

    public function restore(Authenticatable $user, $permission): bool
    {
        return true;
    }

    public function forceDelete(Authenticatable $user, $permission): bool
    {
        return true;
    }

    public function attachRole(Authenticatable $user, $permission, $role): bool
    {
        return true;
    }

    public function detachRole(Authenticatable $user, $permission, $role): bool
    {
        return true;
    }
}