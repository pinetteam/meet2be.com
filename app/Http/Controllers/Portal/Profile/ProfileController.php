<?php

namespace App\Http\Controllers\Portal\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\Profile\UpdateProfileRequest;
use App\Models\System\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $countries = Country::where('is_active', true)
            ->orderBy('name_en')
            ->get();
        
        // Telefon numarasını parse et
        $phoneNumber = null;
        $currentCountry = null;
        
        if ($user->phone) {
            // Varsayılan olarak Türkiye
            $currentCountry = $countries->firstWhere('iso2', 'TR');
            $phoneNumber = $user->phone;
            
            // Eğer telefon numarası ülke koduyla başlıyorsa ayır
            foreach ($countries as $country) {
                $phoneCode = ltrim($country->phone_code, '+');
                if (str_starts_with($user->phone, $phoneCode)) {
                    $currentCountry = $country;
                    $phoneNumber = substr($user->phone, strlen($phoneCode));
                    break;
                }
            }
        } else {
            // Varsayılan olarak Türkiye seçili gelsin
            $currentCountry = $countries->firstWhere('iso2', 'TR');
        }
        
        return view('portal.profile.index', compact('user', 'countries', 'currentCountry', 'phoneNumber'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $section = $request->input('section', 'all');
        
        // Section bazlı güncelleme
        switch ($section) {
            case 'personal':
                $user->update($request->only(['first_name', 'last_name', 'username']));
                $message = 'Kişisel bilgileriniz güncellendi.';
                break;
                
            case 'contact':
                $validated = $request->only(['email', 'phone', 'phone_country_id']);
                
                // Telefon numarasını ülke koduyla birleştir
                if (isset($validated['phone']) && isset($validated['phone_country_id'])) {
                    $country = Country::findOrFail($validated['phone_country_id']);
                    $phoneCode = ltrim($country->phone_code, '+');
                    
                    // Telefon numarasından mask karakterlerini kaldır (boşluk, tire, parantez vb.)
                    $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);
                    
                    $validated['phone'] = $phoneCode . $cleanPhone;
                    unset($validated['phone_country_id']);
                }
                
                $user->update($validated);
                $message = 'İletişim bilgileriniz güncellendi.';
                break;
                
            case 'password':
                // Mevcut şifreyi kontrol et
                if (!Hash::check($request->input('current_password'), $user->password)) {
                    return redirect()
                        ->route('portal.profile.index')
                        ->withErrors(['current_password' => 'Mevcut şifreniz yanlış.'])
                        ->withInput();
                }
                
                $user->update([
                    'password' => Hash::make($request->input('password'))
                ]);
                
                $message = 'Şifreniz başarıyla değiştirildi.';
                break;
                
            default:
                // Tüm alanları güncelle (eski davranış)
                $validated = $request->validated();
                
                // Telefon numarasını ülke koduyla birleştir
                if (isset($validated['phone']) && isset($validated['phone_country_id'])) {
                    $country = Country::findOrFail($validated['phone_country_id']);
                    $phoneCode = ltrim($country->phone_code, '+');
                    $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);
                    $validated['phone'] = $phoneCode . $cleanPhone;
                    unset($validated['phone_country_id']);
                }
                
                $user->update($validated);
                $message = 'Profil bilgileriniz başarıyla güncellendi.';
        }

        return redirect()
            ->route('portal.profile.index')
            ->with('success', $message);
    }
} 