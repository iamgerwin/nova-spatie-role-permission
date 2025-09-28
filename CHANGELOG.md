# Changelog

All notable changes to `nova-spatie-role-permission` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.6] - 2025-09-29

### Fixed
- **CRITICAL**: Fixed `model()` method returning string instead of model instance causing authorization failures
- The `model()` method now correctly returns `$this->resource` instead of the configuration string
- Fixed "Return value must be of type Model|Resource, string returned" error in Nova's authorization system
- Added proper error handling in `newModel()` method with helpful error messages

### Added
- `newModel()` static method override for proper custom model instantiation
- Class existence validation in `newModel()` with descriptive error messages
- Comprehensive tests for model instance returns and custom model support
- Tests for error handling when invalid model classes are configured

### Changed
- `model()` method now returns the actual resource instance instead of configuration string
- Improved PHPDoc annotations to reflect correct return types
- Enhanced test suite to validate model method behavior properly

## [1.1.5] - 2025-09-29

### Fixed
- **CRITICAL**: Fixed PHP Fatal error "Cannot make non static method static" by correcting `model()` method signature
- Changed `model()` method from static to non-static in Role and Permission resources to match parent class
- Resolved application boot failure caused by incorrect method override
- Updated tests to properly test non-static model() method

### Changed
- `model()` method in Nova resources now correctly overrides parent method signature
- Improved compatibility with Laravel Nova's Resource base class

## [1.1.4] - 2025-09-29

### Fixed
- Critical: Initialize `$model` property in Nova resources to prevent "Class name must be a valid object or a string" error
- Add missing Log facade import in ToolServiceProvider
- Ensure Nova resources work properly with both default and custom model configurations

### Added
- `model()` method override in Role and Permission resources for better configuration support
- Comprehensive tests for model initialization
- PHPDoc annotations for better IDE support

### Changed
- Role and Permission resources now initialize with default Spatie models
- Improved model resolution with proper fallback mechanisms

## [1.1.3] - 2025-09-29

### Added
- Automatic configuration merging with default values when config file is not published
- Installation verification command (`php artisan nova-permission:verify`) for diagnosing setup issues
- Comprehensive troubleshooting section in README
- Graceful error handling with helpful messages when configuration is missing

### Fixed
- "Class name must be a valid object or a string" error when configuration file is not published
- Package now works with default configuration without requiring manual publishing
- Improved error messages to guide users through setup issues

### Changed
- Enhanced ToolServiceProvider to merge default configuration automatically
- Updated installation instructions with clear step-by-step process
- Added fallback values for Nova resource configurations
- Improved documentation with troubleshooting guide and common solutions

## [1.1.2] - 2025-09-29

### Fixed
- Fixed `authorizedToCreate` method signature in Role and Permission Nova resources to be properly static
- Resolved "Cannot make static method non static" error when extending Laravel\Nova\Resource

## [1.1.1] - 2024-09-28

### Added
- Installation command (`php artisan nova-permission:install`) for easy setup
- Configuration file (`config/nova-permission.php`) for package customization
- Default permissions seeding with installation command
- Super admin role creation with all permissions
- Option to assign super admin role during installation

### Fixed
- Fixed test suite error handler issues causing risky test warnings in CI
- Removed problematic error handler management from TestCase
- Resolved permission lockout issue when no users have permissions

### Changed
- Enhanced installation process with interactive prompts
- Configuration can now be published separately
- Improved documentation with Quick Setup section

## [1.1.0] - 2024-09-27

### Breaking Changes
- Policies now check for actual permissions instead of always returning true
- Authorization methods are now properly static where required

### Added
- Proper permission-based authorization in RolePolicy and PermissionPolicy
- Super admin role support with bypass authorization
- Null-safe user handling in all authorization methods
- `before` method in policies for super admin bypass
- `authorizedToReplicate` method in Nova resources (returns false by default)

### Fixed
- Fixed authorization method signatures to match Nova's requirements
- `authorizedToCreate` is now properly static in Nova resources
- Added null-safe operators to prevent "Call to a member function on null" errors
- Added proper Request type hints where required

### Changed
- Enhanced all policy methods to check for specific permissions
- Policies now support granular permissions like `view-roles`, `create-permissions`, etc.
- Added fallback to broader permissions like `manage-roles` and `manage-permissions`
- Improved error handling with null coalescence operators

### Security
- Implemented proper authorization checks instead of permissive defaults
- Added role-based access control with super admin support

## [1.0.14] - 2024-09-27

### Fixed
- Override refreshApplication method to handle vendor error handler issues
- Wrap HandleExceptions::flushState in try-catch to prevent failures
- Remove problematic error handler restoration from Pest configuration

### Changed
- Add error reporting suppression during application refresh
- Simplify Pest.php configuration to avoid conflicts

## [1.0.13] - 2024-09-27

### Fixed
- Remove HandleExceptions::flushState() calls that cause errors in Laravel 12
- Fix error handler compatibility issues in test suite
- Simplify test bootstrap to avoid conflicts

### Changed
- Remove HandleExceptions usage from TestCase
- Update test bootstrap to set environment variable instead
- Add error handler restoration in Pest configuration

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
