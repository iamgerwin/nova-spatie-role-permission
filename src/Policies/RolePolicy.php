<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Check if any authorization should be bypassed.
     * Super admins bypass all authorization.
     */
    public function before(Authenticatable $user, $ability): ?bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine if the user can view any roles.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $this->hasAnyPermission($user, ['view-roles', 'manage-roles']);
    }

    /**
     * Determine if the user can view the role.
     */
    public function view(Authenticatable $user, $role): bool
    {
        return $this->hasAnyPermission($user, ['view-roles', 'manage-roles']);
    }

    /**
     * Determine if the user can create roles.
     */
    public function create(Authenticatable $user): bool
    {
        return $this->hasAnyPermission($user, ['create-roles', 'manage-roles']);
    }

    /**
     * Determine if the user can update the role.
     */
    public function update(Authenticatable $user, $role): bool
    {
        return $this->hasAnyPermission($user, ['edit-roles', 'manage-roles']);
    }

    /**
     * Determine if the user can delete the role.
     */
    public function delete(Authenticatable $user, $role): bool
    {
        return $this->hasAnyPermission($user, ['delete-roles', 'manage-roles']);
    }

    /**
     * Determine if the user can restore the role.
     */
    public function restore(Authenticatable $user, $role): bool
    {
        return $this->hasAnyPermission($user, ['restore-roles', 'manage-roles']);
    }

    /**
     * Determine if the user can permanently delete the role.
     */
    public function forceDelete(Authenticatable $user, $role): bool
    {
        return $this->hasAnyPermission($user, ['force-delete-roles', 'manage-roles']);
    }

    /**
     * Determine if the user can attach permissions to the role.
     */
    public function attachPermission(Authenticatable $user, $role, $permission): bool
    {
        return $this->hasAnyPermission($user, ['assign-permissions', 'manage-roles']);
    }

    /**
     * Determine if the user can detach permissions from the role.
     */
    public function detachPermission(Authenticatable $user, $role, $permission): bool
    {
        return $this->hasAnyPermission($user, ['revoke-permissions', 'manage-roles']);
    }

    /**
     * Check if user has any of the given permissions.
     * Safely handles cases where permissions don't exist.
     */
    protected function hasAnyPermission(Authenticatable $user, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            try {
                if ($user->hasPermissionTo($permission)) {
                    return true;
                }
            } catch (PermissionDoesNotExist $e) {
                // Permission doesn't exist, continue checking others
                continue;
            }
        }

        return false;
    }
}