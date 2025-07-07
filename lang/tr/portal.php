<?php

return [
    // Ana başlık
    'title' => 'Portal',
    
    // Navigasyon menüsü
    'navigation' => [
        'dashboard' => 'Dashboard',
        'preparation' => 'Hazırlık',
        'documents' => 'Dökümanlar',
        'participants' => 'Katılımcılar',
        'event_activity' => 'Etkinlik & Aktivite',
        'announcements' => 'Duyurular',
        'score_games' => 'Puan Oyunları',
        'surveys' => 'Anketler',
        'environment' => 'Ortam',
        'halls' => 'Salonlar',
        'virtual_stands' => 'Sanal Stantlar',
        'system_management' => 'Sistem Yönetimi',
        'tenants' => "Tenant'ler",
        'countries' => 'Ülkeler',
        'languages' => 'Diller',
        'currencies' => 'Para Birimleri',
        'timezones' => 'Saat Dilimleri',
        'reports' => 'Raporlar',
        'users' => 'Kullanıcılar',
        'settings' => 'Ayarlar',
    ],
    
    // Header
    'header' => [
        'search' => 'Ara',
        'search_placeholder' => 'Ara...',
        'toggle_theme' => 'Tema değiştir',
        'notifications' => 'Bildirimler',
        'user_menu' => 'Kullanıcı menüsü',
        'default_user' => 'Kullanıcı',
    ],
    
    // Profil menüsü
    'profile_menu' => [
        'profile' => 'Profilim',
        'logout' => 'Çıkış Yap',
    ],
    
    // Footer
    'footer' => [
        'copyright' => '© :year :company. Tüm hakları saklıdır.',
        'version' => 'Versiyon :version',
        'made_with' => 'ile yapıldı',
        'by' => 'tarafından',
    ],
    
    // Genel
    'general' => [
        'loading' => 'Yükleniyor...',
        'error' => 'Bir hata oluştu',
        'success' => 'İşlem başarılı',
        'warning' => 'Uyarı',
        'info' => 'Bilgi',
        'general' => 'Genel',
        'contact' => 'İletişim',
        'localization' => 'Yerelleştirme',
        'subscription' => 'Abonelik',
    ],
    
    // Settings
    'settings' => [
        'title' => 'Ayarlar',
        'subtitle' => 'Organizasyon bilgilerinizi ve tercihlerinizi yönetin',
        'description' => 'Hesap ayarlarınızı ve tercihlerinizi yönetin',
        'tabs' => [
            'general' => 'Genel',
            'contact' => 'İletişim',
            'localization' => 'Yerelleştirme',
            'subscription' => 'Abonelik',
            'security' => 'Güvenlik',
            'notifications' => 'Bildirimler',
        ],
        'fields' => [
            'name' => 'Organizasyon Adı',
            'legal_name' => 'Yasal Ünvan',
            'code' => 'Organizasyon Kodu',
            'slug' => 'URL Kısa Adı',
            'type' => 'Organizasyon Tipi',
            'email' => 'E-posta',
            'phone' => 'Telefon',
            'website' => 'Web Sitesi',
            'address_line_1' => 'Adres Satırı 1',
            'address_line_2' => 'Adres Satırı 2',
            'city' => 'Şehir',
            'state' => 'Eyalet/İl',
            'postal_code' => 'Posta Kodu',
            'country' => 'Ülke',
            'language' => 'Dil',
            'currency' => 'Para Birimi',
            'timezone' => 'Saat Dilimi',
            'date_format' => 'Tarih Formatı',
            'time_format' => 'Saat Formatı',
        ],
        'hints' => [
            'name' => 'Bu isim tüm kullanıcılara görünür olacaktır',
            'code' => 'Sistem tarafından otomatik olarak oluşturulur',
        ],
        'placeholders' => [
            'select_country' => 'Ülke Seçin',
            'select_language' => 'Dil Seçin',
            'select_currency' => 'Para Birimi Seçin',
            'select_timezone' => 'Saat Dilimi Seçin',
        ],
        'buttons' => [
            'save' => 'Değişiklikleri Kaydet',
            'saving' => 'Kaydediliyor...',
            'cancel' => 'İptal',
        ],
        'messages' => [
            'updated_successfully' => 'Ayarlar başarıyla güncellendi',
            'update_failed' => 'Ayarlar güncellenirken bir hata oluştu',
        ],
        'subscription' => [
            'current_plan' => 'Mevcut Plan',
            'plan' => 'Plan',
            'status' => 'Durum',
            'expires' => 'Bitiş Tarihi',
            'users' => 'Kullanıcılar',
            'storage' => 'Depolama',
            'events' => 'Etkinlikler',
            'used' => 'kullanıldı',
        ],
        'save_changes' => 'Değişiklikleri Kaydet',
        'saving' => 'Kaydediliyor...',
        'success' => 'Başarılı',
        'save_failed' => 'Kaydetme başarısız oldu',
        'passwords_not_match' => 'Parolalar eşleşmiyor',
        'general' => [
            'profile' => [
                'title' => 'Profil Bilgileri',
                'description' => 'Temel profil bilgilerinizi güncelleyin',
                'first_name' => 'Ad',
                'first_name_placeholder' => 'Adınızı girin',
                'last_name' => 'Soyad',
                'last_name_placeholder' => 'Soyadınızı girin',
                'email' => 'E-posta Adresi',
                'email_placeholder' => 'E-posta adresinizi girin',
            ],
            'preferences' => [
                'title' => 'Tercihler',
                'description' => 'Dil ve tarih ayarlarınızı özelleştirin',
                'language' => 'Dil',
                'timezone' => 'Saat Dilimi',
                'date_format' => 'Tarih Formatı',
            ],
        ],
        'security' => [
            'password' => [
                'title' => 'Parola Değiştir',
                'description' => 'Hesap güvenliğiniz için güçlü bir parola kullanın',
                'current' => 'Mevcut Parola',
                'new' => 'Yeni Parola',
                'confirm' => 'Yeni Parola (Tekrar)',
                'requirements' => 'En az 8 karakter, bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir',
            ],
            'two_factor' => [
                'title' => 'İki Faktörlü Doğrulama',
                'description' => 'Hesabınıza ekstra güvenlik katmanı ekleyin',
                'status' => 'Durum',
                'enabled' => 'Aktif',
                'disabled' => 'Pasif',
                'enable' => 'Etkinleştir',
                'disable' => 'Devre Dışı Bırak',
            ],
        ],
        'notifications' => [
            'email' => [
                'title' => 'E-posta Bildirimleri',
                'description' => 'Hangi e-postaları almak istediğinizi seçin',
                'updates' => 'Ürün güncellemeleri ve yenilikleri',
                'security' => 'Güvenlik uyarıları ve önemli bildirimler',
                'marketing' => 'Pazarlama ve promosyon e-postaları',
            ],
        ],
    ],
]; 