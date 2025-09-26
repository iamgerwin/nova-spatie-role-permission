<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(Authenticatable $user): bool
    {
        return true;
    }

    public function view(Authenticatable $user, $role): bool
    {
        return true;
    }

    public function create(Authenticatable $user): bool
    {
        return true;
    }

    public function update(Authenticatable $user, $role): bool
    {
        return true;
    }

    public function delete(Authenticatable $user, $role): bool
    {
        return true;
    }

    public function restore(Authenticatable $user, $role): bool
    {
        return true;
    }

    public function forceDelete(Authenticatable $user, $role): bool
    {
        return true;
    }

    public function attachPermission(Authenticatable $user, $role, $permission): bool
    {
        return true;
    }

    public function detachPermission(Authenticatable $user, $role, $permission): bool
    {
        return true;
    }
}