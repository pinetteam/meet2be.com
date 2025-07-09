<?php

return [
    'title' => 'Kullanıcılar',
    'subtitle' => 'Sistem kullanıcılarını ve izinlerini yönetin',
    'create_title' => 'Kullanıcı Ekle',
    'create_subtitle' => 'Yeni bir sistem kullanıcısı oluşturun',
    'edit_title' => 'Kullanıcı Düzenle',
    'edit_subtitle' => ':name kullanıcısının bilgilerini güncelleyin',
    'show_title' => 'Kullanıcı Detayı',
    
    // Eylemler
    'actions' => [
        'add' => 'Kullanıcı Ekle',
        'create' => 'Kullanıcı Oluştur',
        'edit' => 'Düzenle',
        'update' => 'Güncelle',
        'delete' => 'Sil',
        'view' => 'Görüntüle',
        'back_to_list' => 'Kullanıcılara Dön',
        'back_to_user' => 'Kullanıcıya Dön',
        'all_users' => 'Tüm Kullanıcılar',
        'add_first' => 'İlk Kullanıcıyı Ekle',
        'add_first_user' => 'İlk Kullanıcıyı Ekle',
    ],
    
    // Etiketler
    'labels' => [
        'details' => 'Kullanıcı Detayları',
        'create_new' => 'Yeni bir sistem kullanıcısı oluşturun',
        'update_info' => 'Kullanıcı bilgilerini güncelleyin',
        'view_info' => 'Kullanıcı bilgilerini görüntüleyin',
        'no_users' => 'Kullanıcı bulunamadı',
        'adjust_filters' => 'Arama veya filtre kriterlerinizi ayarlamayı deneyin',
        'search_placeholder' => 'Ad, e-posta, kullanıcı adı...',
        'all_types' => 'Tüm Tipler',
        'all_statuses' => 'Tüm Durumlar',
        'select_type' => 'Kullanıcı tipi seçin',
        'select_status' => 'Durum seçin',
        'select_tenant' => 'Bir kiracı seçin',
        'no_tenant' => 'Kiracı Yok',
        'never_logged' => 'Hiç giriş yapmadı',
        'never_updated' => 'Güncelleme yapılmadı',
        'verified' => 'Doğrulanmış',
        'never' => 'Hiç',
        'type' => 'Tip',
        'status' => 'Durum',
        'last_login' => 'Son Giriş',
        'showing' => 'Gösterilen',
        'to' => '-',
        'of' => 'toplam',
        'results' => 'sonuç',
    ],
    
    // Bölümler
    'sections' => [
        'basic_info' => 'Temel Bilgiler',
        'account_info' => 'Hesap Bilgileri',
        'password' => 'Şifre',
        'password_info' => 'Şifre Bilgileri',
        'permissions' => 'İzinler',
        'login_activity' => 'Giriş Aktivitesi',
        'system_info' => 'Sistem Bilgileri',
    ],
    
    // Alanlar
    'fields' => [
        'first_name' => 'Ad',
        'last_name' => 'Soyad',
        'username' => 'Kullanıcı Adı',
        'email' => 'E-posta',
        'phone' => 'Telefon',
        'password' => 'Şifre',
        'password_confirmation' => 'Şifre Onayı',
        'confirm_password' => 'Şifre Onayı',
        'new_password' => 'Yeni Şifre',
        'confirm_new_password' => 'Yeni Şifre Onayı',
        'type' => 'Kullanıcı Tipi',
        'user_type' => 'Kullanıcı Tipi',
        'status' => 'Durum',
        'tenant' => 'Kiracı',
        'tenant_id' => 'Kiracı ID',
        'user_id' => 'Kullanıcı ID',
        'last_login' => 'Son Giriş',
        'last_ip' => 'Son IP Adresi',
        'last_user_agent' => 'Son Kullanıcı Aracısı',
        'settings' => 'Kullanıcı Ayarları',
        'created_at' => 'Oluşturulma Tarihi',
        'updated_at' => 'Güncellenme Tarihi',
    ],
    
    // Placeholders
    'placeholders' => [
        'first_name' => 'Adınızı girin',
        'last_name' => 'Soyadınızı girin',
        'username' => 'Kullanıcı adı girin',
        'email' => 'E-posta adresi girin',
        'phone' => 'Telefon numarası girin',
        'password' => 'Güçlü bir şifre girin',
        'password_confirmation' => 'Şifreyi tekrar girin',
        'new_password' => 'Yeni şifre girin',
        'select_type' => 'Tip seçin',
        'select_status' => 'Durum seçin',
    ],
    
    // İpuçları
    'hints' => [
        'username' => 'Benzersiz olmalıdır, boşluk içeremez',
        'password' => 'En az 8 karakter, büyük/küçük harf, rakam ve özel karakter içermelidir',
        'password_update' => 'Mevcut şifreyi korumak için şifre alanlarını boş bırakın',
    ],
    
    // Kullanıcı Tipleri
    'types' => [
        'admin' => 'Yönetici',
        'screener' => 'Eleme Görevlisi',
        'operator' => 'Operatör',
    ],
    
    // Kullanıcı Durumları
    'statuses' => [
        'active' => 'Aktif',
        'inactive' => 'Pasif',
        'suspended' => 'Askıya Alınmış',
    ],
    
    // Mesajlar
    'messages' => [
        'created_successfully' => 'Kullanıcı başarıyla oluşturuldu',
        'updated_successfully' => 'Kullanıcı başarıyla güncellendi',
        'deleted_successfully' => 'Kullanıcı başarıyla silindi',
        'delete_confirm' => 'Bu kullanıcıyı silmek istediğinizden emin misiniz?',
        'password_hint' => 'Mevcut şifreyi korumak için şifre alanlarını boş bırakın',
        'confirm_delete' => 'Bu kullanıcıyı silmek istediğinizden emin misiniz?',
        'no_users_found' => 'Kullanıcı bulunamadı',
        'try_adjusting_search_or_filter_criteria' => 'Arama veya filtre kriterlerinizi ayarlamayı deneyin',
    ],
    
    // Tablo başlıkları
    'table' => [
        'user' => 'Kullanıcı',
        'type' => 'Tip',
        'status' => 'Durum',
        'last_login' => 'Son Giriş',
        'actions' => 'İşlemler',
    ],
]; 