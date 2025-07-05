# Meet2Be - Event Management System

A modern event management system built with Laravel 12, FluxUI Pro, Alpine.js and Tailwind CSS.

## Features

- Multi-tenant architecture
- Event management with venues
- User management (admin, screener, operator)
- Internationalization (Turkish/English)
- Modern UI with FluxUI Pro components
- Real-time features with Alpine.js

## Requirements

- PHP 8.2+
- MySQL 8.0+ or PostgreSQL 15+
- Node.js 18+
- Composer 2.x
- Redis (optional)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/pinetteam/meet2be.com.git
cd meet2be.com
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file

7. Run migrations and seeders:
```bash
php artisan migrate --seed
```

8. Build assets:
```bash
npm run build
```

9. Start the development server:
```bash
php artisan serve
```

## FluxUI Pro License

This project uses FluxUI Pro. You need a valid license to use it. Add your license credentials to `auth.json`:

```json
{
    "http-basic": {
        "composer.fluxui.dev": {
            "username": "your-email@example.com",
            "password": "your-license-key"
        }
    }
}
```

## Technology Stack

- **Backend**: Laravel 12
- **Frontend**: FluxUI Pro, Alpine.js, Tailwind CSS 4.0
- **Database**: MySQL/PostgreSQL
- **Icons**: FontAwesome Pro
- **Build Tool**: Vite

## Directory Structure

```
meet2be.com/
├── app/                # Application core
│   ├── Http/          # Controllers, Requests, Resources
│   ├── Models/        # Eloquent models
│   └── Services/      # Business logic
├── database/          # Migrations, seeders, factories
├── lang/              # Language files (tr, en)
├── resources/         # Views, CSS, JavaScript
│   ├── css/          # Tailwind CSS files
│   ├── js/           # Alpine.js components
│   └── views/        # Blade templates
└── routes/           # Application routes
```

## Development Standards

- Strict adherence to Laravel conventions
- UUID primary keys (no auto-increment)
- Hierarchical namespace structure
- Self-documenting code (no comments)
- DRY/KISS principles
- FluxUI Pro components for UI

## License

This project is proprietary software. All rights reserved.
