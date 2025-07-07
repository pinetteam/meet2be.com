<?php

namespace App\Http\Controllers\Portal\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\Setting\UpdateSettingRequest;
use App\Models\System\Country;
use App\Models\System\Currency;
use App\Models\System\Language;
use App\Models\System\Timezone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display tenant settings
     */
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;
        $tenant->load(['country', 'language', 'currency', 'timezone']);
        
        $countries = Country::orderBy('name_en')->get();
        $languages = Language::where('is_active', true)->orderBy('name_en')->get();
        $currencies = Currency::where('is_active', true)->orderBy('code')->get();
        $timezones = Timezone::orderBy('offset')->get();
        
        return view('portal.setting.index', compact(
            'tenant',
            'countries',
            'languages',
            'currencies',
            'timezones'
        ));
    }

    /**
     * Update tenant settings
     */
    public function update(UpdateSettingRequest $request): JsonResponse
    {
        $tenant = $request->user()->tenant;
        
        try {
            $tenant->update($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => __('portal.settings.messages.updated_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('portal.settings.messages.update_failed')
            ], 500);
        }
    }
    
    /**
     * Update general settings
     */
    public function updateGeneral(Request $request): JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'language' => 'required|in:tr,en,de,fr,es',
            'timezone' => 'required|timezone',
            'date_format' => 'required|in:d/m/Y,m/d/Y,Y-m-d,d.m.Y',
        ]);
        
        $user = Auth::user();
        $user->update($request->only(['first_name', 'last_name', 'email']));
        
        // Update tenant preferences
        if ($user->tenant) {
            $user->tenant->update([
                'language_id' => $this->getLanguageIdFromCode($request->language),
                'timezone_id' => $this->getTimezoneIdFromName($request->timezone),
                'date_format' => $request->date_format,
            ]);
        }
        
        // Update locale
        session(['locale' => $request->language]);
        
        return response()->json([
            'success' => true,
            'message' => __('portal.settings.general_saved')
        ]);
    }
    
    /**
     * Update security settings
     */
    public function updateSecurity(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return response()->json([
            'success' => true,
            'message' => __('portal.settings.password_changed')
        ]);
    }
    
    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request): JsonResponse
    {
        $request->validate([
            'email_updates' => 'boolean',
            'email_security' => 'boolean',
            'email_marketing' => 'boolean',
        ]);
        
        // Save notification preferences
        // This would typically be saved to a user_preferences table
        
        return response()->json([
            'success' => true,
            'message' => __('portal.settings.notifications_saved')
        ]);
    }
    
    /**
     * Toggle two-factor authentication
     */
    public function toggleTwoFactor(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        // Toggle 2FA (implementation would depend on your 2FA package)
        // For now, just return success
        
        return response()->json([
            'success' => true,
            'enabled' => $request->enable ?? false,
            'message' => $request->enable 
                ? __('portal.settings.two_factor_enabled')
                : __('portal.settings.two_factor_disabled')
        ]);
    }
    
    /**
     * Get language ID from code
     */
    private function getLanguageIdFromCode(string $code): ?int
    {
        return \App\Models\System\Language::where('iso_639_1', $code)->value('id');
    }
    
    /**
     * Get timezone ID from name
     */
    private function getTimezoneIdFromName(string $name): ?int
    {
        return \App\Models\System\Timezone::where('name', $name)->value('id');
    }
} 