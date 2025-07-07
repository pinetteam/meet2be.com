# Backend Documentation

This directory contains comprehensive documentation for the Meet2Be backend architecture and implementation.

## Table of Contents

### Core Architecture
- [Architecture Overview](ARCHITECTURE.md) - System design, principles, and patterns
- [Directory Structure](DIRECTORY_STRUCTURE.md) - Project organization and file conventions
- [Multi-tenancy Architecture](MULTI_TENANCY.md) - Row-level isolation implementation
- [Database Design](DATABASE_DESIGN.md) - Schema design, relationships, and optimization

### Development Guidelines
- [Coding Standards](CODING_STANDARDS.md) - Laravel conventions and best practices
- [UUID Implementation](UUID_IMPLEMENTATION.md) - UUID v7 usage throughout the system
- [Models & Relationships](MODELS_RELATIONSHIPS.md) - Eloquent patterns and conventions
- [Services Layer](SERVICES_LAYER.md) - Business logic encapsulation
- [Traits & Concerns](TRAITS_CONCERNS.md) - Reusable functionality patterns

### Features & Components
- [DateTime & Timezone Management](DATETIME_TIMEZONE.md) - Tenant-aware date/time handling
- [Authentication & Authorization](AUTHENTICATION.md) - Security implementation
- [Request Validation](REQUEST_VALIDATION.md) - FormRequest patterns and rules
- [API Design Standards](API_STANDARDS.md) - RESTful API implementation
- [Middleware](MIDDLEWARE.md) - HTTP request/response filtering

### Infrastructure
- [Environment Configuration](ENVIRONMENT.md) - Environment variables and settings
- [Queue & Jobs](QUEUES_JOBS.md) - Asynchronous task processing
- [Caching Strategy](CACHING.md) - Redis caching patterns
- [Monitoring & Logging](MONITORING.md) - System observability

### Quality & Security
- [Testing Strategy](TESTING_STRATEGY.md) - Pest PHP testing approach
- [Security Guidelines](SECURITY.md) - Security best practices
- [Performance Optimization](PERFORMANCE.md) - Speed and efficiency techniques

## Quick Start

For new developers joining the project:

1. Start with [Architecture Overview](ARCHITECTURE.md) to understand the system
2. Review [Directory Structure](DIRECTORY_STRUCTURE.md) to navigate the codebase
3. Read [Coding Standards](CODING_STANDARDS.md) before writing code
4. Check [Multi-tenancy Architecture](MULTI_TENANCY.md) for tenant isolation
5. Study [Models & Relationships](MODELS_RELATIONSHIPS.md) for data layer

## Key Concepts

### Technology Stack
- **Framework**: Laravel 12
- **PHP Version**: 8.4+
- **Database**: MySQL 8.0+ / PostgreSQL 15+
- **Cache**: Redis
- **Queue**: Redis
- **Frontend**: Tailwind CSS + Alpine.js

### Architecture Principles
- Domain-Driven Design (DDD)
- Repository Pattern with Services
- Row-level Multi-tenancy
- UUID v7 for all primary keys
- Comprehensive test coverage

### Development Workflow
1. All models use UUID v7 as primary key
2. All controllers follow Laravel resource conventions
3. Business logic resides in service classes
4. Validation happens in FormRequest classes
5. API responses use Resource classes
6. All features must have tests

## Documentation Standards

When updating documentation:
- Keep examples practical and runnable
- Include both good and bad examples
- Update the table of contents
- Maintain consistent formatting
- Test all code examples
- Keep security considerations in mind

## Contributing

When adding new features or components:
1. Update relevant documentation
2. Add examples to show usage
3. Document any new patterns introduced
4. Update this README if adding new docs
5. Ensure consistency with existing patterns

## Support

For questions or clarifications:
- Check existing documentation first
- Review code examples in the codebase
- Consult team leads for architectural decisions
- Document new patterns for future reference 