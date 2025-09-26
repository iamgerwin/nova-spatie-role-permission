<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any roles.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $user->hasPermissionTo('view-roles') ||
               $user->hasPermissionTo('manage-roles') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can view the role.
     */
    public function view(Authenticatable $user, $role): bool
    {
        return $user->hasPermissionTo('view-roles') ||
               $user->hasPermissionTo('manage-roles') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can create roles.
     */
    public function create(Authenticatable $user): bool
    {
        return $user->hasPermissionTo('create-roles') ||
               $user->hasPermissionTo('manage-roles') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can update the role.
     */
    public function update(Authenticatable $user, $role): bool
    {
        return $user->hasPermissionTo('edit-roles') ||
               $user->hasPermissionTo('manage-roles') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can delete the role.
     */
    public function delete(Authenticatable $user, $role): bool
    {
        return $user->hasPermissionTo('delete-roles') ||
               $user->hasPermissionTo('manage-roles') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can restore the role.
     */
    public function restore(Authenticatable $user, $role): bool
    {
        return $user->hasPermissionTo('restore-roles') ||
               $user->hasPermissionTo('manage-roles') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can permanently delete the role.
     */
    public function forceDelete(Authenticatable $user, $role): bool
    {
        return $user->hasPermissionTo('force-delete-roles') ||
               $user->hasPermissionTo('manage-roles') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can attach permissions to the role.
     */
    public function attachPermission(Authenticatable $user, $role, $permission): bool
    {
        return $user->hasPermissionTo('assign-permissions') ||
               $user->hasPermissionTo('manage-roles') ||
               $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can detach permissions from the role.
     */
    public function detachPermission(Authenticatable $user, $role, $permission): bool
    {
        return $user->hasPermissionTo('revoke-permissions') ||
               $user->hasPermissionTo('manage-roles') ||
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