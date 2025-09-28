<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Tests\Unit;

use Iamgerwin\NovaSpatieRolePermission\Nova\Permission;
use Iamgerwin\NovaSpatieRolePermission\Nova\Role;
use Iamgerwin\NovaSpatieRolePermission\Tests\TestCase;

class ResourceModelInitializationTest extends TestCase
{
    public function test_it_initializes_role_model_property()
    {
        $this->assertNotNull(Role::$model);
        $this->assertEquals(\Spatie\Permission\Models\Role::class, Role::$model);
    }

    public function test_it_initializes_permission_model_property()
    {
        $this->assertNotNull(Permission::$model);
        $this->assertEquals(\Spatie\Permission\Models\Permission::class, Permission::$model);
    }

    public function test_role_model_method_returns_model_instance()
    {
        // Create a role model instance
        $roleModel = new \Spatie\Permission\Models\Role;
        $roleResource = new Role($roleModel);

        // Test that model() returns the resource instance
        $this->assertSame($roleModel, $roleResource->model());
        $this->assertInstanceOf(\Spatie\Permission\Models\Role::class, $roleResource->model());
    }

    public function test_permission_model_method_returns_model_instance()
    {
        // Create a permission model instance
        $permissionModel = new \Spatie\Permission\Models\Permission;
        $permissionResource = new Permission($permissionModel);

        // Test that model() returns the resource instance
        $this->assertSame($permissionModel, $permissionResource->model());
        $this->assertInstanceOf(\Spatie\Permission\Models\Permission::class, $permissionResource->model());
    }

    public function test_role_get_model_returns_fallback_when_needed()
    {
        $this->assertEquals(\Spatie\Permission\Models\Role::class, Role::getModel());
    }

    public function test_permission_get_model_returns_fallback_when_needed()
    {
        $this->assertEquals(\Spatie\Permission\Models\Permission::class, Permission::getModel());
    }

    public function test_role_new_model_creates_instance_with_default_config()
    {
        $model = Role::newModel();

        $this->assertInstanceOf(\Spatie\Permission\Models\Role::class, $model);
        $this->assertTrue($model->exists === false);
    }

    public function test_permission_new_model_creates_instance_with_default_config()
    {
        $model = Permission::newModel();

        $this->assertInstanceOf(\Spatie\Permission\Models\Permission::class, $model);
        $this->assertTrue($model->exists === false);
    }

    public function test_role_new_model_uses_custom_model_from_config()
    {
        // Create a mock class for testing
        eval('class CustomTestRole extends \Spatie\Permission\Models\Role {}');

        config(['permission.models.role' => 'CustomTestRole']);

        $model = Role::newModel();

        $this->assertInstanceOf('CustomTestRole', $model);
    }

    public function test_permission_new_model_uses_custom_model_from_config()
    {
        // Create a mock class for testing
        eval('class CustomTestPermission extends \Spatie\Permission\Models\Permission {}');

        config(['permission.models.permission' => 'CustomTestPermission']);

        $model = Permission::newModel();

        $this->assertInstanceOf('CustomTestPermission', $model);
    }

    public function test_role_new_model_throws_exception_for_invalid_class()
    {
        config(['permission.models.role' => 'NonExistentClass']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Role model class [NonExistentClass] not found');

        Role::newModel();
    }

    public function test_permission_new_model_throws_exception_for_invalid_class()
    {
        config(['permission.models.permission' => 'NonExistentClass']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Permission model class [NonExistentClass] not found');

        Permission::newModel();
    }
}
