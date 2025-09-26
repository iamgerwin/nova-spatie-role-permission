<?php

declare(strict_types=1);

// Prevent error handler conflicts with Laravel 12 and PHPUnit
if (class_exists(\Illuminate\Foundation\Bootstrap\HandleExceptions::class)) {
    \Illuminate\Foundation\Bootstrap\HandleExceptions::flushState();
}

require __DIR__.'/../vendor/autoload.php';
