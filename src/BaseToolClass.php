<?php

declare(strict_types=1);

namespace Iamgerwin\NovaSpatieRolePermission;

// Conditionally extend Nova Tool if available, otherwise create a base class
if (class_exists(\Laravel\Nova\Tool::class)) {
    abstract class BaseToolClass extends \Laravel\Nova\Tool
    {
    }
} else {
    abstract class BaseToolClass
    {
        public function boot(): void
        {
            // Default implementation when Nova is not available
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
}