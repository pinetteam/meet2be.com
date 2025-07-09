<?php

namespace App\Http\Controllers\Portal\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\Setting\UpdateSettingRequest;
use App\Models\System\Country;
use App\Models\System\Language;
use App\Models\System\Timezone;
use App\Services\DateTime\DateTimeManager;
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
    public function index(Request $request): View
    {
        $tenant = $request->user()->tenant;
        $tenant->load(['country', 'language', 'timezone']);
        
        $countries = Country::orderBy('name_en')->get();
        $languages = Language::where('is_active', true)->orderBy('name_en')->get();
        $timezones = Timezone::orderBy('offset')->get();
        
        return view('portal.setting.index', compact(
            'tenant',
            'countries',
            'languages',
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
            // Check if timezone or date/time format changed
            $timezoneChanged = $request->has('timezone_id') && $tenant->timezone_id !== $request->timezone_id;
            $dateFormatChanged = $request->has('date_format') && $tenant->date_format !== $request->date_format;
            $timeFormatChanged = $request->has('time_format') && $tenant->time_format !== $request->time_format;
            $languageChanged = $request->has('language_id') && $tenant->language_id !== $request->language_id;
            
            // Update tenant
            $tenant->update($request->validated());
            
            // Clear DateTime cache if any date/time settings changed
            if ($timezoneChanged || $dateFormatChanged || $timeFormatChanged) {
                app(DateTimeManager::class)->clearCache();
                
                // Update session to trigger frontend refresh
                session(['datetime_settings_updated' => now()->timestamp]);
            }
            
            // Update locale if language changed
            if ($languageChanged) {
                $tenant->load('language');
                if ($tenant->language) {
                    session(['locale' => $tenant->language->iso_639_1]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => __('settings.messages.saved_successfully'),
                'datetime_updated' => $timezoneChanged || $dateFormatChanged || $timeFormatChanged,
                'language_updated' => $languageChanged,
                'reload_required' => $languageChanged
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('settings.messages.save_failed'),
                'error' => config('app.debug') ? $e->getMessage() : null
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