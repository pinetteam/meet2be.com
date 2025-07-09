<?php

return [
    'title' => 'Ayarlar',
    'subtitle' => 'Kuruluş bilgilerinizi ve tercihlerinizi yönetin',
    'select_tab' => 'Ayar kategorisi seçin',
    
    // Sekmeler
    'tabs' => [
        'general' => 'Genel',
        'contact' => 'İletişim',
        'localization' => 'Yerelleştirme',
        'subscription' => 'Abonelik',
    ],
    
    // Bölümler
    'sections' => [
        'organization_info' => 'Kuruluş Bilgileri',
        'contact_info' => 'İletişim Bilgileri',
        'address' => 'Adres',
        'regional_settings' => 'Bölgesel Ayarlar',
        'datetime_formats' => 'Tarih ve Saat Formatları',
    ],
    
    // Alanlar
    'fields' => [
        // Kuruluş
        'organization_name' => 'Kuruluş Adı',
        'legal_name' => 'Yasal Adı',
        'organization_code' => 'Kuruluş Kodu',
        'url_slug' => 'URL Kısa Adı',
        
        // İletişim
        'email' => 'E-posta Adresi',
        'phone' => 'Telefon Numarası',
        'website' => 'Web Sitesi',
        
        // Adres
        'address_line_1' => 'Adres Satırı 1',
        'address_line_2' => 'Adres Satırı 2',
        'city' => 'Şehir',
        'state' => 'Eyalet/İl',
        'postal_code' => 'Posta Kodu',
        'country' => 'Ülke',
        
        // Yerelleştirme
        'language' => 'Dil',
        'currency' => 'Para Birimi',
        'timezone' => 'Saat Dilimi',
        'date_format' => 'Tarih Formatı',
        'time_format' => 'Saat Formatı',
    ],
    
    // Yer tutucular
    'placeholders' => [
        'organization_name' => 'Acme Şirketi',
        'legal_name' => 'Acme Şirketi Ltd. Şti.',
        'email' => 'iletisim@ornek.com',
        'phone' => '+90 (555) 123-4567',
        'address_line_1' => 'Atatürk Caddesi No: 123',
        'address_line_2' => 'Kat 4 Daire 10',
        'city' => 'İstanbul',
        'state' => 'İstanbul',
        'postal_code' => '34000',
        'select_country' => 'Bir ülke seçin',
        'select_language' => 'Bir dil seçin',
        'select_currency' => 'Bir para birimi seçin',
        'select_timezone' => 'Bir saat dilimi seçin',
    ],
    
    // İpuçları
    'hints' => [
        'organization_name' => 'Bu ad tüm kullanıcılara görünür olacaktır',
        'organization_code' => 'Sistem tarafından otomatik oluşturulur',
        'phone' => 'Uluslararası numaralar için ülke kodunu dahil edin',
        'save_changes' => 'Tüm değişiklikler hemen geçerli olacaktır',
    ],
    
    // Abonelik
    'subscription' => [
        'current_plan' => 'Mevcut Abonelik Planı',
        'plan' => 'Plan',
        'status' => 'Durum',
        'expires' => 'Bitiş Tarihi',
        'usage_statistics' => 'Kullanım İstatistikleri',
        'users' => 'Kullanıcılar',
        'storage' => 'Depolama',
        'events' => 'Etkinlikler',
        'used' => 'kullanıldı',
    ],
    
    // Mesajlar
    'messages' => [
        'saved_successfully' => 'Ayarlar başarıyla kaydedildi',
        'save_failed' => 'Ayarlar kaydedilemedi. Lütfen tekrar deneyin.',
    ],
]; 