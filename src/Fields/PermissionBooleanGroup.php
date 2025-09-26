<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Fields;

use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Http\Requests\NovaRequest;

class PermissionBooleanGroup extends BooleanGroup
{
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        if (! $request->exists($requestAttribute)) {
            return;
        }

        $values = collect(json_decode($request[$requestAttribute], true))
            ->filter(fn ($value) => $value === true)
            ->keys()
            ->toArray();

        $permissionClass = config('permission.models.permission');
        $permissions = $permissionClass::whereIn('id', $values)->get();

        $model->syncPermissions($permissions);
    }
}
