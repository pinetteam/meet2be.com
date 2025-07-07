# Database Design

## Overview

Meet2Be uses a relational database design with UUID primary keys, multi-tenant row-level isolation, and comprehensive indexing for optimal performance. The database is designed to be scalable, maintainable, and secure.

## Design Principles

### 1. UUID Primary Keys
All tables use UUID v7 as primary keys for:
- Global uniqueness across distributed systems
- Security through non-sequential IDs
- Easy data migration and replication

### 2. Multi-Tenant Architecture
- Shared database with row-level isolation
- Every tenant-scoped table has `tenant_id` column
- Composite indexes on `tenant_id` + frequently queried columns

### 3. Soft Deletes
- Critical data is never hard deleted
- `deleted_at` timestamp column for soft deletes
- Maintains data integrity and audit trail

### 4. Audit Trail
- `created_at` and `updated_at` on all tables
- Additional audit tables for sensitive operations

## System Tables

### system_countries
Stores country information for global use.

```sql
CREATE TABLE system_countries (
    id CHAR(36) PRIMARY KEY,
    code CHAR(2) UNIQUE NOT NULL,
    code3 CHAR(3) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    native_name VARCHAR(100),
    capital VARCHAR(100),
    region VARCHAR(100),
    subregion VARCHAR(100),
    phone_code VARCHAR(10),
    currency_code CHAR(3),
    flag_emoji VARCHAR(10),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_code (code),
    INDEX idx_is_active (is_active)
);
```

### system_languages
Stores available languages.

```sql
CREATE TABLE system_languages (
    id CHAR(36) PRIMARY KEY,
    code VARCHAR(10) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    native_name VARCHAR(100),
    direction ENUM('ltr', 'rtl') DEFAULT 'ltr',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_code (code),
    INDEX idx_is_active (is_active)
);
```

### system_currencies
Stores currency information.

```sql
CREATE TABLE system_currencies (
    id CHAR(36) PRIMARY KEY,
    code CHAR(3) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    symbol VARCHAR(10),
    decimal_places TINYINT DEFAULT 2,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_code (code),
    INDEX idx_is_active (is_active)
);
```

### system_timezones
Stores timezone information.

```sql
CREATE TABLE system_timezones (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    offset VARCHAR(10) NOT NULL,
    offset_minutes INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_name (name),
    INDEX idx_is_active (is_active)
);
```

### system_country_language (Pivot)
Many-to-many relationship between countries and languages.

```sql
CREATE TABLE system_country_language (
    country_id CHAR(36) NOT NULL,
    language_id CHAR(36) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    
    PRIMARY KEY (country_id, language_id),
    FOREIGN KEY (country_id) REFERENCES system_countries(id),
    FOREIGN KEY (language_id) REFERENCES system_languages(id)
);
```

## Tenant Tables

### tenants
Core tenant information.

```sql
CREATE TABLE tenants (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('personal', 'team', 'enterprise') DEFAULT 'personal',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    owner_id CHAR(36) NOT NULL,
    
    -- Settings
    timezone VARCHAR(50) DEFAULT 'UTC',
    locale VARCHAR(10) DEFAULT 'en',
    date_format VARCHAR(50) DEFAULT 'Y-m-d',
    time_format VARCHAR(50) DEFAULT 'H:i',
    currency_code CHAR(3) DEFAULT 'USD',
    country_code CHAR(2),
    
    -- Metadata
    settings JSON,
    metadata JSON,
    
    -- Flags
    is_active BOOLEAN DEFAULT TRUE,
    
    -- Timestamps
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    -- Indexes
    INDEX idx_code (code),
    INDEX idx_status (status),
    INDEX idx_owner_id (owner_id),
    INDEX idx_is_active (is_active),
    INDEX idx_deleted_at (deleted_at),
    
    -- Foreign Keys
    FOREIGN KEY (owner_id) REFERENCES users(id),
    FOREIGN KEY (currency_code) REFERENCES system_currencies(code),
    FOREIGN KEY (country_code) REFERENCES system_countries(code)
);
```

## User Tables

### users
User account information.

```sql
CREATE TABLE users (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    avatar VARCHAR(500),
    
    -- Preferences
    timezone VARCHAR(50),
    locale VARCHAR(10),
    
    -- Security
    remember_token VARCHAR(100),
    two_factor_secret TEXT,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    two_factor_recovery_codes TEXT,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    locked_at TIMESTAMP NULL,
    locked_until TIMESTAMP NULL,
    failed_login_attempts INT DEFAULT 0,
    last_failed_login_at TIMESTAMP NULL,
    
    -- Activity
    last_login_at TIMESTAMP NULL,
    last_activity_at TIMESTAMP NULL,
    
    -- Timestamps
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    -- Indexes
    UNIQUE INDEX idx_tenant_email (tenant_id, email),
    INDEX idx_email (email),
    INDEX idx_tenant_id (tenant_id),
    INDEX idx_is_active (is_active),
    INDEX idx_deleted_at (deleted_at),
    
    -- Foreign Keys
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);
```

### user_password_reset_tokens
Password reset tokens.

```sql
CREATE TABLE user_password_reset_tokens (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    
    PRIMARY KEY (email),
    INDEX idx_token (token)
);
```

## Event Tables

### events
Main event information.

```sql
CREATE TABLE events (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    venue_id CHAR(36),
    
    -- Basic Info
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    
    -- Timing
    starts_at TIMESTAMP NOT NULL,
    ends_at TIMESTAMP NOT NULL,
    timezone VARCHAR(50) NOT NULL,
    
    -- Status
    status ENUM('draft', 'published', 'cancelled') DEFAULT 'draft',
    visibility ENUM('public', 'private', 'unlisted') DEFAULT 'public',
    
    -- Capacity
    max_attendees INT,
    current_attendees INT DEFAULT 0,
    
    -- Settings
    settings JSON,
    metadata JSON,
    
    -- Timestamps
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    -- Indexes
    UNIQUE INDEX idx_tenant_slug (tenant_id, slug),
    INDEX idx_tenant_id (tenant_id),
    INDEX idx_venue_id (venue_id),
    INDEX idx_starts_at (starts_at),
    INDEX idx_ends_at (ends_at),
    INDEX idx_status (status),
    INDEX idx_tenant_status (tenant_id, status),
    INDEX idx_tenant_starts (tenant_id, starts_at),
    INDEX idx_deleted_at (deleted_at),
    
    -- Foreign Keys
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (venue_id) REFERENCES event_venues(id)
);
```

### event_venues
Event venue information.

```sql
CREATE TABLE event_venues (
    id CHAR(36) PRIMARY KEY,
    tenant_id CHAR(36) NOT NULL,
    
    -- Basic Info
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    type ENUM('physical', 'virtual', 'hybrid') DEFAULT 'physical',
    
    -- Location
    address_line_1 VARCHAR(255),
    address_line_2 VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    postal_code VARCHAR(20),
    country_code CHAR(2),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    
    -- Virtual Info
    virtual_url VARCHAR(500),
    virtual_platform VARCHAR(100),
    
    -- Details
    capacity INT,
    description TEXT,
    amenities JSON,
    
    -- Contact
    contact_name VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(50),
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    
    -- Timestamps
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    -- Indexes
    UNIQUE INDEX idx_tenant_slug (tenant_id, slug),
    INDEX idx_tenant_id (tenant_id),
    INDEX idx_type (type),
    INDEX idx_country_code (country_code),
    INDEX idx_is_active (is_active),
    INDEX idx_deleted_at (deleted_at),
    
    -- Foreign Keys
    FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    FOREIGN KEY (country_code) REFERENCES system_countries(code)
);
```

## Session & Cache Tables

### sessions
User session storage.

```sql
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id CHAR(36) NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INT NOT NULL,
    
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
);
```

### cache
Application cache storage.

```sql
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL,
    
    INDEX idx_expiration (expiration)
);
```

### cache_locks
Cache lock management.

```sql
CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL,
    
    INDEX idx_expiration (expiration)
);
```

## Job Queue Tables

### jobs
Queue job storage.

```sql
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    
    INDEX idx_queue_reserved (queue, reserved_at)
);
```

### failed_jobs
Failed job tracking.

```sql
CREATE TABLE failed_jobs (
    id CHAR(36) PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_uuid (uuid),
    INDEX idx_failed_at (failed_at)
);
```

## Indexing Strategy

### Primary Indexes
- All primary keys are automatically indexed
- UUID columns use CHAR(36) for consistent storage

### Composite Indexes
```sql
-- Tenant + Common Query Patterns
INDEX idx_tenant_status (tenant_id, status)
INDEX idx_tenant_created (tenant_id, created_at)
INDEX idx_tenant_type (tenant_id, type)

-- Performance Optimization
INDEX idx_tenant_deleted (tenant_id, deleted_at)
```

### Full-Text Indexes
```sql
-- For search functionality
ALTER TABLE events ADD FULLTEXT(title, description);
ALTER TABLE event_venues ADD FULLTEXT(name, description);
```

## Query Optimization

### Common Query Patterns

1. **Tenant-Scoped Queries**
```sql
-- Always filter by tenant first
SELECT * FROM events 
WHERE tenant_id = ? 
AND status = 'published' 
AND starts_at >= NOW()
ORDER BY starts_at ASC;
```

2. **Date Range Queries**
```sql
-- Use indexed date columns
SELECT * FROM events
WHERE tenant_id = ?
AND starts_at BETWEEN ? AND ?
AND deleted_at IS NULL;
```

3. **Pagination Queries**
```sql
-- Use LIMIT with OFFSET
SELECT * FROM users
WHERE tenant_id = ?
AND is_active = TRUE
ORDER BY created_at DESC
LIMIT 20 OFFSET 40;
```

## Migration Best Practices

### Migration Naming Convention
```
YYYY_MM_DD_HHMMSS_descriptive_action_name.php
```

### Migration Structure
```php
public function up()
{
    Schema::create('table_name', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('tenant_id');
        
        // Columns
        
        $table->timestamps();
        $table->softDeletes();
        
        // Indexes
        $table->index('tenant_id');
        
        // Foreign Keys
        $table->foreign('tenant_id')->references('id')->on('tenants');
    });
}

public function down()
{
    Schema::dropIfExists('table_name');
}
```

## Performance Considerations

### 1. Index Usage
- Always include tenant_id in composite indexes
- Monitor slow query log for missing indexes
- Use EXPLAIN to verify index usage

### 2. Query Optimization
- Avoid SELECT * - specify needed columns
- Use eager loading to prevent N+1 queries
- Batch operations when possible

### 3. Data Types
- Use appropriate column types and sizes
- VARCHAR for variable-length strings
- CHAR for fixed-length (like UUID)
- JSON for flexible structured data

### 4. Constraints
- Use foreign keys for data integrity
- Add CHECK constraints where applicable
- Use UNIQUE constraints for business rules

## Backup & Recovery

### Backup Strategy
1. **Full Backups**: Daily automated backups
2. **Incremental Backups**: Every 4 hours
3. **Transaction Logs**: Continuous backup
4. **Retention**: 30 days for daily, 7 days for incremental

### Recovery Procedures
1. **Point-in-Time Recovery**: Using transaction logs
2. **Tenant-Specific Export**: Export single tenant data
3. **Disaster Recovery**: Replicated to different region

## Future Considerations

### Partitioning
For large-scale deployments:
```sql
-- Partition by tenant_id for very large tables
ALTER TABLE events
PARTITION BY HASH(tenant_id)
PARTITIONS 64;
```

### Read Replicas
- Master for writes
- Multiple read replicas for queries
- Tenant-based routing to specific replicas

### Archival Strategy
- Move old data to archive tables
- Compress archived data
- Maintain separate archive database 