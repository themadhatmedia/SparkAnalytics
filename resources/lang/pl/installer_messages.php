<?php return [
  'title' => 'Instalator laravel ',
  'next' => 'Następny krok ',
  'back' => 'Poprzedni ',
  'finish' => 'Instaluj ',
  'forms' => 
  [
    'errorTitle' => 'Wystąpiły następujące błędy: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Powitanie ',
    'title' => 'Instalator laravel ',
    'message' => 'Łatwy kreator instalacji i konfiguracji. ',
    'next' => 'Sprawdź wymagania ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Krok 1 | Wymagania serwerowe ',
    'title' => 'Wymagania serwera ',
    'next' => 'Sprawdź uprawnienia ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Krok 2 | Uprawnienia ',
    'title' => 'Uprawnienia ',
    'next' => 'Konfigurowanie środowiska ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Krok 3 | Ustawienia środowiska ',
      'title' => 'Ustawienia środowiska ',
      'desc' => 'Wybierz sposób konfigurowania pliku <code>.env</code> aplikacji. ',
      'wizard-button' => 'Konfiguracja kreatora formularzy ',
      'classic-button' => 'Klasyczny edytor tekstu ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Krok 3 | Ustawienia środowiska | Guided Wizard ',
      'title' => 'Kreator <code>.env</code> z przewodnikiem ',
      'tabs' => 
      [
        'environment' => 'Środowisko ',
        'database' => 'Baza danych ',
        'application' => 'Aplikacja ',
      ],
      'form' => 
      [
        'name_required' => 'Wymagana jest nazwa środowiska. ',
        'app_name_label' => 'Nazwa aplikacji ',
        'app_name_placeholder' => 'Nazwa aplikacji ',
        'app_environment_label' => 'Środowisko aplikacji ',
        'app_environment_label_local' => 'Lokalne ',
        'app_environment_label_developement' => 'Programowanie ',
        'app_environment_label_qa' => 'Qa ',
        'app_environment_label_production' => 'Produkcja ',
        'app_environment_label_other' => 'Inne ',
        'app_environment_placeholder_other' => 'Wprowadź swoje środowisko ... ',
        'app_debug_label' => 'Debugowanie aplikacji ',
        'app_debug_label_true' => 'Prawda ',
        'app_debug_label_false' => 'Fałsz ',
        'app_log_level_label' => 'Poziom dziennika aplikacji ',
        'app_log_level_label_debug' => 'debugowanie ',
        'app_log_level_label_info' => 'informacja ',
        'app_log_level_label_notice' => 'powiadomienie ',
        'app_log_level_label_warning' => 'ostrzeżenie ',
        'app_log_level_label_error' => 'błąd ',
        'app_log_level_label_critical' => 'krytyczne ',
        'app_log_level_label_alert' => 'alert ',
        'app_log_level_label_emergency' => 'stan awaryjny ',
        'app_url_label' => 'Adres URL aplikacji ',
        'app_url_placeholder' => 'Adres URL aplikacji ',
        'db_connection_failed' => 'Nie można nawiązać połączenia z bazą danych. ',
        'db_connection_label' => 'Połączenie bazy danych ',
        'db_connection_label_mysql' => 'mysql ',
        'db_connection_label_sqlite' => 'sqlite ',
        'db_connection_label_pgsql' => 'pgsql ',
        'db_connection_label_sqlsrv' => 'sqlsrv ',
        'db_host_label' => 'Host bazy danych ',
        'db_host_placeholder' => 'Host bazy danych ',
        'db_port_label' => 'Port bazy danych ',
        'db_port_placeholder' => 'Port bazy danych ',
        'db_name_label' => 'Nazwa bazy danych ',
        'db_name_placeholder' => 'Nazwa bazy danych ',
        'db_username_label' => 'Nazwa użytkownika bazy danych ',
        'db_username_placeholder' => 'Nazwa użytkownika bazy danych ',
        'db_password_label' => 'Hasło bazy danych ',
        'db_password_placeholder' => 'Hasło bazy danych ',
        'app_tabs' => 
        [
          'more_info' => 'Więcej informacji ',
          'broadcasting_title' => 'Rozgłaszanie, buforowanie, sesja i kolejka ',
          'broadcasting_label' => 'Sterownik rozgłaszania ',
          'broadcasting_placeholder' => 'Sterownik rozgłaszania ',
          'cache_label' => 'Sterownik pamięci podręcznej ',
          'cache_placeholder' => 'Sterownik pamięci podręcznej ',
          'session_label' => 'Sterownik sesji ',
          'session_placeholder' => 'Sterownik sesji ',
          'queue_label' => 'Sterownik kolejki ',
          'queue_placeholder' => 'Sterownik kolejki ',
          'redis_label' => 'Sterownik Redis ',
          'redis_host' => 'Host redis ',
          'redis_password' => 'Redis-Hasło ',
          'redis_port' => 'Port redis ',
          'mail_label' => 'Poczta ',
          'mail_driver_label' => 'Sterownik poczty elektronicznej ',
          'mail_driver_placeholder' => 'Sterownik poczty elektronicznej ',
          'mail_host_label' => 'Host poczty elektronicznej ',
          'mail_host_placeholder' => 'Host poczty elektronicznej ',
          'mail_port_label' => 'Port poczty elektronicznej ',
          'mail_port_placeholder' => 'Port poczty elektronicznej ',
          'mail_username_label' => 'Nazwa użytkownika poczty ',
          'mail_username_placeholder' => 'Nazwa użytkownika poczty ',
          'mail_password_label' => 'Hasło poczty elektronicznej ',
          'mail_password_placeholder' => 'Hasło poczty elektronicznej ',
          'mail_encryption_label' => 'Szyfrowanie poczty ',
          'mail_encryption_placeholder' => 'Szyfrowanie poczty ',
          'pusher_label' => 'Pusher ',
          'pusher_app_id_label' => 'Identyfikator aplikacji pusher ',
          'pusher_app_id_palceholder' => 'Identyfikator aplikacji pusher ',
          'pusher_app_key_label' => 'Klawisz aplikacji pusher ',
          'pusher_app_key_palceholder' => 'Klawisz aplikacji pusher ',
          'pusher_app_secret_label' => 'Pusher App Secret ',
          'pusher_app_secret_palceholder' => 'Pusher App Secret ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Konfiguracja bazy danych ',
          'setup_application' => 'Aplikacja konfiguruj ',
          'install' => 'Instaluj ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Krok 3 | Ustawienia środowiska | edytor klasyczny ',
      'title' => 'Klasyczny edytor środowiska ',
      'save' => 'Zapisz plik .env ',
      'back' => 'Użyj kreatora formularzy ',
      'install' => 'Zapisz i zainstaluj ',
    ],
    'success' => 'Ustawienia pliku .env zostały zapisane. ',
    'errors' => 'Nie można zapisać pliku .env. Utwórz go ręcznie. ',
  ],
  'install' => 'Instaluj ',
  'installed' => 
  [
    'success_log_message' => 'Instalator laravel został pomyślnie zainstalowany ',
  ],
  'final' => 
  [
    'title' => 'Instalacja została zakończona ',
    'templateTitle' => 'Instalacja została zakończona ',
    'finished' => 'Aplikacja została pomyślnie zainstalowana. ',
    'migration' => 'Dane wyjściowe konsoli migracji i adresów początkowych: ',
    'console' => 'Dane wyjściowe konsoli aplikacji: ',
    'log' => 'Pozycja dziennika instalacji: ',
    'env' => 'Plik finał.env: ',
    'exit' => 'Kliknij tutaj, aby wyjść ',
  ],
  'updater' => 
  [
    'title' => 'Program aktualizujący laravel ',
    'welcome' => 
    [
      'title' => 'Witamy W Aktualizatorze ',
      'message' => 'Witamy w kreatorze aktualizacji. ',
    ],
    'overview' => 
    [
      'title' => 'Przegląd ',
      'message' => 'Istnieje 1 aktualizacja. | Istnieją: liczba aktualizacji. ',
      'install_updates' => 'Zainstaluj aktualizacje ',
    ],
    'final' => 
    [
      'title' => 'Zakończone ',
      'finished' => 'Baza danych aplikacji została pomyślnie zaktualizowana. ',
      'exit' => 'Kliknij tutaj, aby wyjść ',
    ],
    'log' => 
    [
      'success_message' => 'Instalator laravel pomyślnie zaktualizował',
    ],
  ],
];