# Changelog

All notable changes to `nova-spatie-role-permission` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.12] - 2024-09-27

### Fixed
- Resolve persistent PHPUnit error handler conflicts with Laravel 12
- Fix HandleExceptions::flushState() error in test suite
- Add custom test bootstrap file for proper initialization

### Changed
- Move error handler cleanup to tearDown method only
- Update PHPUnit bootstrap configuration to use custom bootstrap file
- Simplify TestBench version constraint in composer.json

### Added
- Custom test bootstrap.php file for handling error state management

## [1.0.11] - 2024-09-27

### Fixed
- Fix PHPUnit error handler compatibility with Laravel 12
- Resolve test failures related to error handler state management
- Update PHPUnit configuration for better compatibility

### Changed
- Disable failOnRisky and beStrictAboutOutputDuringTests in PHPUnit config
- Add proper error handler cleanup in TestCase setUp and tearDown methods

## [1.0.10] - 2024-09-27

### Fixed
- Remove type hints from authorization method parameters in Nova resources
- Ensure full compatibility with parent Nova Resource class signatures
- Remove unused Request import from Role and Permission resources

### Changed
- All authorization methods now use untyped $request parameter to match parent class

## [1.0.9] - 2024-09-27

### Fixed
- Fix authorizedToCreate method signature in Role and Permission resources
- Changed static method to instance method to match Laravel Nova Resource interface
- Resolved test failures caused by incompatible method overrides

### Changed
- authorizedToCreate is now an instance method in both Role and Permission Nova resources

## [1.0.8] - 2024-09-27

### Fixed
- Fix menu method signature compatibility with Laravel Nova Tool base class
- Remove type hints to match parent class method signature
- Apply code styling fixes from CI pipeline

### Changed
- Menu method no longer uses typed parameters for Nova compatibility
- Removed unused Illuminate\Http\Request import

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
