<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Tests\Unit;

use Iamgerwin\NovaSpatieRolePermission\Nova\Permission;
use Iamgerwin\NovaSpatieRolePermission\Nova\Role;
use Iamgerwin\NovaSpatieRolePermission\Tests\TestCase;

class ResourceModelInitializationTest extends TestCase
{
    /** @test */
    public function it_initializes_role_model_property()
    {
        $this->assertNotNull(Role::$model);
        $this->assertEquals(\Spatie\Permission\Models\Role::class, Role::$model);
    }

    /** @test */
    public function it_initializes_permission_model_property()
    {
        $this->assertNotNull(Permission::$model);
        $this->assertEquals(\Spatie\Permission\Models\Permission::class, Permission::$model);
    }

    /** @test */
    public function role_model_method_returns_configured_model()
    {
        // Create instance to test non-static model() method
        $roleResource = new Role(new \Spatie\Permission\Models\Role);

        // Test with default configuration
        $this->assertEquals(\Spatie\Permission\Models\Role::class, $roleResource->model());

        // Test with custom configuration
        config(['permission.models.role' => 'App\Models\CustomRole']);
        $this->assertEquals('App\Models\CustomRole', $roleResource->model());
    }

    /** @test */
    public function permission_model_method_returns_configured_model()
    {
        // Create instance to test non-static model() method
        $permissionResource = new Permission(new \Spatie\Permission\Models\Permission);

        // Test with default configuration
        $this->assertEquals(\Spatie\Permission\Models\Permission::class, $permissionResource->model());

        // Test with custom configuration
        config(['permission.models.permission' => 'App\Models\CustomPermission']);
        $this->assertEquals('App\Models\CustomPermission', $permissionResource->model());
    }

    /** @test */
    public function role_get_model_returns_fallback_when_needed()
    {
        $this->assertEquals(\Spatie\Permission\Models\Role::class, Role::getModel());
    }

    /** @test */
    public function permission_get_model_returns_fallback_when_needed()
    {
        $this->assertEquals(\Spatie\Permission\Models\Permission::class, Permission::getModel());
    }
}
