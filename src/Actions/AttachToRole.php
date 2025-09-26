<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class AttachToRole extends Action
{
    use InteractsWithQueue;
    use Queueable;

    public $name = 'Attach To Role';

    public function handle(ActionFields $fields, Collection $models): mixed
    {
        $roleClass = config('permission.models.role');
        $role = $roleClass::find($fields->role_id);

        if (! $role) {
            return Action::danger(__('nova-spatie-role-permission::actions.role_not_found'));
        }

        foreach ($models as $permission) {
            $role->givePermissionTo($permission);
        }

        return Action::message(__('nova-spatie-role-permission::actions.permissions_attached', [
            'count' => $models->count(),
            'role' => $role->name,
        ]));
    }

    public function fields(NovaRequest $request): array
    {
        $roleClass = config('permission.models.role');
        $roles = $roleClass::pluck('name', 'id')->toArray();

        return [
            Select::make(__('nova-spatie-role-permission::resources.role'), 'role_id')
                ->options($roles)
                ->rules('required'),
        ];
    }
}