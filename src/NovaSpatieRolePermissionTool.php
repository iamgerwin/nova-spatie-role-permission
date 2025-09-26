<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission;

use Iamgerwin\NovaSpatieRolePermission\Nova\Permission;
use Iamgerwin\NovaSpatieRolePermission\Nova\Role;
use Iamgerwin\NovaSpatieRolePermission\Policies\PermissionPolicy;
use Iamgerwin\NovaSpatieRolePermission\Policies\RolePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaSpatieRolePermissionTool extends Tool
{
    protected ?string $roleResource = null;

    protected ?string $permissionResource = null;

    protected ?string $rolePolicy = null;

    protected ?string $permissionPolicy = null;

    protected ?string $permissionGuard = null;

    protected ?string $roleGuard = null;

    public function boot(): void
    {
        Nova::resources([
            $this->getRoleResource(),
            $this->getPermissionResource(),
        ]);

        $roleModel = $this->getRoleModel();
        $permissionModel = $this->getPermissionModel();

        Gate::policy($roleModel, $this->getRolePolicy());
        Gate::policy($permissionModel, $this->getPermissionPolicy());
    }

    public function menu(Request $request): mixed
    {
        return MenuSection::make(__('nova-spatie-role-permission::navigation.sidebar-label'), [
            MenuItem::resource($this->getRoleResource()),
            MenuItem::resource($this->getPermissionResource()),
        ])->icon('shield-check')->collapsable();
    }

    public function roleResource(string $roleResource): self
    {
        $this->roleResource = $roleResource;

        return $this;
    }

    public function permissionResource(string $permissionResource): self
    {
        $this->permissionResource = $permissionResource;

        return $this;
    }

    public function rolePolicy(string $rolePolicy): self
    {
        $this->rolePolicy = $rolePolicy;

        return $this;
    }

    public function permissionPolicy(string $permissionPolicy): self
    {
        $this->permissionPolicy = $permissionPolicy;

        return $this;
    }

    public function permissionGuard(string $permissionGuard): self
    {
        $this->permissionGuard = $permissionGuard;

        return $this;
    }

    public function roleGuard(string $roleGuard): self
    {
        $this->roleGuard = $roleGuard;

        return $this;
    }

    public function getRoleResource(): string
    {
        return $this->roleResource ?? Role::class;
    }

    public function getPermissionResource(): string
    {
        return $this->permissionResource ?? Permission::class;
    }

    public function getRolePolicy(): string
    {
        return $this->rolePolicy ?? RolePolicy::class;
    }

    public function getPermissionPolicy(): string
    {
        return $this->permissionPolicy ?? PermissionPolicy::class;
    }

    public function getPermissionGuard(): ?string
    {
        return $this->permissionGuard;
    }

    public function getRoleGuard(): ?string
    {
        return $this->roleGuard;
    }

    public function getRoleModel(): string
    {
        $roleResource = $this->getRoleResource();

        return $roleResource::$model ?? config('permission.models.role');
    }

    public function getPermissionModel(): string
    {
        $permissionResource = $this->getPermissionResource();

        return $permissionResource::$model ?? config('permission.models.permission');
    }
}
