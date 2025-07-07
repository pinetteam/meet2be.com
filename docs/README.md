# Meet2Be Backend Documentation

## Table of Contents

### Architecture & Design
- [Backend Architecture Overview](backend/ARCHITECTURE.md)
- [Directory Structure](backend/DIRECTORY_STRUCTURE.md)
- [Database Design](backend/DATABASE_DESIGN.md)
- [API Design Standards](backend/API_STANDARDS.md)

### Core Concepts
- [Authentication & Authorization](backend/AUTHENTICATION.md)
- [Multi-tenancy Architecture](backend/MULTI_TENANCY.md)
- [DateTime & Timezone Management](backend/DATETIME_TIMEZONE.md)
- [UUID Implementation](backend/UUID_IMPLEMENTATION.md)

### Development Guide
- [Models & Relationships](backend/MODELS_RELATIONSHIPS.md)
- [Services Layer](backend/SERVICES_LAYER.md)
- [Traits & Concerns](backend/TRAITS_CONCERNS.md)
- [Middleware](backend/MIDDLEWARE.md)
- [Request Validation](backend/REQUEST_VALIDATION.md)

### Best Practices
- [Coding Standards](backend/CODING_STANDARDS.md)
- [Testing Strategy](backend/TESTING_STRATEGY.md)
- [Performance Optimization](backend/PERFORMANCE.md)
- [Security Guidelines](backend/SECURITY.md)

### Deployment & Operations
- [Environment Configuration](backend/ENVIRONMENT.md)
- [Queue & Jobs](backend/QUEUES_JOBS.md)
- [Caching Strategy](backend/CACHING.md)
- [Monitoring & Logging](backend/MONITORING.md)

## Quick Start

1. Clone the repository
2. Copy `.env.example` to `.env`
3. Run `composer install`
4. Run `php artisan key:generate`
5. Configure your database in `.env`
6. Run `php artisan migrate --seed`
7. Run `php artisan serve`

## Technology Stack

- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Database**: MySQL 8.0+ / PostgreSQL 15+
- **Cache**: Redis
- **Queue**: Redis
- **Frontend**: Tailwind CSS + Alpine.js
- **Build Tool**: Vite

## Key Features

- Multi-tenant architecture
- UUID-based primary keys
- Global DateTime/Timezone handling
- Role-based access control
- RESTful API design
- Comprehensive test coverage
- Performance optimized queries
- Security-first approach 