<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Nova;

use Iamgerwin\NovaSpatieRolePermission\Fields\PermissionBooleanGroup;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Role extends Resource
{
    public static $model;

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
        'guard_name',
    ];

    public static $globallySearchable = true;

    public static function getModel(): string
    {
        return static::$model ?? config('permission.models.role');
    }

    public static function label(): string
    {
        return __('nova-spatie-role-permission::resources.roles');
    }

    public static function singularLabel(): string
    {
        return __('nova-spatie-role-permission::resources.role');
    }

    public function fields(NovaRequest $request): array
    {
        $guardOptions = collect(config('auth.guards', []))
            ->mapWithKeys(fn ($value, $key) => [$key => $key])
            ->toArray();

        $userResource = Nova::resourceForModel(config('auth.providers.users.model'));

        return [
            ID::make(__('ID'), 'id')
                ->sortable(),

            Text::make(__('nova-spatie-role-permission::roles.name'), 'name')
                ->rules(['required', 'string', 'max:255'])
                ->creationRules('unique:'.config('permission.table_names.roles', 'roles'))
                ->updateRules('unique:'.config('permission.table_names.roles', 'roles').',name,{{resourceId}}'),

            Select::make(__('nova-spatie-role-permission::roles.guard_name'), 'guard_name')
                ->options($guardOptions)
                ->rules(['required', 'string'])
                ->default(config('auth.defaults.guard')),

            DateTime::make(__('nova-spatie-role-permission::roles.created_at'), 'created_at')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make(__('nova-spatie-role-permission::roles.updated_at'), 'updated_at')
                ->exceptOnForms()
                ->sortable(),

            PermissionBooleanGroup::make(__('nova-spatie-role-permission::roles.permissions'), 'permissions')
                ->resolveUsing(function ($permissions, $role) {
                    return $permissions->pluck('id')->toArray();
                })
                ->options(
                    app(config('permission.models.permission'))
                        ->pluck('name', 'id')
                        ->map(fn ($permission) => ucfirst($permission))
                        ->toArray()
                )
                ->hideFromIndex(),

            MorphToMany::make(__('nova-spatie-role-permission::roles.permissions'), 'permissions', Permission::class)
                ->searchable()
                ->singularLabel(__('nova-spatie-role-permission::resources.permission'))
                ->onlyOnDetail(),

            $userResource ? MorphToMany::make($userResource::label(), 'users', $userResource)
                ->searchable()
                ->singularLabel($userResource::singularLabel())
                ->onlyOnDetail() : null,
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with('permissions');
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return auth()->user()->can('create', static::getModel());
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return auth()->user()->can('update', $this->resource);
    }

    public function authorizedToDelete(Request $request): bool
    {
        return auth()->user()->can('delete', $this->resource);
    }

    public function authorizedToView(Request $request): bool
    {
        return auth()->user()->can('view', $this->resource);
    }
}
