<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Fields;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class RoleSelect extends Select
{
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        if (! $request->exists($requestAttribute)) {
            return;
        }

        $roleId = $request[$requestAttribute];

        if (! $roleId) {
            $model->syncRoles([]);
            return;
        }

        $roleClass = config('permission.models.role');
        $role = $roleClass::find($roleId);

        if ($role) {
            $model->syncRoles([$role]);
        }
    }

    public function resolveForDisplay($resource, $attribute = null)
    {
        $roles = $resource->roles;

        if ($roles->isEmpty()) {
            return null;
        }

        return $roles->first()->id;
    }
}