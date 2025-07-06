<?php

namespace App\Services;

use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class TenantService
{
    private static ?string $currentTenantId = null;
    
    /**
     * Get current tenant ID from session or authenticated user
     */
    public static function getCurrentTenantId(): ?string
    {
        // Memory cache
        if (self::$currentTenantId) {
            return self::$currentTenantId;
        }
        
        // Session cache
        $sessionTenantId = Session::get('current_tenant_id');
        if ($sessionTenantId) {
            self::$currentTenantId = $sessionTenantId;
            return self::$currentTenantId;
        }
        
        // Get from authenticated user
        if (Auth::check()) {
            $userId = Auth::id();
            
            // Direct DB query to avoid circular dependency
            $tenantId = DB::table('users')
                ->where('id', $userId)
                ->value('tenant_id');
            
            if ($tenantId) {
                self::$currentTenantId = $tenantId;
                Session::put('current_tenant_id', $tenantId);
                return $tenantId;
            }
        }
        
        return null;
    }
    
    /**
     * Set current tenant
     */
    public static function setCurrentTenant(?Tenant $tenant): void
    {
        if ($tenant) {
            self::$currentTenantId = $tenant->id;
            Session::put('current_tenant_id', $tenant->id);
        } else {
            self::clearCurrentTenant();
        }
    }
    
    /**
     * Clear tenant context
     */
    public static function clearCurrentTenant(): void
    {
        self::$currentTenantId = null;
        Session::forget('current_tenant_id');
    }
    
    /**
     * Check if user has access to tenant
     */
    public static function checkTenantAccess($tenantId): bool
    {
        if (!$tenantId) {
            return false;
        }
        
        $currentTenantId = self::getCurrentTenantId();
        return $currentTenantId && $currentTenantId === $tenantId;
    }
    
    /**
     * Ensure user has access to tenant or abort
     */
    public static function ensureTenantAccess($tenantId): void
    {
        if (!self::checkTenantAccess($tenantId)) {
            abort(403, 'Access denied to this tenant.');
        }
    }
    
    /**
     * Validate tenant exists and is active
     */
    public static function validateTenant(string $tenantId): bool
    {
        return DB::table('tenants')
            ->where('id', $tenantId)
            ->whereIn('status', ['active', 'trial'])
            ->whereNull('deleted_at')
            ->exists();
    }
} 