# AI Coding Agent Instructions for OMSV2

## Project Overview
OMSV2 (Order Management System Version 2) is a Laravel 12 + Filament 4 admin panel application with role-based multi-panel architecture. It manages orders, products, materials, stocks, and encashments with Spatie permissions and passkey authentication.

## Architecture & Key Components

### Multi-Panel Structure
The app uses **4 separate Filament panels**, each with role-based access control via `canAccessPanel()` in the User model:
- **mukhiya** - Super admin panel (full system control)
- **maker** - Order creation and fulfillment
- **taker** - Order intake and management  
- **packer** - Packaging and dispatch operations

Each panel has its own `PanelProvider` in `app/Providers/Filament/` with distinct navigation groups, themes, and plugins. Panel switching is handled by `BezhanSalleh\PanelSwitch`.

### Authorization Pattern
Uses **Spatie Permissions** (config in `config/permission.php`). All Policies follow consistent naming:
```php
// app/Policies/*.php - Use permission strings like "ViewAny:Model", "Create:Model"
public function view(AuthUser $authUser): bool
{
    return $authUser->can('View:User');
}
```
This grants granular control via Filament Shield role/permission UI.

### Data Models
Located in `app/Models/`:
- **User** - Implements `FilamentUser`, `HasPasskeys`, `HasAvatar`, `MustVerifyEmail`, `TwoFactorAuthenticatable`
- **Order** - Has many OrderItems, belongs to User
- **OrderItem** - Line items within orders, relates to Product/Material
- **Product/Material** - Core inventory items
- **Stock** - Tracks inventory balance per material
- **Encashment** - Financial records
- **Category/Company** - Reference data

### Filament Resource Structure
Resources in `app/Filament/Resources/` follow a **schema-based pattern**:
```
Users/
  UserResource.php (main resource definition)
  Pages/
    CreateUser.php, EditUser.php, ListUsers.php
  Schemas/
    UserForm.php (form schema definition)
  Tables/
    UsersTable.php (table schema definition)
```
Forms and tables are extracted into separate schema classes for reusability and clarity.

## Development Workflows

### Setup & Running
```bash
# Full setup with migrations
composer run setup

# Development with hot reload (Laravel serve, queue listener, logs, Vite)
composer run dev

# Tests
composer run test
```

The `dev` script uses `concurrently` to run 4 processes: Laravel server, queue listener, pail logs, and Vite development server.

### Database
- Migrations in `database/migrations/` (date-prefixed files)
- Multiple core models with relationships (Order → OrderItem, Product ↔ Category, etc.)
- Recent addition: Two-factor authentication, passkeys, custom user fields

### Testing
Tests in `tests/Feature/` and `tests/Unit/`. Run with `composer run test` which clears config cache first.

## Project-Specific Patterns & Conventions

### Filament Plugin Stack
The mukhiya panel demonstrates plugin usage:
- `FilamentShieldPlugin` - Role/permission management UI
- `FilamentEditProfilePlugin` - User profile customization
- `FilamentEditEnvPlugin` - Environment variable editor
- `PasskeysPlugin` - Passkey authentication
- `TwoFactorAuthenticationPlugin` - 2FA
- `FilamentApexChartsPlugin` - Dashboard analytics
- `FileManagerPlugin` - File system navigation
- `FilamentUiSwitcherPlugin` - UI theme switching
- `StickyTableHeaderPlugin` - Better table UX
- `FilamentNotificationSoundPlugin` - Audio notifications

When creating new panels, include relevant plugins via `PanelProvider::plugins()`.

### User Model Traits
The User model composes multiple concerns:
- `HasRoles` from Spatie - for role assignment
- `InteractsWithPasskeys` - passkey management
- `TwoFactorAuthenticatable` - 2FA support
- `HasUiPreferences` - UI theme/preference storage (as JSON)
- `HasApiTokens` - Sanctum API tokens

When extending User, ensure traits don't conflict and check `casts()` for JSON columns.

### Policies & Authorization
All models have corresponding policies. When creating new resources:
1. Generate policy: `php artisan make:policy ModelPolicy --model=Model`
2. Implement methods using `$authUser->can('Action:Model')` pattern
3. Policies are automatically discovered and used by Filament Shield

### Navigation Groups
Each panel defines `navigationGroups()` at the panel level (e.g., 'Stock Management', 'Order Management'). Resources specify their group via `protected static string|null $navigationGroup`.

### Migrations
Follow Laravel conventions. Recent patterns include:
- Using `$table->morphs()` for polymorphic relationships
- Adding JSON columns for flexible data (`ui_preferences`, `custom_fields`)
- Datetime columns with `->useCurrent()` and `->useCurrentOnUpdate()`

## Critical Integration Points

### Filament ↔ Database
- Resources auto-discover Models and generate CRUD operations
- Forms use schema classes (`UserForm::configure()`) to prevent duplication
- Tables use schema classes (`UsersTable::configure()`) with sortable/searchable columns
- Policies gate all actions automatically

### Authentication & Panels
- User must have a role to access a panel
- `canAccessPanel()` checks role and returns boolean
- Two-factor and passkey auth are required during login
- Sanctum tokens available for API consumption

### Email Verification
- User model implements `MustVerifyEmail`
- Panels include `.emailVerification()` middleware
- Affects login flow - unverified users may face restrictions

## Common Tasks

### Adding a New Resource
1. Create model: `php artisan make:model ModelName -m` (with migration)
2. Create policy: `php artisan make:policy ModelPolicy --model=ModelName`
3. Generate resource: `php artisan make:filament-resource ModelName`
4. Extract form/table to schema classes in `Resources/ModelName/Schemas/` and `Tables/`
5. Register policy in `AuthServiceProvider`
6. Add to one or more panels via resource discovery

### Adding a Role/Permission
1. Use Filament Shield UI (only available in mukhiya panel for super_admin)
2. Or seed via `database/seeders/`: Create permission, attach to role
3. Update policy methods to check new permission
4. Clear permission cache: `php artisan permission:cache-clear`

### Creating a New Panel
1. Create `PanelProvider` extending `PanelProvider` in `app/Providers/Filament/`
2. Define panel ID, path, resources, pages, navigation groups, plugins
3. Update `canAccessPanel()` in User model for new panel
4. Register provider in bootstrap/providers.php or auto-discover

## Dependencies & External Libraries

### Key Packages
- `laravel/filament` v4 - Admin panel framework
- `spatie/laravel-permission` - Role-based authorization
- `spatie/laravel-passkeys` - Passwordless authentication
- `stephenjude/filament-two-factor-authentication` - 2FA
- `marcelweidum/filament-passkeys` - Filament UI for passkeys
- `mwguerra/filemanager` - File system management

### Validation
Filament handles validation through form schema rules. Use Illuminate\Validation\Rules for complex validation.

## Code Quality & Maintenance

### Standards
- PHP 8.2+ required
- Follows PSR-4 autoloading
- Uses type declarations throughout
- Models use strict typing for relationships

### Linting
Laravel Pint installed (`composer run-script lint` if available, or `./vendor/bin/pint`).

### Asset Compilation
- Vite for JS/CSS bundling
- Tailwind CSS v4 for styling
- Panel-specific theme CSS: `resources/css/filament/{panel}/theme.css`
