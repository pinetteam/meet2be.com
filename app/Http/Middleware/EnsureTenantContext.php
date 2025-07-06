<?php

namespace App\Http\Middleware;

use App\Services\TenantService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for non-authenticated users
        if (!Auth::check()) {
            return $next($request);
        }
        
        // Get current tenant ID
        $tenantId = TenantService::getCurrentTenantId();
        
        if (!$tenantId) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['tenant' => 'Invalid tenant context. Please log in again.']);
        }
        
        // Validate tenant exists and is active
        if (!TenantService::validateTenant($tenantId)) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['tenant' => 'Tenant access suspended. Please contact support.']);
        }
        
        // Set tenant context in request
        $request->merge(['current_tenant_id' => $tenantId]);
        
        return $next($request);
    }
} 