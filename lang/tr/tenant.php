<?php

return [
    'title' => 'Kiracılar',
    'subtitle' => 'Kiracı hesaplarını ve aboneliklerini yönetin',
    
    // Durumlar
    'statuses' => [
        'active' => 'Aktif',
        'trial' => 'Deneme',
        'suspended' => 'Askıya Alınmış',
        'cancelled' => 'İptal Edilmiş',
        'expired' => 'Süresi Dolmuş',
    ],
    
    // Alanlar
    'fields' => [
        'name' => 'Kiracı Adı',
        'code' => 'Kiracı Kodu',
        'legal_name' => 'Yasal Adı',
        'tax_number' => 'Vergi Numarası',
        'tax_office' => 'Vergi Dairesi',
        'owner' => 'Sahip',
        'status' => 'Durum',
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
    
    // Mesajlar
    'messages' => [
        'not_found' => 'Kiracı bulunamadı veya erişim yetkiniz yok',
        'access_denied' => 'Bu kiracıya erişim yetkiniz yok',
        'subscription_expired' => 'Abonelik süresi dolmuş',
        'trial_expired' => 'Deneme süresi dolmuş',
        'suspended' => 'Kiracı hesabı askıya alınmış',
        'limit_exceeded' => 'Limit aşıldı',
        'created_successfully' => 'Kiracı başarıyla oluşturuldu',
        'updated_successfully' => 'Kiracı başarıyla güncellendi',
        'deleted_successfully' => 'Kiracı başarıyla silindi',
    ],
    
    // Limitler
    'limits' => [
        'users' => 'Kullanıcı',
        'events' => 'Etkinlik',
        'storage' => 'GB Depolama',
        'unlimited' => 'Sınırsız',
    ],
]; 