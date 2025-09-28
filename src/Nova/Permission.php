<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission\Nova;

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
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Spatie\Permission\Models\Permission>
     */
    public static $model = \Spatie\Permission\Models\Permission::class;

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
        'guard_name',
    ];

    public static $globallySearchable = true;

    /**
     * Get the model class for the resource.
     *
     * @return class-string<\Spatie\Permission\Models\Permission>
     */
    public static function getModel(): string
    {
        return static::$model ?? config('permission.models.permission', \Spatie\Permission\Models\Permission::class);
    }

    /**
     * Get the underlying model instance for the resource.
     *
     * @return \Spatie\Permission\Models\Permission|\Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return $this->resource;
    }

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return \Spatie\Permission\Models\Permission|\Illuminate\Database\Eloquent\Model
     */
    public static function newModel()
    {
        $model = config('permission.models.permission', static::$model);

        if (! class_exists($model)) {
            throw new \Exception("Permission model class [{$model}] not found. Please check your permission.models.permission configuration.");
        }

        return new $model;
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

    public function authorizedToCreate($request): bool
    {
        return $request->user()?->can('create', static::getModel()) ?? false;
    }

    public function authorizedToUpdate($request): bool
    {
        return $request->user()?->can('update', $this->resource) ?? false;
    }

    public function authorizedToDelete($request): bool
    {
        return $request->user()?->can('delete', $this->resource) ?? false;
    }

    public function authorizedToView($request): bool
    {
        return $request->user()?->can('view', $this->resource) ?? false;
    }

    public function authorizedToReplicate($request): bool
    {
        return false;
    }
}
