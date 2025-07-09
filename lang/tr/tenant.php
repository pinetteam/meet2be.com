<?php

return [
    'title' => 'Kiracı Yönetimi',
    'singular' => 'Kiracı',
    'plural' => 'Kiracılar',
    
    // Tipler
    'types' => [
        'individual' => 'Bireysel',
        'business' => 'İşletme',
        'enterprise' => 'Kurumsal',
    ],
    
    // Durumlar
    'statuses' => [
        'active' => 'Aktif',
        'inactive' => 'Pasif',
        'suspended' => 'Askıya Alınmış',
        'expired' => 'Süresi Dolmuş',
        'trial' => 'Deneme',
    ],
    
    // Planlar
    'plans' => [
        'basic' => 'Temel',
        'pro' => 'Profesyonel',
        'enterprise' => 'Kurumsal',
    ],
    
    // Alanlar
    'fields' => [
        'name' => 'İsim',
        'code' => 'Kod',
        'type' => 'Tip',
        'status' => 'Durum',
        'plan' => 'Plan',
        'email' => 'E-posta',
        'phone' => 'Telefon',
        'created_at' => 'Oluşturulma Tarihi',
        'legal_name' => 'Yasal Adı',
        'tax_number' => 'Vergi Numarası',
        'tax_office' => 'Vergi Dairesi',
        'owner' => 'Sahip',
        'subscription_plan' => 'Abonelik Planı',
        'subscription_starts_at' => 'Abonelik Başlangıcı',
        'subscription_ends_at' => 'Abonelik Bitişi',
        'trial_ends_at' => 'Deneme Süresi Bitişi',
        'user_limit' => 'Kullanıcı Limiti',
        'event_limit' => 'Etkinlik Limiti',
        'storage_limit' => 'Depolama Limiti',
        'current_users' => 'Mevcut Kullanıcılar',
        'current_events' => 'Mevcut Etkinlikler',
        'current_storage' => 'Kullanılan Depolama',
    ],
    
    // İşlemler
    'actions' => [
        'create' => 'Kiracı Oluştur',
        'edit' => 'Kiracı Düzenle',
        'delete' => 'Kiracı Sil',
        'view' => 'Kiracı Görüntüle',
    ],
    
    // Mesajlar
    'messages' => [
        'created' => 'Kiracı başarıyla oluşturuldu.',
        'updated' => 'Kiracı başarıyla güncellendi.',
        'deleted' => 'Kiracı başarıyla silindi.',
        'not_found' => 'Kiracı bulunamadı.',
        'access_denied' => 'Bu kiracıya erişim yetkiniz yok',
        'subscription_expired' => 'Abonelik süresi dolmuş',
        'trial_expired' => 'Deneme süresi dolmuş',
        'limit_exceeded' => 'Limit aşıldı',
    ],
    
    // Limitler
    'limits' => [
        'users' => 'Kullanıcı',
        'events' => 'Etkinlik',
        'storage' => 'GB Depolama',
        'unlimited' => 'Sınırsız',
    ],
]; 