<?php

namespace Database\Seeders\System;

use Illuminate\Database\Seeder;
use App\Models\System\Country;
use App\Models\System\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CountryLanguageSeeder extends Seeder
{
    public function run(): void
    {
        $relationships = [
            // Major countries with their languages
            'US' => ['en'],
            'GB' => ['en', 'cy'],
            'CA' => ['en', 'fr'],
            'AU' => ['en'],
            'NZ' => ['en', 'mi'],
            'IE' => ['en', 'ga'],
            'ZA' => ['en', 'af', 'zu', 'xh', 'st', 'tn', 'ss', 've', 'ts', 'nd'],
            
            // Europe
            'FR' => ['fr'],
            'DE' => ['de'],
            'ES' => ['es', 'ca', 'eu', 'gl'],
            'IT' => ['it'],
            'PT' => ['pt'],
            'NL' => ['nl'],
            'BE' => ['nl', 'fr', 'de'],
            'CH' => ['de', 'fr', 'it'],
            'AT' => ['de'],
            'LU' => ['lb', 'fr', 'de'],
            'MT' => ['mt', 'en'],
            'CY' => ['el', 'tr'],
            
            // Nordics
            'SE' => ['sv'],
            'NO' => ['no'],
            'DK' => ['da'],
            'FI' => ['fi', 'sv'],
            'IS' => ['is'],
            
            // Eastern Europe
            'PL' => ['pl'],
            'CZ' => ['cs'],
            'SK' => ['sk'],
            'HU' => ['hu'],
            'RO' => ['ro'],
            'BG' => ['bg'],
            'GR' => ['el'],
            'RS' => ['sr'],
            'HR' => ['hr'],
            'SI' => ['sl'],
            'BA' => ['bs', 'hr', 'sr'],
            'ME' => ['sr'],
            'MK' => ['mk', 'sq'],
            'AL' => ['sq'],
            'EE' => ['et'],
            'LV' => ['lv'],
            'LT' => ['lt'],
            'BY' => ['be', 'ru'],
            'UA' => ['uk', 'ru'],
            'MD' => ['ro', 'ru'],
            
            // Middle East
            'TR' => ['tr', 'ku'],
            'SA' => ['ar'],
            'AE' => ['ar', 'en'],
            'QA' => ['ar'],
            'KW' => ['ar'],
            'BH' => ['ar'],
            'OM' => ['ar'],
            'YE' => ['ar'],
            'JO' => ['ar'],
            'LB' => ['ar', 'fr'],
            'SY' => ['ar', 'ku'],
            'IQ' => ['ar', 'ku'],
            'IL' => ['he', 'ar'],
            'IR' => ['fa', 'az', 'ku'],
            
            // Central Asia
            'AF' => ['ps', 'uz', 'tk'],
            'PK' => ['ur', 'en', 'pa', 'ps'],
            'KZ' => ['kk', 'ru'],
            'UZ' => ['uz', 'ru'],
            'TM' => ['tk', 'ru'],
            'KG' => ['ky', 'ru'],
            'TJ' => ['tg', 'ru'],
            
            // South Asia
            'IN' => ['hi', 'en', 'bn', 'te', 'mr', 'ta', 'ur', 'gu', 'kn', 'ml', 'or', 'pa', 'as', 'ne'],
            'BD' => ['bn'],
            'LK' => ['si', 'ta', 'en'],
            'NP' => ['ne'],
            'BT' => ['dz'],
            'MV' => ['dv'],
            
            // Southeast Asia
            'ID' => ['id', 'jv', 'su'],
            'MY' => ['ms', 'en', 'zh', 'ta'],
            'SG' => ['en', 'ms', 'zh', 'ta'],
            'TH' => ['th'],
            'VN' => ['vi'],
            'PH' => ['tl', 'en'],
            'MM' => ['my'],
            'KH' => ['km'],
            'LA' => ['lo'],
            'BN' => ['ms'],
            
            // East Asia
            'CN' => ['zh', 'ug', 'bo', 'mn'],
            'JP' => ['ja'],
            'KR' => ['ko'],
            'KP' => ['ko'],
            'TW' => ['zh'],
            'MN' => ['mn'],
            'HK' => ['zh', 'en'],
            'MO' => ['zh', 'pt'],
            
            // Africa - North
            'EG' => ['ar'],
            'LY' => ['ar'],
            'TN' => ['ar', 'fr'],
            'DZ' => ['ar', 'fr'],
            'MA' => ['ar', 'fr'],
            'SD' => ['ar', 'en'],
            'SS' => ['en', 'ar'],
            
            // Africa - West
            'NG' => ['en', 'ha', 'yo', 'ig', 'ff'],
            'GH' => ['en', 'ak', 'ee', 'ha'],
            'SN' => ['fr', 'wo', 'ff'],
            'ML' => ['fr', 'bm', 'ff'],
            'BF' => ['fr', 'ff'],
            'NE' => ['fr', 'ha', 'ff'],
            'CI' => ['fr'],
            'GN' => ['fr', 'ff'],
            'GM' => ['en', 'wo'],
            'SL' => ['en'],
            'LR' => ['en'],
            'TG' => ['fr', 'ee', 'yo'],
            'BJ' => ['fr', 'yo'],
            'GW' => ['pt'],
            'CV' => ['pt'],
            'MR' => ['ar', 'fr', 'wo'],
            
            // Africa - Central
            'CM' => ['fr', 'en', 'ff'],
            'CF' => ['fr', 'sg', 'ln'],
            'TD' => ['fr', 'ar'],
            'CD' => ['fr', 'ln', 'sw', 'rw'],
            'CG' => ['fr', 'ln'],
            'GA' => ['fr'],
            'GQ' => ['es', 'fr', 'pt'],
            'AO' => ['pt', 'ln'],
            'ST' => ['pt'],
            
            // Africa - East
            'ET' => ['am', 'ti', 'so'],
            'ER' => ['ti', 'ar'],
            'DJ' => ['fr', 'ar', 'so'],
            'SO' => ['so', 'ar'],
            'KE' => ['en', 'sw'],
            'UG' => ['en', 'sw', 'lg', 'rw'],
            'RW' => ['rw', 'fr', 'en', 'sw'],
            'BI' => ['rn', 'fr', 'sw'],
            'TZ' => ['sw', 'en'],
            'MW' => ['ny', 'en'],
            'ZM' => ['en', 'ny'],
            'MZ' => ['pt', 'sn', 'ny', 'ts'],
            'MG' => ['mg', 'fr'],
            'SC' => ['en', 'fr'],
            'MU' => ['en', 'fr'],
            'KM' => ['ar', 'fr'],
            
            // Africa - Southern
            'ZW' => ['en', 'sn', 'nd', 've'],
            'BW' => ['en', 'tn'],
            'NA' => ['en', 'af', 'de'],
            'SZ' => ['en', 'ss'],
            'LS' => ['en', 'st'],
            
            // Americas - South
            'BR' => ['pt'],
            'AR' => ['es', 'gn'],
            'CL' => ['es'],
            'PE' => ['es', 'qu', 'ay'],
            'CO' => ['es'],
            'VE' => ['es'],
            'EC' => ['es', 'qu'],
            'BO' => ['es', 'qu', 'ay', 'gn'],
            'PY' => ['es', 'gn'],
            'UY' => ['es'],
            'GY' => ['en'],
            'SR' => ['nl'],
            'GF' => ['fr'],
            
            // Americas - Central & Caribbean
            'MX' => ['es'],
            'GT' => ['es'],
            'HN' => ['es'],
            'SV' => ['es'],
            'NI' => ['es'],
            'CR' => ['es'],
            'PA' => ['es'],
            'CU' => ['es'],
            'DO' => ['es'],
            'HT' => ['fr', 'ht'],
            'JM' => ['en'],
            'TT' => ['en'],
            'BB' => ['en'],
            'BS' => ['en'],
            'BZ' => ['en', 'es'],
            
            // Pacific
            'FJ' => ['en', 'fj', 'hi'],
            'PG' => ['en'],
            'SB' => ['en'],
            'VU' => ['en', 'fr', 'bi'],
            'NC' => ['fr'],
            'PF' => ['fr'],
            'WS' => ['sm', 'en'],
            'TO' => ['to', 'en'],
            'TV' => ['en'],
            'NR' => ['en'],
            'KI' => ['en'],
            'PW' => ['en'],
            'MH' => ['en'],
            'FM' => ['en'],
            
            // Others
            'GL' => ['kl', 'da'],
            'FO' => ['fo', 'da'],
            'AD' => ['ca'],
            'MC' => ['fr'],
            'SM' => ['it'],
            'VA' => ['it'],
            'LI' => ['de'],
        ];
        
        $inserted = 0;
        
        foreach ($relationships as $countryCode => $languageCodes) {
            $country = Country::where('iso2', $countryCode)->first();
            
            if (!$country) {
                $this->command->warn("Country not found: {$countryCode}");
                continue;
            }
            
            foreach ($languageCodes as $languageCode) {
                $language = Language::where('iso_639_1', $languageCode)->first();
                
                if (!$language) {
                    $this->command->warn("Language not found: {$languageCode} for country {$countryCode}");
                    continue;
                }
                
                DB::table('system_country_language')->insert([
                    'id' => Str::uuid(),
                    'country_id' => $country->id,
                    'language_id' => $language->id,
                    'is_primary' => ($languageCode === $languageCodes[0]),
                    'created_at' => Carbon::now('UTC'),
                    'updated_at' => Carbon::now('UTC'),
                ]);
                
                $inserted++;
            }
        }
        
        $this->command->info("Country-Language relationships seeded successfully! Total: {$inserted}");
    }
} 