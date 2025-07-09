<?php

return [
    // Sayfa
    'title' => 'Ayarlar',
    'subtitle' => 'Organizasyon ayarlarınızı yönetin',
    
    // Sekmeler
    'tabs' => [
        'general' => 'Genel',
        'contact' => 'İletişim',
        'localization' => 'Yerelleştirme',
        'subscription' => 'Abonelik',
    ],
    
    // Bölümler
    'sections' => [
        'organization_info' => 'Organizasyon Bilgileri',
        'contact_info' => 'İletişim Bilgileri',
        'address' => 'Adres',
        'regional_settings' => 'Bölgesel Ayarlar',
        'datetime_formats' => 'Tarih ve Saat Formatları',
    ],
    
    // Alanlar
    'fields' => [
        // Genel
        'organization_name' => 'Organizasyon Adı',
        'legal_name' => 'Yasal Unvan',
        'organization_code' => 'Organizasyon Kodu',
        'organization_id' => 'Organizasyon ID',
        
        // İletişim
        'email' => 'E-posta Adresi',
        'phone' => 'Telefon Numarası',
        'website' => 'Web Sitesi',
        
        // Adres
        'address_line_1' => 'Adres Satırı 1',
        'address_line_2' => 'Adres Satırı 2',
        'city' => 'Şehir',
        'state' => 'İl/Eyalet',
        'postal_code' => 'Posta Kodu',
        'country' => 'Ülke',
        
        // Yerelleştirme
        'language' => 'Dil',
        'timezone' => 'Saat Dilimi',
        'date_format' => 'Tarih Formatı',
        'time_format' => 'Saat Formatı',
    ],
    
    // Yer Tutucular
    'placeholders' => [
        'organization_name' => 'Organizasyon adını girin',
        'legal_name' => 'Yasal işletme adını girin',
        'email' => 'info@ornek.com',
        'website' => 'https://www.ornek.com',
        'address_line_1' => 'Sokak adresi, Posta kutusu, şirket adı',
        'address_line_2' => 'Daire, süit, birim, bina, kat, vb.',
        'city' => 'Şehir veya ilçe adı',
        'state' => 'İl, eyalet veya bölge',
        'postal_code' => 'Posta kodu',
        'select_country' => 'Bir ülke seçin',
        'select_language' => 'Bir dil seçin',
        'select_timezone' => 'Bir saat dilimi seçin',
    ],
    
    // İpuçları
    'hints' => [
        'organization_name' => 'Bu ad sistem genelinde görüntülenecektir',
        'legal_name' => 'Yasal belgeler ve faturalar için resmi kayıtlı isim',
        'organization_code' => 'Organizasyonunuza atanan benzersiz kod',
        'organization_id' => 'Organizasyonunuz için benzersiz sistem tanımlayıcısı',
        'email' => 'Organizasyonunuz için birincil iletişim e-postası',
        'phone' => 'İş iletişimleri için birincil iletişim numarası',
        'website' => 'Organizasyonunuzun web sitesi URL\'si',
        'address_line_1' => 'Sokak adresi veya posta kutusu',
        'address_line_2' => 'Ek adres bilgisi (isteğe bağlı)',
        'city' => 'Şehir veya ilçe',
        'state' => 'İl, eyalet veya idari bölge',
        'postal_code' => 'Posta kodu',
        'country' => 'Adres ve bölgesel ayarlar için ülke',
        'language' => 'Organizasyonunuz için varsayılan dil',
        'timezone' => 'Zamanlama ve zaman damgaları için saat dilimi',
        'date_format' => 'Tarihler sistem genelinde nasıl görüntülenecek',
        'time_format' => 'Saatler sistem genelinde nasıl görüntülenecek',
        'no_changes' => 'Kaydedilecek değişiklik yok',
        'save_changes' => 'Kaydedilmemiş değişiklikleriniz var',
    ],
    
    // Tarih Formatları
    'date_formats' => [
        'iso8601' => 'ISO 8601',
        'european' => 'Avrupa',
        'us' => 'ABD',
        'european_dot' => 'Avrupa (nokta)',
        'european_dash' => 'Avrupa (tire)',
        'long' => 'Uzun format',
        'full' => 'Tam format',
        'compact' => 'Kompakt',
        'medium' => 'Orta',
    ],
    
    // Saat Formatları
    'time_formats' => [
        '24h' => '24 saat',
        '24h_seconds' => '24 saat (saniyeli)',
        '12h' => '12 saat',
        '12h_seconds' => '12 saat (saniyeli)',
        '12h_leading' => '12 saat (başında sıfır)',
        '12h_leading_seconds' => '12 saat (başında sıfır ve saniyeli)',
    ],
    
    // Abonelik
    'subscription' => [
        'current_plan' => 'Mevcut Plan',
        'plan' => 'Plan',
        'status' => 'Durum',
        'expires' => 'Bitiş',
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
        'datetime_updated' => 'Tarih ve saat ayarları güncellendi. Değişiklikleri uygulamak için sayfa yeniden yüklenecek.',
    ],
]; 