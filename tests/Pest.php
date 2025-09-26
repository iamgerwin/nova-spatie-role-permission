<?php

use Iamgerwin\NovaSpatieRolePermission\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

// Configure Pest to handle error handlers properly
uses()->beforeEach(function () {
    // Restore error handlers if needed
    restore_error_handler();
    restore_exception_handler();
})->in(__DIR__);
