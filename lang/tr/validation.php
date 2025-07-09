<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları doğrulama sınıfı tarafından kullanılan varsayılan
    | hata mesajlarını içerir. Bu kuralların bazıları, boyut kuralları gibi
    | birden fazla sürüme sahiptir. Bu mesajların her birini burada dilediğiniz
    | gibi düzenleyebilirsiniz.
    |
    */

    'accepted' => ':attribute alanı kabul edilmelidir.',
    'accepted_if' => ':attribute alanı, :other :value olduğunda kabul edilmelidir.',
    'active_url' => ':attribute alanı geçerli bir URL olmalıdır.',
    'after' => ':attribute alanı :date tarihinden sonra bir tarih olmalıdır.',
    'after_or_equal' => ':attribute alanı :date tarihinden sonra veya aynı bir tarih olmalıdır.',
    'alpha' => ':attribute alanı sadece harflerden oluşmalıdır.',
    'alpha_dash' => ':attribute alanı sadece harfler, rakamlar, tireler ve alt çizgilerden oluşmalıdır.',
    'alpha_num' => ':attribute alanı sadece harfler ve rakamlardan oluşmalıdır.',
    'any_of' => ':attribute alanı geçersiz.',
    'array' => ':attribute alanı bir dizi olmalıdır.',
    'ascii' => ':attribute alanı yalnızca tek baytlık alfasayısal karakterler ve semboller içermelidir.',
    'before' => ':attribute alanı :date tarihinden önce bir tarih olmalıdır.',
    'before_or_equal' => ':attribute alanı :date tarihinden önce veya aynı bir tarih olmalıdır.',
    'between' => [
        'array' => ':attribute alanı :min ile :max arasında öğe içermelidir.',
        'file' => ':attribute alanı :min ile :max kilobayt arasında olmalıdır.',
        'numeric' => ':attribute alanı :min ile :max arasında olmalıdır.',
        'string' => ':attribute alanı :min ile :max karakter arasında olmalıdır.',
    ],
    'boolean' => ':attribute alanı doğru veya yanlış olmalıdır.',
    'can' => ':attribute alanı yetkisiz bir değer içeriyor.',
    'confirmed' => ':attribute alanı onayı eşleşmiyor.',
    'contains' => ':attribute alanı gerekli bir değeri içermiyor.',
    'current_password' => 'Şifre yanlış.',
    'date' => ':attribute alanı geçerli bir tarih olmalıdır.',
    'date_equals' => ':attribute alanı :date tarihine eşit bir tarih olmalıdır.',
    'date_format' => ':attribute alanı :format biçiminde olmalıdır.',
    'decimal' => ':attribute alanı :decimal ondalık basamak içermelidir.',
    'declined' => ':attribute alanı reddedilmelidir.',
    'declined_if' => ':attribute alanı, :other :value olduğunda reddedilmelidir.',
    'different' => ':attribute alanı ve :other farklı olmalıdır.',
    'digits' => ':attribute alanı :digits basamaklı olmalıdır.',
    'digits_between' => ':attribute alanı :min ile :max basamak arasında olmalıdır.',
    'dimensions' => ':attribute alanı geçersiz görüntü boyutlarına sahip.',
    'distinct' => ':attribute alanı yinelenen bir değere sahip.',
    'doesnt_end_with' => ':attribute alanı şunlardan biriyle bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute alanı şunlardan biriyle başlamamalıdır: :values.',
    'email' => ':attribute alanı geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ':attribute alanı şunlardan biriyle bitmelidir: :values.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'extensions' => ':attribute alanı şu uzantılardan birine sahip olmalıdır: :values.',
    'file' => ':attribute alanı bir dosya olmalıdır.',
    'filled' => ':attribute alanı bir değere sahip olmalıdır.',
    'gt' => [
        'array' => ':attribute alanı :value öğeden fazla içermelidir.',
        'file' => ':attribute alanı :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute alanı :value değerinden büyük olmalıdır.',
        'string' => ':attribute alanı :value karakterden büyük olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute alanı :value veya daha fazla öğe içermelidir.',
        'file' => ':attribute alanı :value kilobayt veya daha büyük olmalıdır.',
        'numeric' => ':attribute alanı :value veya daha büyük olmalıdır.',
        'string' => ':attribute alanı :value karakter veya daha büyük olmalıdır.',
    ],
    'hex_color' => ':attribute alanı geçerli bir onaltılık renk olmalıdır.',
    'image' => ':attribute alanı bir görüntü olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'in_array' => ':attribute alanı :other içinde bulunmalıdır.',
    'in_array_keys' => ':attribute alanı şu anahtarlardan en az birini içermelidir: :values.',
    'integer' => ':attribute alanı bir tam sayı olmalıdır.',
    'ip' => ':attribute alanı geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute alanı geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute alanı geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute alanı geçerli bir JSON dizesi olmalıdır.',
    'list' => ':attribute alanı bir liste olmalıdır.',
    'lowercase' => ':attribute alanı küçük harf olmalıdır.',
    'lt' => [
        'array' => ':attribute alanı :value öğeden az içermelidir.',
        'file' => ':attribute alanı :value kilobayttan küçük olmalıdır.',
        'numeric' => ':attribute alanı :value değerinden küçük olmalıdır.',
        'string' => ':attribute alanı :value karakterden küçük olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute alanı :value öğeden fazla içermemelidir.',
        'file' => ':attribute alanı :value kilobayt veya daha küçük olmalıdır.',
        'numeric' => ':attribute alanı :value veya daha küçük olmalıdır.',
        'string' => ':attribute alanı :value karakter veya daha küçük olmalıdır.',
    ],
    'mac_address' => ':attribute alanı geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute alanı :max öğeden fazla içermemelidir.',
        'file' => ':attribute alanı :max kilobayttan büyük olmamalıdır.',
        'numeric' => ':attribute alanı :max değerinden büyük olmamalıdır.',
        'string' => ':attribute alanı :max karakterden büyük olmamalıdır.',
    ],
    'max_digits' => ':attribute alanı :max basamaktan fazla içermemelidir.',
    'mimes' => ':attribute alanı şu türlerden biri olmalıdır: :values.',
    'mimetypes' => ':attribute alanı şu türlerden biri olmalıdır: :values.',
    'min' => [
        'array' => ':attribute alanı en az :min öğe içermelidir.',
        'file' => ':attribute alanı en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute alanı en az :min olmalıdır.',
        'string' => ':attribute alanı en az :min karakter olmalıdır.',
    ],
    'min_digits' => ':attribute alanı en az :min basamak içermelidir.',
    'missing' => ':attribute alanı eksik olmalıdır.',
    'missing_if' => ':attribute alanı, :other :value olduğunda eksik olmalıdır.',
    'missing_unless' => ':attribute alanı, :other :value olmadıkça eksik olmalıdır.',
    'missing_with' => ':attribute alanı, :values mevcut olduğunda eksik olmalıdır.',
    'missing_with_all' => ':attribute alanı, :values mevcut olduğunda eksik olmalıdır.',
    'multiple_of' => ':attribute alanı :value değerinin katı olmalıdır.',
    'not_in' => 'Seçilen :attribute geçersiz.',
    'not_regex' => ':attribute alanı biçimi geçersiz.',
    'numeric' => ':attribute alanı bir sayı olmalıdır.',
    'password' => [
        'letters' => ':attribute alanı en az bir harf içermelidir.',
        'mixed' => ':attribute alanı en az bir büyük harf ve bir küçük harf içermelidir.',
        'numbers' => ':attribute alanı en az bir rakam içermelidir.',
        'symbols' => ':attribute alanı en az bir sembol içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında görüldü. Lütfen farklı bir :attribute seçin.',
    ],
    'present' => ':attribute alanı mevcut olmalıdır.',
    'present_if' => ':attribute alanı, :other :value olduğunda mevcut olmalıdır.',
    'present_unless' => ':attribute alanı, :other :value olmadıkça mevcut olmalıdır.',
    'present_with' => ':attribute alanı, :values mevcut olduğunda mevcut olmalıdır.',
    'present_with_all' => ':attribute alanı, :values mevcut olduğunda mevcut olmalıdır.',
    'prohibited' => ':attribute alanı yasaktır.',
    'prohibited_if' => ':attribute alanı, :other :value olduğunda yasaktır.',
    'prohibited_if_accepted' => ':attribute alanı, :other kabul edildiğinde yasaktır.',
    'prohibited_if_declined' => ':attribute alanı, :other reddedildiğinde yasaktır.',
    'prohibited_unless' => ':attribute alanı, :other :values içinde olmadıkça yasaktır.',
    'prohibits' => ':attribute alanı :other alanının mevcut olmasını yasaklar.',
    'regex' => ':attribute alanı biçimi geçersiz.',
    'required' => ':attribute alanı zorunludur.',
    'required_array_keys' => ':attribute alanı şu girdileri içermelidir: :values.',
    'required_if' => ':attribute alanı, :other :value olduğunda zorunludur.',
    'required_if_accepted' => ':attribute alanı, :other kabul edildiğinde zorunludur.',
    'required_if_declined' => ':attribute alanı, :other reddedildiğinde zorunludur.',
    'required_unless' => ':attribute alanı, :other :values içinde olmadıkça zorunludur.',
    'required_with' => ':attribute alanı, :values mevcut olduğunda zorunludur.',
    'required_with_all' => ':attribute alanı, :values mevcut olduğunda zorunludur.',
    'required_without' => ':attribute alanı, :values mevcut olmadığında zorunludur.',
    'required_without_all' => ':attribute alanı, :values hiçbiri mevcut olmadığında zorunludur.',
    'same' => ':attribute alanı ile :other eşleşmelidir.',
    'size' => [
        'array' => ':attribute alanı :size öğe içermelidir.',
        'file' => ':attribute alanı :size kilobayt olmalıdır.',
        'numeric' => ':attribute alanı :size olmalıdır.',
        'string' => ':attribute alanı :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute alanı şunlardan biriyle başlamalıdır: :values.',
    'string' => ':attribute alanı bir metin olmalıdır.',
    'timezone' => ':attribute alanı geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute zaten kullanılmaktadır.',
    'uploaded' => ':attribute yüklenemedi.',
    'uppercase' => ':attribute alanı büyük harf olmalıdır.',
    'url' => ':attribute alanı geçerli bir URL olmalıdır.',
    'ulid' => ':attribute alanı geçerli bir ULID olmalıdır.',
    'uuid' => ':attribute alanı geçerli bir UUID olmalıdır.',
    
    // Özel doğrulama mesajları
    'phone_format' => 'Lütfen ülke kodu ile başlayan geçerli bir telefon numarası girin (örn: +905551234567).',
    'invalid_date_format' => 'Seçilen tarih formatı geçersiz.',
    'invalid_time_format' => 'Seçilen saat formatı geçersiz.',

    /*
    |--------------------------------------------------------------------------
    | Özel Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Burada nitelikler için özel doğrulama mesajları belirtebilirsiniz.
    | "attribute.rule" kuralını kullanarak satırları adlandırın. Bu, belirli
    | bir nitelik kuralı için hızlı bir şekilde özel bir dil satırı
    | belirtmenizi sağlar.
    |
    */

    'errors_occurred' => 'Lütfen aşağıdaki hataları düzeltin:',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'özel-mesaj',
        ],
        'tenant' => [
            'name_required' => 'Organizasyon adı zorunludur.',
            'email_required' => 'İletişim e-postası zorunludur.',
            'email_invalid' => 'Lütfen geçerli bir e-posta adresi girin.',
            'phone_format' => 'Lütfen geçerli bir telefon numarası girin.',
            'website_format' => 'Lütfen geçerli bir web sitesi URL\'si girin (örn: https://www.example.com veya www.example.com).',
            'language_required' => 'Lütfen bir dil seçin.',
            'timezone_required' => 'Lütfen bir saat dilimi seçin.',
            'date_format_required' => 'Lütfen bir tarih formatı seçin.',
            'date_format_invalid' => 'Geçersiz tarih formatı seçildi.',
            'time_format_required' => 'Lütfen bir saat formatı seçin.',
            'time_format_invalid' => 'Geçersiz saat formatı seçildi.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Özel Doğrulama Nitelikleri
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları, yer tutucuyu "E-posta Adresi" gibi daha okunaklı
    | bir şeyle değiştirmek için kullanılır. Bu, mesajımızı daha açıklayıcı
    | hale getirmemize yardımcı olur.
    |
    */

    'attributes' => [],

]; 