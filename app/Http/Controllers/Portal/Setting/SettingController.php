<?php

namespace App\Http\Controllers\Portal\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\Setting\UpdateSettingRequest;
use App\Models\System\Country;
use App\Models\System\Language;
use App\Models\System\Timezone;
use App\Services\DateTime\DateTimeManager;
use Illuminate\Http\Request;
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
    public function update(UpdateSettingRequest $request)
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
            
            // Return JSON for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('settings.messages.saved_successfully'),
                    'datetime_updated' => $timezoneChanged || $dateFormatChanged || $timeFormatChanged,
                    'language_updated' => $languageChanged,
                    'reload_required' => $languageChanged
                ]);
            }
            
            // Return redirect for regular form submission
            return redirect()->route('portal.setting.index')
                ->with('success', __('settings.messages.saved_successfully'));
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('settings.messages.save_failed'),
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return redirect()->route('portal.setting.index')
                ->with('error', __('settings.messages.save_failed'))
                ->withInput();
        }
    }
} 