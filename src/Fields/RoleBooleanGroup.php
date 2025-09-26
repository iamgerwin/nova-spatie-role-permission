<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Fields;

use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Http\Requests\NovaRequest;

class RoleBooleanGroup extends BooleanGroup
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

        $roleClass = config('permission.models.role');
        $roles = $roleClass::whereIn('id', $values)->get();

        $model->syncRoles($roles);
    }
}