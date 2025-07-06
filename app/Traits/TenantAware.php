<?php

namespace App\Traits;

use App\Services\TenantService;
use Illuminate\Database\Eloquent\Builder;

trait TenantAware
{
    protected static function bootTenantAware()
    {
        // Auto-assign tenant_id on creation
        static::creating(function ($model) {
            if (!$model->tenant_id && TenantService::getCurrentTenantId()) {
                $model->tenant_id = TenantService::getCurrentTenantId();
            }
        });
    }
    
    /**
     * Override newQuery to automatically add tenant filtering
     */
    public function newQuery(): Builder
    {
        $builder = parent::newQuery();
        
        // Only apply tenant filter if we have a tenant context
        if ($this->shouldApplyTenantFilter()) {
            $tenantId = TenantService::getCurrentTenantId();
            if ($tenantId) {
                $builder->where($this->getTable() . '.tenant_id', $tenantId);
            }
        }
        
        return $builder;
    }
    
    /**
     * Check if tenant filter should be applied
     */
    protected function shouldApplyTenantFilter(): bool
    {
        // Skip if table doesn't have tenant_id
        if (!$this->hasTenantColumn()) {
            return false;
        }
        
        // Allow disabling tenant filter
        if (property_exists($this, 'disableTenantFilter') && $this->disableTenantFilter) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if model has tenant_id column
     */
    protected function hasTenantColumn(): bool
    {
        return in_array('tenant_id', $this->fillable ?? []);
    }
    
    /**
     * Get a new query without tenant filtering
     */
    public static function withoutTenantFilter(): Builder
    {
        $instance = new static;
        $instance->disableTenantFilter = true;
        return $instance->newQuery();
    }
    
    /**
     * Scope to filter by specific tenant
     */
    public function scopeForTenant(Builder $query, string $tenantId): Builder
    {
        return $query->where($this->getTable() . '.tenant_id', $tenantId);
    }
} 