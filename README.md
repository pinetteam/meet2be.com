# meet2be.com

## Proje Tanımı
meet2be.com, çoklu tenant destekli, yüksek güvenlikli ve performans odaklı bir etkinlik yönetim platformudur. Kod kalitesi, mimari ve sürdürülebilirlikte Fortune 500 standartlarını hedefler.

Git yüklemeleri sonrası sunucu otomatik eşitler.

## Teknoloji Yığını
- **Backend:** Laravel 12 (PHP 8.3+)
- **Frontend:** Tailwind CSS, Alpine.js 3.x, FontAwesome Pro
- **Veritabanı:** MySQL 8.0+ veya PostgreSQL 15+
- **Önbellek:** Redis
- **Build:** Vite
- **Test:** PHPUnit

## Klasör Yapısı
```
app/                # Uygulama çekirdeği (Controller, Model, Service, Trait)
database/           # Migration, Seeder, Factory
docs/               # Dokümantasyon (varsa)
lang/               # Çoklu dil dosyaları (en, tr, ...)
public/             # Web sunucu kök dizini
resources/          # Blade, JS, CSS, görseller
routes/             # Route tanımları (site.php, portal.php, ...)
tests/              # Testler (Feature, Unit)
```

## Kurulum
1. Depoyu klonlayın:
   ```bash
   git clone https://github.com/kullanici/meet2be.com.git
   cd meet2be.com
   ```
2. Bağımlılıkları yükleyin:
   ```bash
   composer install
   npm install
   ```
3. Ortam dosyasını oluşturun:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Veritabanı ayarlarını yapın ve migrasyonları çalıştırın:
   ```bash
   php artisan migrate --seed
   ```
5. Geliştirme sunucusunu başlatın:
   ```bash
   npm run dev
   php artisan serve
   ```

## Kullanım
- Giriş: `/login`
- Portal: `/portal`
- Çoklu dil desteği: Türkçe ve İngilizce
- Tüm CRUD işlemleri otomatik resource controller ile yönetilir.

## Kod Standartları
- **Fortress Standard:** Sıfır teknik borç, tek sorumluluk, DRY/KISS, kendini açıklayan kod
- **UUIDv7:** Tüm anahtarlar UUIDv7 ile yönetilir
- **Yorum Yok:** Kod kendini açıklar, yorum eklenmez
- **Katı dizin ve isimlendirme standartları**
- **Banned:** Livewire, Inertia, React, Vue, Bootstrap, jQuery, custom CSS

## Katkı
1. Fork'la ve yeni bir branch oluştur
2. Kodunu Fortress Standard'a uygun yaz
3. Testleri çalıştır: `php artisan test`
4. Pull request aç

## Lisans
Bu proje MIT lisansı ile lisanslanmıştır.
