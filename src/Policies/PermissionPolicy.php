<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Check if any authorization should be bypassed.
     * Super admins bypass all authorization.
     */
    public function before(Authenticatable $user, $ability): ?bool
    {
        try {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        } catch (\Exception $e) {
            // Role doesn't exist or other error, continue with normal checks
        }

        return null;
    }

    /**
     * Determine if the user can view any permissions.
     */
    public function viewAny(Authenticatable $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->hasAnyPermission($user, ['view-permissions', 'manage-permissions']);
    }

    /**
     * Determine if the user can view the permission.
     */
    public function view(Authenticatable $user, $permission): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->hasAnyPermission($user, ['view-permissions', 'manage-permissions']);
    }

    /**
     * Determine if the user can create permissions.
     */
    public function create(Authenticatable $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->hasAnyPermission($user, ['create-permissions', 'manage-permissions']);
    }

    /**
     * Determine if the user can update the permission.
     */
    public function update(Authenticatable $user, $permission): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->hasAnyPermission($user, ['edit-permissions', 'manage-permissions']);
    }

    /**
     * Determine if the user can delete the permission.
     */
    public function delete(Authenticatable $user, $permission): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->hasAnyPermission($user, ['delete-permissions', 'manage-permissions']);
    }

    /**
     * Determine if the user can restore the permission.
     */
    public function restore(Authenticatable $user, $permission): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->hasAnyPermission($user, ['restore-permissions', 'manage-permissions']);
    }

    /**
     * Determine if the user can permanently delete the permission.
     */
    public function forceDelete(Authenticatable $user, $permission): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->hasAnyPermission($user, ['force-delete-permissions', 'manage-permissions']);
    }

    /**
     * Determine if the user can attach roles to the permission.
     */
    public function attachRole(Authenticatable $user, $permission, $role): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->hasAnyPermission($user, ['assign-roles', 'manage-permissions']);
    }

    /**
     * Determine if the user can detach roles from the permission.
     */
    public function detachRole(Authenticatable $user, $permission, $role): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->hasAnyPermission($user, ['revoke-roles', 'manage-permissions']);
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

    /**
     * Check if user is a super admin.
     * Safely handles cases where the role doesn't exist.
     */
    protected function isSuperAdmin(Authenticatable $user): bool
    {
        try {
            return $user->hasRole('super-admin');
        } catch (\Exception $e) {
            return false;
        }
    }
}
