# Changelog

All notable changes to `nova-spatie-role-permission` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.7] - 2024-09-27

### Fixed
- Fix test migration setup by creating tables directly in TestCase
- Resolve CreatePermissionTables class not found error
- Ensure test suite runs properly without migration files

### Changed
- TestCase now creates permission tables programmatically
- Removed dependency on migration class for test setup

## [1.0.6] - 2024-09-27

### Fixed
- Exclude all Nova-dependent files from PHPStan analysis
- Fix test suite migration loading in TestCase
- Resolve ApplicationNotAvailableException in tests
- Complete PHPStan configuration for CI environment

### Changed
- Test setup now properly initializes database migrations
- PHPStan excludes tool and service provider files

## [1.0.5] - 2024-09-27

### Fixed
- Exclude Nova-dependent files from PHPStan analysis
- Prevent static analysis errors when Nova is not installed
- Add excludePaths configuration for files requiring Nova classes

### Changed
- PHPStan now skips analysis of Nova resources, fields, and actions

## [1.0.4] - 2024-09-27

### Fixed
- Fix PHPStan configuration regex patterns for proper escaping
- Simplify ignoreErrors patterns to prevent compilation failures
- Add reportUnmatchedIgnoredErrors option for flexibility
- Disable checkModelProperties to avoid false positives

## [1.0.3] - 2024-09-27

### Fixed
- Fix ToolServiceProvider to conditionally load Nova features
- Prevent class not found errors when Nova is not installed
- Add BaseToolClass to handle Nova Tool inheritance gracefully
- Fix package discovery during composer autoload

### Changed
- Service provider now checks for Nova existence before registering features
- Tool class conditionally extends Nova Tool or base class

## [1.0.2] - 2024-09-27

### Fixed
- Fix test suite to run without Nova package installed
- Add Nova stub classes for testing
- Update TestCase configuration for proper migrations
- Fix CI/CD pipeline test failures

### Added
- Nova mock classes for testing environment

## [1.0.1] - 2024-09-27

### Fixed
- Remove Nova from composer requirements (Nova is private package)
- Fix GitHub Actions workflows for CI/CD
- Update PHPStan configuration to handle Nova classes
- Improve documentation about Nova installation

### Changed
- Nova is now treated as a peer dependency
- Updated PHP version in workflows to 8.3

## [1.0.0] - 2024-09-27

### Added
- Initial release
- Full Laravel Nova 5 compatibility
- Support for Laravel 11 and 12
- PHP 8.3 compatibility
- Complete role and permission management interface
- Custom field components (RoleBooleanGroup, PermissionBooleanGroup, RoleSelect)
- Automatic permission cache management
- Bulk action to attach permissions to roles
- Customizable resources and policies
- Multi-guard support
- Comprehensive test coverage with Pest
- Full documentation and examples
