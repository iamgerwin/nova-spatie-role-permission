<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any permissions.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $user->hasPermissionTo('view-permissions') ||
               $user->hasPermissionTo('manage-permissions') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can view the permission.
     */
    public function view(Authenticatable $user, $permission): bool
    {
        return $user->hasPermissionTo('view-permissions') ||
               $user->hasPermissionTo('manage-permissions') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can create permissions.
     */
    public function create(Authenticatable $user): bool
    {
        return $user->hasPermissionTo('create-permissions') ||
               $user->hasPermissionTo('manage-permissions') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can update the permission.
     */
    public function update(Authenticatable $user, $permission): bool
    {
        return $user->hasPermissionTo('edit-permissions') ||
               $user->hasPermissionTo('manage-permissions') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can delete the permission.
     */
    public function delete(Authenticatable $user, $permission): bool
    {
        return $user->hasPermissionTo('delete-permissions') ||
               $user->hasPermissionTo('manage-permissions') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can restore the permission.
     */
    public function restore(Authenticatable $user, $permission): bool
    {
        return $user->hasPermissionTo('restore-permissions') ||
               $user->hasPermissionTo('manage-permissions') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can permanently delete the permission.
     */
    public function forceDelete(Authenticatable $user, $permission): bool
    {
        return $user->hasPermissionTo('force-delete-permissions') ||
               $user->hasPermissionTo('manage-permissions') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can attach roles to the permission.
     */
    public function attachRole(Authenticatable $user, $permission, $role): bool
    {
        return $user->hasPermissionTo('assign-roles') ||
               $user->hasPermissionTo('manage-permissions') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can detach roles from the permission.
     */
    public function detachRole(Authenticatable $user, $permission, $role): bool
    {
        return $user->hasPermissionTo('revoke-roles') ||
               $user->hasPermissionTo('manage-permissions') ||
               $user->hasRole('super-admin');
    }

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
}