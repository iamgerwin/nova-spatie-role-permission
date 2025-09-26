<?php

declare(strict_types=1);

namespace Laravel\Nova;

// Mock Nova classes for testing
class Nova
{
    protected static array $tools = [];
    protected static array $resources = [];

    public static function tools(array $tools = null)
    {
        if ($tools !== null) {
            static::$tools = $tools;
        }
        return static::$tools;
    }

    public static function registeredTools(): array
    {
        return static::$tools;
    }

    public static function resources(array $resources): void
    {
        static::$resources = array_merge(static::$resources, $resources);
    }

    public static function registeredResources(): array
    {
        return static::$resources;
    }

    public static function resourceForModel($model)
    {
        return null;
    }

    public static function router($middleware, $name)
    {
        return new class {
            public function group($file) {
                return $this;
            }
        };
    }

    public static function serving($callback): void
    {
        // Mock implementation
    }

    public static function provideToScript(array $data): void
    {
        // Mock implementation
    }
}

class Tool
{
    public function boot(): void
    {
        // Default implementation
    }

    public function menu($request)
    {
        return null;
    }

    public function authorize($request): bool
    {
        return true;
    }
}

class Resource
{
    public static $model;
    public static $title = 'id';
    public static $search = ['id'];

    public function authorizedToCreate($request): bool
    {
        return true;
    }

    public function authorizedToUpdate($request): bool
    {
        return true;
    }

    public function authorizedToDelete($request): bool
    {
        return true;
    }

    public function authorizedToView($request): bool
    {
        return true;
    }

    public static function label(): string
    {
        return 'Resource';
    }

    public static function singularLabel(): string
    {
        return 'Resource';
    }
}

namespace Laravel\Nova\Fields;

class Field
{
    public $attribute;
    protected $name;

    public function __construct($name, $attribute = null)
    {
        $this->name = $name;
        $this->attribute = $attribute ?? str_replace(' ', '_', strtolower($name));
    }

    public static function make(...$args)
    {
        return new static(...$args);
    }

    public function __call($method, $args)
    {
        return $this;
    }
}

class ID extends Field {}
class Text extends Field {}
class Select extends Field {}
class DateTime extends Field {}
class MorphToMany extends Field {}
class BooleanGroup extends Field {}

namespace Laravel\Nova\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NovaRequest extends FormRequest {}

namespace Laravel\Nova\Actions;

class Action
{
    public static function danger($message)
    {
        return ['danger' => $message];
    }

    public static function message($message)
    {
        return ['message' => $message];
    }
}

class ActionFields
{
    protected array $fields = [];

    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    public function __get($name)
    {
        return $this->fields[$name] ?? null;
    }
}

namespace Laravel\Nova\Menu;

class MenuItem
{
    public static function resource($resource)
    {
        return new static();
    }

    public function __call($method, $args)
    {
        return $this;
    }
}

class MenuSection
{
    public static function make($title, $items = [])
    {
        return new static();
    }

    public function __call($method, $args)
    {
        return $this;
    }
}

namespace Laravel\Nova\Events;

class ServingNova {}