<?php

namespace App\Http\Controllers\Portal\Settings;

use App\Http\Controllers\Controller;
use App\Models\System\Country;
use App\Models\System\Currency;
use App\Models\System\Language;
use App\Models\System\Timezone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = Auth::user()->tenant;
        $locale = app()->getLocale();
        
        // Sistem verilerini al
        $countries = Country::where('is_active', true)
            ->orderBy($locale === 'tr' ? 'name_tr' : 'name_en')
            ->get();
        $currencies = Currency::where('is_active', true)->orderBy('code')->get();
        $languages = Language::where('is_active', true)
            ->orderBy($locale === 'tr' ? 'name_tr' : 'name_en')
            ->get();
        
        // Timezone'ları bölgelere göre grupla
        $timezones = Timezone::orderBy('name')->get();
        $groupedTimezones = $timezones->groupBy(function ($timezone) {
            // Timezone adından bölgeyi çıkar (örn: "Europe/Istanbul" -> "Europe")
            $parts = explode('/', $timezone->name);
            return count($parts) > 1 ? $parts[0] : 'Other';
        })->sortKeys()->map(function ($group) {
            // Her grup içindeki timezone'ları offset'e göre sırala
            return $group->sortBy('offset');
        });
        
        // Telefon numarasını parse et
        $phoneNumber = null;
        $currentCountry = null;
        
        if ($tenant->phone) {
            // Varsayılan olarak tenant'ın ülkesini kullan, yoksa Türkiye
            $currentCountry = $tenant->country_id 
                ? $countries->firstWhere('id', $tenant->country_id)
                : $countries->firstWhere('iso2', 'TR');
            
            $phoneNumber = $tenant->phone;
            
            // Eğer telefon numarası ülke koduyla başlıyorsa ayır
            if ($currentCountry) {
                $phoneCode = ltrim($currentCountry->phone_code, '+');
                if (str_starts_with($tenant->phone, $phoneCode)) {
                    $phoneNumber = substr($tenant->phone, strlen($phoneCode));
                }
            }
        } else {
            // Varsayılan olarak tenant'ın ülkesini veya Türkiye'yi seç
            $currentCountry = $tenant->country_id 
                ? $countries->firstWhere('id', $tenant->country_id)
                : $countries->firstWhere('iso2', 'TR');
        }
        
        return view('portal.settings.index', compact('tenant', 'countries', 'currencies', 'languages', 'groupedTimezones', 'currentCountry', 'phoneNumber'));
    }
    
    public function update(Request $request): RedirectResponse
    {
        $tenant = Auth::user()->tenant;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'phone_country_id' => 'nullable|exists:system_countries,id',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country_id' => 'nullable|exists:system_countries,id',
            'currency_id' => 'nullable|exists:system_currencies,id',
            'language_id' => 'nullable|exists:system_languages,id',
            'timezone_id' => 'nullable|exists:system_timezones,id',
            'date_format' => 'nullable|string|in:Y-m-d,d/m/Y,m/d/Y,d.m.Y,d-m-Y,M j, Y,F j, Y,j F Y',
            'time_format' => 'nullable|string|in:H:i,H:i:s,g:i A,g:i:s A,h:i A,h:i:s A',
        ]);
        
        // address_line_1 için address'i kullan
        if (isset($validated['address'])) {
            $validated['address_line_1'] = $validated['address'];
            unset($validated['address']);
        }
        
        // Telefon numarasını ülke koduyla birleştir
        if (!empty($validated['phone']) && !empty($validated['phone_country_id'])) {
            $country = Country::findOrFail($validated['phone_country_id']);
            $phoneCode = ltrim($country->phone_code, '+');
            
            // Telefon numarasından mask karakterlerini kaldır
            $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);
            
            $validated['phone'] = $phoneCode . $cleanPhone;
            unset($validated['phone_country_id']);
        }
        
        // Timezone değişti mi kontrol et
        $timezoneChanged = isset($validated['timezone_id']) && $tenant->timezone_id != $validated['timezone_id'];
        
        $tenant->update($validated);
        
        // Timezone değiştiyse relationship'leri yenile
        if ($timezoneChanged) {
            // Tenant modelini ve ilişkilerini yenile
            $tenant->refresh();
            $tenant->load('timezone');
            
            // User modelindeki tenant'ı da yenile
            Auth::user()->load('tenant.timezone');
        }
        
        return redirect()
            ->route('portal.settings.index')
            ->with('success', __('settings.messages.updated_successfully'));
    }
} 