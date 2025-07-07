# Directory Structure

## Overview

Our directory structure follows Laravel conventions while implementing domain-driven design principles. Each directory has a specific purpose and follows strict naming conventions.

## Root Directory Structure

```
meet2be.com/
├── app/                    # Application core logic
├── bootstrap/              # Framework bootstrap files
├── config/                 # Configuration files
├── database/               # Database migrations, factories, seeders
├── docs/                   # Project documentation
├── lang/                   # Localization files
├── public/                 # Web server document root
├── resources/              # Views, raw assets, language files
├── routes/                 # Route definitions
├── storage/                # Logs, cache, uploads
├── tests/                  # Test files
├── vendor/                 # Composer dependencies
├── .env.example            # Environment template
├── artisan                 # CLI entry point
├── composer.json           # PHP dependencies
├── package.json            # NPM dependencies
├── phpunit.xml             # Test configuration
├── tailwind.config.js      # Tailwind CSS configuration
└── vite.config.js          # Vite build configuration
```

## App Directory Structure

### Controllers (`app/Http/Controllers/`)

Controllers are organized by access area and domain:

```
Controllers/
├── Controller.php          # Base controller
├── Portal/                 # Authenticated portal area
│   ├── Dashboard/
│   │   └── DashboardController.php
│   ├── User/
│   │   └── UserController.php
│   ├── Setting/
│   │   └── SettingController.php
│   └── Profile/
│       └── ProfileController.php
└── Site/                   # Public site area
    ├── Auth/
    │   ├── LoginController.php
    │   └── LogoutController.php
    └── HomeController.php
```

**Naming Conventions:**
- Controllers use singular names: `UserController`, not `UsersController`
- Nested in domain folders: `Portal/User/UserController`
- Each controller handles one resource
- Methods follow REST conventions: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`

### Models (`app/Models/`)

Models are organized by business domain:

```
Models/
├── Event/                  # Event domain
│   ├── Event.php
│   └── Venue/
│       └── Venue.php
├── System/                 # System configuration models
│   ├── Country.php
│   ├── Currency.php
│   ├── Language.php
│   └── Timezone.php
├── Tenant/                 # Multi-tenancy models
│   └── Tenant.php
└── User/                   # User domain
    └── User.php
```

**Key Principles:**
- Each model in its own domain folder
- Related models can be nested (e.g., `Event/Venue/Venue.php`)
- Models use singular names
- All models use UUID primary keys

### Services (`app/Services/`)

Business logic is encapsulated in service classes:

```
Services/
├── DateTime/               # DateTime handling service
│   ├── DateTimeManager.php
│   ├── DateTimeFormatter.php
│   └── TenantDateTime.php
└── TenantService.php       # Tenant management
```

**Service Guidelines:**
- Services contain business logic
- Keep controllers thin
- Services can be injected into controllers
- Name services by their responsibility

### Traits (`app/Traits/`)

Reusable model behaviors:

```
Traits/
├── HasDateTime.php         # DateTime formatting trait
├── HasTimezone.php         # Timezone conversion trait
└── TenantAware.php         # Multi-tenancy trait
```

**Trait Usage:**
- Traits provide reusable functionality
- Prefix with `Has` or verb for clarity
- Document trait requirements

### Middleware (`app/Http/Middleware/`)

HTTP middleware for request filtering:

```
Middleware/
├── Authenticate.php        # Authentication check
└── EnsureTenantContext.php # Tenant context validation
```

### Requests (`app/Http/Requests/`)

Form request validation classes:

```
Requests/
├── Portal/
│   ├── User/
│   │   ├── StoreUserRequest.php
│   │   └── UpdateUserRequest.php
│   └── Profile/
│       └── UpdateProfileRequest.php
└── Site/
    └── Auth/
        └── LoginRequest.php
```

**Request Naming:**
- Prefix with action: `Store`, `Update`, `Delete`
- Suffix with `Request`
- Mirror controller structure

### Observers (`app/Observers/`)

Model event observers:

```
Observers/
└── User/
    └── UserObserver.php
```

## Database Directory

### Migrations (`database/migrations/`)

Database schema definitions:

```
migrations/
├── 2024_01_01_000100_create_system_sessions_table.php
├── 2024_01_01_000700_create_system_countries_table.php
├── 2024_01_02_001000_create_tenants_table.php
├── 2024_01_03_002000_create_users_table.php
└── 2024_01_04_000100_create_events_table.php
```

**Migration Naming:**
- Date prefix: `YYYY_MM_DD_HHMMSS`
- Descriptive action: `create_`, `add_`, `remove_`
- Table name in plural
- System tables prefixed with `system_`

### Factories (`database/factories/`)

Model factories for testing:

```
factories/
├── Event/
│   ├── EventFactory.php
│   └── Venue/
│       └── VenueFactory.php
├── Tenant/
│   └── TenantFactory.php
└── User/
    └── UserFactory.php
```

### Seeders (`database/seeders/`)

Database seeding classes:

```
seeders/
├── DatabaseSeeder.php      # Main seeder orchestrator
├── System/                 # System data seeders
│   ├── CountriesSeeder.php
│   ├── CurrenciesSeeder.php
│   ├── LanguagesSeeder.php
│   └── TimezonesSeeder.php
├── Tenant/
│   └── TenantsSeeder.php
└── User/
    └── UsersSeeder.php
```

## Resources Directory

### Views (`resources/views/`)

Blade template files:

```
views/
├── layouts/                # Layout templates
│   ├── portal.blade.php
│   └── site.blade.php
├── portal/                 # Portal area views
│   ├── dashboard/
│   │   └── index.blade.php
│   ├── user/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── show.blade.php
│   │   └── edit.blade.php
│   └── setting/
│       └── index.blade.php
└── site/                   # Public site views
    ├── auth/
    │   └── login.blade.php
    └── home/
        └── index.blade.php
```

**View Conventions:**
- Mirror controller structure
- Use folders for resource grouping
- Standard CRUD views: `index`, `create`, `show`, `edit`

### JavaScript (`resources/js/`)

Frontend JavaScript files:

```
js/
├── portal/                 # Portal-specific JS
│   └── portal.js
├── site/                   # Site-specific JS
│   └── site.js
└── services/               # Shared services
    └── datetime.js
```

### CSS (`resources/css/`)

Stylesheet files:

```
css/
├── portal/
│   └── portal.css
└── site/
    └── site.css
```

## Routes Directory

Route definition files:

```
routes/
├── console.php             # Artisan commands
├── portal.php              # Portal routes (authenticated)
└── site.php                # Site routes (public)
```

**Route Organization:**
- Separate files by access area
- Group related routes
- Use resource routes when possible
- Name all routes

## Language Directory

Localization files:

```
lang/
├── en/                     # English translations
│   ├── auth.php
│   ├── common.php
│   ├── user.php
│   └── validation.php
└── tr/                     # Turkish translations
    ├── auth.php
    ├── common.php
    ├── user.php
    └── validation.php
```

**Translation Structure:**
- Organize by feature/domain
- Use nested arrays for organization
- Keep common translations in `common.php`

## Config Directory

Configuration files:

```
config/
├── app.php                 # Application configuration
├── auth.php                # Authentication configuration
├── cache.php               # Cache configuration
├── database.php            # Database configuration
├── filesystems.php         # File storage configuration
├── logging.php             # Logging configuration
├── mail.php                # Mail configuration
├── queue.php               # Queue configuration
└── services.php            # Third-party services
```

## Tests Directory

Test files mirror app structure:

```
tests/
├── Feature/                # Feature tests
│   ├── Auth/
│   │   └── LoginTest.php
│   └── Portal/
│       └── UserManagementTest.php
├── Unit/                   # Unit tests
│   ├── Models/
│   │   └── UserTest.php
│   └── Services/
│       └── DateTimeManagerTest.php
├── Pest.php                # Pest configuration
└── TestCase.php            # Base test class
```

## Storage Directory

Application storage (git-ignored):

```
storage/
├── app/                    # Application files
│   ├── public/            # Public file storage
│   └── private/           # Private file storage
├── framework/              # Framework files
│   ├── cache/             # Cache files
│   ├── sessions/          # Session files
│   ├── testing/           # Testing files
│   └── views/             # Compiled views
└── logs/                   # Application logs
    └── laravel.log
```

## Public Directory

Web-accessible files:

```
public/
├── index.php               # Application entry point
├── favicon.ico
├── robots.txt
├── build/                  # Compiled assets (git-ignored)
└── assets/                 # Static assets
    └── images/
        └── flags/          # Country flag images
```

## Best Practices

### File Naming
- Use PascalCase for classes: `UserController.php`
- Use kebab-case for views: `create-user.blade.php`
- Use snake_case for database: `user_profiles`
- Use camelCase for JavaScript: `userProfile.js`

### Organization
- Keep related files together
- Follow domain boundaries
- Maintain consistent structure
- Document non-obvious decisions

### Dependencies
- Declare all dependencies explicitly
- Use dependency injection
- Avoid tight coupling
- Favor composition over inheritance 