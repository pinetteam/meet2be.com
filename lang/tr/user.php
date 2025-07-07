<?php

return [
    'title' => 'Kullanıcılar',
    'subtitle' => 'Sistem kullanıcılarını ve izinlerini yönetin',
    
    // Eylemler
    'actions' => [
        'add' => 'Kullanıcı Ekle',
        'create' => 'Kullanıcı Oluştur',
        'edit' => 'Kullanıcı Düzenle',
        'update' => 'Kullanıcı Güncelle',
        'delete' => 'Kullanıcı Sil',
        'view' => 'Kullanıcı Görüntüle',
        'back_to_list' => 'Kullanıcılara Dön',
        'back_to_user' => 'Kullanıcıya Dön',
        'all_users' => 'Tüm Kullanıcılar',
        'add_first' => 'İlk Kullanıcıyı Ekle',
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
    ],
    
    // Bölümler
    'sections' => [
        'basic_info' => 'Temel Bilgiler',
        'account_info' => 'Hesap Bilgileri',
        'password_info' => 'Şifre Bilgileri',
        'permissions' => 'Kullanıcı İzinleri',
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
        'confirm_password' => 'Şifre Onayı',
        'new_password' => 'Yeni Şifre',
        'confirm_new_password' => 'Yeni Şifre Onayı',
        'user_type' => 'Kullanıcı Tipi',
        'status' => 'Durum',
        'tenant' => 'Kiracı',
        'tenant_id' => 'Kiracı ID',
        'user_id' => 'Kullanıcı ID',
        'last_login' => 'Son Giriş',
        'last_ip' => 'Son IP Adresi',
        'last_user_agent' => 'Son Kullanıcı Aracısı',
        'settings' => 'Kullanıcı Ayarları',
    ],
    
    // Kullanıcı Tipleri
    'types' => [
        'admin' => 'Yönetici',
        'screener' => 'Eleme Görevlisi',
        'operator' => 'Operatör',
    ],
    
    // Mesajlar
    'messages' => [
        'created_successfully' => 'Kullanıcı başarıyla oluşturuldu',
        'updated_successfully' => 'Kullanıcı başarıyla güncellendi',
        'deleted_successfully' => 'Kullanıcı başarıyla silindi',
        'delete_confirm' => 'Bu kullanıcıyı silmek istediğinizden emin misiniz?',
        'password_hint' => 'Mevcut şifreyi korumak için şifre alanlarını boş bırakın',
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