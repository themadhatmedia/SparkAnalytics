<?php return [
  'title' => 'Laravel Kuruluş Programı ',
  'next' => 'Sonraki Adım ',
  'back' => 'Önceki ',
  'finish' => 'Kur ',
  'forms' => 
  [
    'errorTitle' => 'Aşağıdaki hatalar oluştu: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Hoş Geldiniz ',
    'title' => 'Laravel Kuruluş Programı ',
    'message' => 'Kolay Kuruluş ve Ayar Sihirbazı. ',
    'next' => 'Gereksinimleri Denetle ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Adım 1 | Sunucu Gereksinimleri ',
    'title' => 'Sunucu Gereksinimleri ',
    'next' => 'İzinleri Denetle ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Adım 2 | İzinler ',
    'title' => 'İzinler ',
    'next' => 'Ortamı Yapılandır ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Adım 3 | Ortam Ayarları ',
      'title' => 'Ortam Ayarları ',
      'desc' => 'Lütfen uygulamalarıconfigure.env</code> dosyasını nasıl yapılandırmak istediğinizi seçin. ',
      'wizard-button' => 'Form Sihirbazı Ayarları ',
      'classic-button' => 'Klasik Metin Düzenleyicisi ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Adım 3 | Ortam Ayarları | Kılavuzlu Sihirbaz ',
      'title' => 'Kılavuzlu <code>.env</code> Sihirbazı ',
      'tabs' => 
      [
        'environment' => 'Ortam ',
        'database' => 'Veritabanı ',
        'application' => 'Uygulama ',
      ],
      'form' => 
      [
        'name_required' => 'Bir ortam adı gerekli. ',
        'app_name_label' => 'Uygulama Adı ',
        'app_name_placeholder' => 'Uygulama Adı ',
        'app_environment_label' => 'Uygulama Ortamı ',
        'app_environment_label_local' => 'Yerel ',
        'app_environment_label_developement' => 'Geliştirme ',
        'app_environment_label_qa' => 'Ka ',
        'app_environment_label_production' => 'Üretim ',
        'app_environment_label_other' => 'Diğer ',
        'app_environment_placeholder_other' => 'Ortamınızı girin ... ',
        'app_debug_label' => 'Uygulama Hata Ayıklama ',
        'app_debug_label_true' => 'Doğru ',
        'app_debug_label_false' => 'Yanlış ',
        'app_log_level_label' => 'Uygulama Günlüğü Düzeyi ',
        'app_log_level_label_debug' => 'hata ayıklama ',
        'app_log_level_label_info' => 'bilgi ',
        'app_log_level_label_notice' => 'özel not ',
        'app_log_level_label_warning' => 'uyarı ',
        'app_log_level_label_error' => 'hata ',
        'app_log_level_label_critical' => 'kritik ',
        'app_log_level_label_alert' => 'uyarı ',
        'app_log_level_label_emergency' => 'acil durum ',
        'app_url_label' => 'Uygulama Url \'si ',
        'app_url_placeholder' => 'Uygulama Url \'si ',
        'db_connection_failed' => 'Veritabanına bağlanılamadı. ',
        'db_connection_label' => 'Veritabanı Bağlantısı ',
        'db_connection_label_mysql' => 'mysql ',
        'db_connection_label_sqlite' => 'sqlite ',
        'db_connection_label_pgsql' => 'pgsql ',
        'db_connection_label_sqlsrv' => 'sqlsrv ',
        'db_host_label' => 'Veritabanı Anasistemi ',
        'db_host_placeholder' => 'Veritabanı Anasistemi ',
        'db_port_label' => 'Veritabanı Kapısı ',
        'db_port_placeholder' => 'Veritabanı Kapısı ',
        'db_name_label' => 'Veritabanı Adı ',
        'db_name_placeholder' => 'Veritabanı Adı ',
        'db_username_label' => 'Veritabanı Kullanıcı Adı ',
        'db_username_placeholder' => 'Veritabanı Kullanıcı Adı ',
        'db_password_label' => 'Veritabanı Parolası ',
        'db_password_placeholder' => 'Veritabanı Parolası ',
        'app_tabs' => 
        [
          'more_info' => 'Ek Bilgi ',
          'broadcasting_title' => 'Yayın, Önbelleğe Alma, Oturum ve Kuyruk ',
          'broadcasting_label' => 'Yayın Sürücüsü ',
          'broadcasting_placeholder' => 'Yayın Sürücüsü ',
          'cache_label' => 'Önbellek Sürücüsü ',
          'cache_placeholder' => 'Önbellek Sürücüsü ',
          'session_label' => 'Oturum Sürücüsü ',
          'session_placeholder' => 'Oturum Sürücüsü ',
          'queue_label' => 'Kuyruk Sürücüsü ',
          'queue_placeholder' => 'Kuyruk Sürücüsü ',
          'redis_label' => 'Redis Sürücüsü ',
          'redis_host' => 'Redis Anasistemi ',
          'redis_password' => 'Redis Parolası ',
          'redis_port' => 'Redis Kapısı ',
          'mail_label' => 'Posta ',
          'mail_driver_label' => 'Posta Sürücüsü ',
          'mail_driver_placeholder' => 'Posta Sürücüsü ',
          'mail_host_label' => 'Posta Anasistemi ',
          'mail_host_placeholder' => 'Posta Anasistemi ',
          'mail_port_label' => 'Posta Kapısı ',
          'mail_port_placeholder' => 'Posta Kapısı ',
          'mail_username_label' => 'Posta Kullanıcı Adı ',
          'mail_username_placeholder' => 'Posta Kullanıcı Adı ',
          'mail_password_label' => 'Posta Parolası ',
          'mail_password_placeholder' => 'Posta Parolası ',
          'mail_encryption_label' => 'Posta Şifreleme ',
          'mail_encryption_placeholder' => 'Posta Şifreleme ',
          'pusher_label' => 'Pusher ',
          'pusher_app_id_label' => 'Pusher App Tanıtıcısı ',
          'pusher_app_id_palceholder' => 'Pusher App Tanıtıcısı ',
          'pusher_app_key_label' => 'Pusher App Anahtarı ',
          'pusher_app_key_palceholder' => 'Pusher App Anahtarı ',
          'pusher_app_secret_label' => 'Pusher App Gizli ',
          'pusher_app_secret_palceholder' => 'Pusher App Gizli ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Veritabanını Ayarla ',
          'setup_application' => 'Uygulamayı Ayarla ',
          'install' => 'Kur ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Adım 3 | Ortam Ayarları | Klasik Düzenleyici ',
      'title' => 'Klasik Ortam Düzenleyicisi ',
      'save' => 'Kaydet .env ',
      'back' => 'Form Sihirbazı Kullan ',
      'install' => 'Sakla ve Kur ',
    ],
    'success' => '.env dosya ayarlarınız kaydedildi. ',
    'errors' => '.env dosyası kaydedilemiyor, Lütfen el ile yaratın. ',
  ],
  'install' => 'Kur ',
  'installed' => 
  [
    'success_log_message' => 'Laravel Kuruluş Programı başarıyla KURULDU ',
  ],
  'final' => 
  [
    'title' => 'Kuruluş Bitti ',
    'templateTitle' => 'Kuruluş Bitti ',
    'finished' => 'Uygulama başarıyla kuruldu. ',
    'migration' => 'Geçiş ve Bırakılan Konsol Çıktısı: ',
    'console' => 'Uygulama Konsolu Çıktısı: ',
    'log' => 'Kuruluş Günlüğü Girdisi: ',
    'env' => 'Son .env Dosyası: ',
    'exit' => 'Çıkmak için burayı tıklatın ',
  ],
  'updater' => 
  [
    'title' => 'Larevel Güncelleyici ',
    'welcome' => 
    [
      'title' => 'Güncelleyiciye Hoş Geldiniz ',
      'message' => 'Güncelleme sihirbazına hoş geldiniz. ',
    ],
    'overview' => 
    [
      'title' => 'Genel Bakış ',
      'message' => '1 güncelleştirme var. | Var: number updates. ',
      'install_updates' => 'Güncelleştirmeleri Kur ',
    ],
    'final' => 
    [
      'title' => 'Bitti ',
      'finished' => 'Uygulamanın veritabanı başarıyla güncellendi. ',
      'exit' => 'Çıkmak için burayı tıklatın ',
    ],
    'log' => 
    [
      'success_message' => 'Laravel Kuruluş Programı başarıyla UPDATED:',
    ],
  ],
];