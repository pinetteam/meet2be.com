<?php

namespace App\Http\Controllers\Portal\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\Event\Event;
use App\Services\TenantService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tenantId = TenantService::getCurrentTenantId();
        
        // İstatistikler
        $stats = [
            'total_users' => User::where('tenant_id', $tenantId)->count(),
            'active_users' => User::where('tenant_id', $tenantId)
                ->where('status', User::STATUS_ACTIVE)
                ->count(),
            'total_events' => Event::where('tenant_id', $tenantId)->count(),
            'upcoming_events' => Event::where('tenant_id', $tenantId)
                ->upcoming()
                ->count(),
        ];
        
        // Son eklenen kullanıcılar
        $recent_users = User::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Yaklaşan etkinlikler
        $upcoming_events = Event::where('tenant_id', $tenantId)
            ->upcoming()
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return view('portal.dashboard.index', compact('stats', 'recent_users', 'upcoming_events'));
    }
} 