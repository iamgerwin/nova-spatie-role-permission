<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Nova;

use Illuminate\Http\Request;
use Iamgerwin\NovaSpatieRolePermission\Fields\RoleBooleanGroup;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;

class Permission extends Resource
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
        return static::$model ?? config('permission.models.permission');
    }

    public static function label(): string
    {
        return __('nova-spatie-role-permission::resources.permissions');
    }

    public static function singularLabel(): string
    {
        return __('nova-spatie-role-permission::resources.permission');
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

            Text::make(__('nova-spatie-role-permission::permissions.name'), 'name')
                ->rules(['required', 'string', 'max:255'])
                ->creationRules('unique:'.config('permission.table_names.permissions', 'permissions'))
                ->updateRules('unique:'.config('permission.table_names.permissions', 'permissions').',name,{{resourceId}}'),

            Text::make(__('nova-spatie-role-permission::permissions.display_name'), 'display_name')
                ->nullable()
                ->hideFromIndex(),

            Select::make(__('nova-spatie-role-permission::permissions.guard_name'), 'guard_name')
                ->options($guardOptions)
                ->rules(['required', 'string'])
                ->default(config('auth.defaults.guard')),

            DateTime::make(__('nova-spatie-role-permission::permissions.created_at'), 'created_at')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make(__('nova-spatie-role-permission::permissions.updated_at'), 'updated_at')
                ->exceptOnForms()
                ->sortable(),

            RoleBooleanGroup::make(__('nova-spatie-role-permission::permissions.roles'), 'roles')
                ->resolveUsing(function ($roles, $permission) {
                    return $roles->pluck('id')->toArray();
                })
                ->options(
                    app(config('permission.models.role'))
                        ->pluck('name', 'id')
                        ->map(fn ($role) => ucfirst($role))
                        ->toArray()
                )
                ->hideFromIndex(),

            MorphToMany::make(__('nova-spatie-role-permission::permissions.roles'), 'roles', Role::class)
                ->searchable()
                ->singularLabel(__('nova-spatie-role-permission::resources.role'))
                ->onlyOnDetail(),

            $userResource ? MorphToMany::make($userResource::label(), 'users', $userResource)
                ->searchable()
                ->singularLabel($userResource::singularLabel())
                ->onlyOnDetail() : null,
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with('roles');
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
        return [
            new \Iamgerwin\NovaSpatieRolePermission\Actions\AttachToRole,
        ];
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