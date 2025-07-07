<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kimlik Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları kimlik doğrulama sırasında kullanıcıya göstermemiz
    | gereken çeşitli mesajlar için kullanılır. Bu dil satırlarını uygulamanızın
    | gereksinimlerine göre değiştirmekte özgürsünüz.
    |
    */

    'failed' => 'Bu kimlik bilgileri kayıtlarımızla eşleşmiyor.',
    'password' => 'Girilen şifre yanlış.',
    'throttle' => 'Çok fazla giriş denemesi. Lütfen :seconds saniye sonra tekrar deneyin.',

    // Giriş
    'login' => [
        'title' => 'Giriş Yap',
        'heading' => 'Hesabınıza giriş yapın',
        'email' => 'E-posta Adresi',
        'password' => 'Şifre',
        'remember' => 'Beni hatırla',
        'forgot' => 'Şifrenizi mi unuttunuz?',
        'submit' => 'Giriş Yap',
        'no_account' => 'Hesabınız yok mu?',
        'register_link' => 'Kayıt olun',
    ],
    
    // Kayıt
    'register' => [
        'title' => 'Kayıt Ol',
        'heading' => 'Yeni hesap oluşturun',
        'name' => 'Ad Soyad',
        'email' => 'E-posta Adresi',
        'password' => 'Şifre',
        'confirm_password' => 'Şifre Onayı',
        'terms' => 'Kullanım koşullarını kabul ediyorum',
        'submit' => 'Kayıt Ol',
        'have_account' => 'Zaten hesabınız var mı?',
        'login_link' => 'Giriş yapın',
    ],
    
    // Şifre sıfırlama
    'reset' => [
        'title' => 'Şifre Sıfırlama',
        'heading' => 'Şifrenizi sıfırlayın',
        'email' => 'E-posta Adresi',
        'password' => 'Yeni Şifre',
        'confirm_password' => 'Şifre Onayı',
        'submit' => 'Şifreyi Sıfırla',
        'send_link' => 'Sıfırlama Bağlantısı Gönder',
    ],
    
    // E-posta doğrulama
    'verify' => [
        'title' => 'E-posta Doğrulama',
        'heading' => 'E-posta adresinizi doğrulayın',
        'sent' => 'Doğrulama bağlantısı e-posta adresinize gönderildi.',
        'check' => 'Devam etmeden önce lütfen e-postanızı kontrol edin.',
        'not_received' => 'E-postayı almadınız mı?',
        'resend' => 'Tekrar gönder',
    ],
    
    // Mesajlar
    'messages' => [
        'logged_in' => 'Başarıyla giriş yaptınız.',
        'logged_out' => 'Başarıyla çıkış yaptınız.',
        'registered' => 'Hesabınız başarıyla oluşturuldu.',
        'verified' => 'E-posta adresiniz doğrulandı.',
        'reset_sent' => 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.',
        'reset_success' => 'Şifreniz başarıyla sıfırlandı.',
        'invalid_token' => 'Geçersiz veya süresi dolmuş bağlantı.',
    ],
]; 