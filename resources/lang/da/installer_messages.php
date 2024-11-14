<?php return [
  'title' => 'Laravel Installer ',
  'next' => 'Næste trin ',
  'back' => 'Forrige ',
  'finish' => 'Installation ',
  'forms' => 
  [
    'errorTitle' => 'Der er opstået følgende fejl: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Velkommen ',
    'title' => 'Laravel Installer ',
    'message' => 'Installation og konfiguration af installation og konfiguration. ',
    'next' => 'Kontrollér krav ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Trin 1 | Serverkrav ',
    'title' => 'Serverkrav ',
    'next' => 'Kontrollér tilladelser ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Trin 2 | Tilladelser ',
    'title' => 'Tilladelser ',
    'next' => 'Konfigurér miljø ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Trin 3 | Miljøindstillinger ',
      'title' => 'Systemindstillinger ',
      'desc' => 'Vælg, hvordan du vil konfigurere apperne <code>.env</code> -filen. ',
      'wizard-button' => 'Konfiguration af formularguide ',
      'classic-button' => 'Editor til klassisk tekst ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Trin 3 | Miljøindstillinger | Styret guide ',
      'title' => 'Guiden Styret <code>.env</code> ',
      'tabs' => 
      [
        'environment' => 'Miljø ',
        'database' => 'Database ',
        'application' => 'Ansøgning ',
      ],
      'form' => 
      [
        'name_required' => 'Der kræves et miljønavn. ',
        'app_name_label' => 'App-navn ',
        'app_name_placeholder' => 'App-navn ',
        'app_environment_label' => 'App-miljø ',
        'app_environment_label_local' => 'Lokal ',
        'app_environment_label_developement' => 'Udvikling ',
        'app_environment_label_qa' => 'Qa ',
        'app_environment_label_production' => 'Produktion ',
        'app_environment_label_other' => 'Andet ',
        'app_environment_placeholder_other' => 'Angiv dit miljø ... ',
        'app_debug_label' => 'App-fejlsøgning ',
        'app_debug_label_true' => 'Sandt. ',
        'app_debug_label_false' => 'Falsk ',
        'app_log_level_label' => 'App-logniveau ',
        'app_log_level_label_debug' => 'fejlfinding ',
        'app_log_level_label_info' => 'info ',
        'app_log_level_label_notice' => 'mærke ',
        'app_log_level_label_warning' => 'advarsel ',
        'app_log_level_label_error' => 'fejl ',
        'app_log_level_label_critical' => 'kritisk ',
        'app_log_level_label_alert' => 'alarm ',
        'app_log_level_label_emergency' => 'Nødsituation ',
        'app_url_label' => 'App-URL ',
        'app_url_placeholder' => 'App-URL ',
        'db_connection_failed' => 'Kan ikke oprette forbindelse til databasen. ',
        'db_connection_label' => 'Databaseforbindelse ',
        'db_connection_label_mysql' => 'mysql ',
        'db_connection_label_sqlite' => 'sqlite ',
        'db_connection_label_pgsql' => 'pgsql ',
        'db_connection_label_sqlsrv' => 'sqlsrv ',
        'db_host_label' => 'Databasevært ',
        'db_host_placeholder' => 'Databasevært ',
        'db_port_label' => 'Databaseport ',
        'db_port_placeholder' => 'Databaseport ',
        'db_name_label' => 'Databasenavn ',
        'db_name_placeholder' => 'Databasenavn ',
        'db_username_label' => 'Navn på databasebruger ',
        'db_username_placeholder' => 'Navn på databasebruger ',
        'db_password_label' => 'Databasekodeord ',
        'db_password_placeholder' => 'Databasekodeord ',
        'app_tabs' => 
        [
          'more_info' => 'Flere oplysninger ',
          'broadcasting_title' => 'Rundsendelse, Caching, Session og Kø ',
          'broadcasting_label' => 'Rundsend driver ',
          'broadcasting_placeholder' => 'Rundsend driver ',
          'cache_label' => 'Cachedriver ',
          'cache_placeholder' => 'Cachedriver ',
          'session_label' => 'Sessionsdriver ',
          'session_placeholder' => 'Sessionsdriver ',
          'queue_label' => 'Køstyreprogram ',
          'queue_placeholder' => 'Køstyreprogram ',
          'redis_label' => 'Redis Driver ',
          'redis_host' => 'Redis Vært ',
          'redis_password' => 'Redis-kodeord ',
          'redis_port' => 'Redis Port ',
          'mail_label' => 'E-mail ',
          'mail_driver_label' => 'Poststyreprogram ',
          'mail_driver_placeholder' => 'Poststyreprogram ',
          'mail_host_label' => 'Postvært ',
          'mail_host_placeholder' => 'Postvært ',
          'mail_port_label' => 'Postport ',
          'mail_port_placeholder' => 'Postport ',
          'mail_username_label' => 'Brugernavn for e-mail ',
          'mail_username_placeholder' => 'Brugernavn for e-mail ',
          'mail_password_label' => 'Postkodeord ',
          'mail_password_placeholder' => 'Postkodeord ',
          'mail_encryption_label' => 'E-mail-kryptering ',
          'mail_encryption_placeholder' => 'E-mail-kryptering ',
          'pusher_label' => 'Pusher ',
          'pusher_app_id_label' => 'Pusher-program-id ',
          'pusher_app_id_palceholder' => 'Pusher-program-id ',
          'pusher_app_key_label' => 'Pusher-app-nøgle ',
          'pusher_app_key_palceholder' => 'Pusher-app-nøgle ',
          'pusher_app_secret_label' => 'Pusher-app-hemmelighed ',
          'pusher_app_secret_palceholder' => 'Pusher-app-hemmelighed ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Opsætningsdatabase ',
          'setup_application' => 'Konfiguration af applikation ',
          'install' => 'Installation ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Trin 3 | Systemindstillinger | Klassisk editor ',
      'title' => 'Klassisk miljøeditor ',
      'save' => 'Gem .env ',
      'back' => 'Brug formularguide ',
      'install' => 'Gem og installér ',
    ],
    'success' => 'Dine .env-filindstillinger er gemt. ',
    'errors' => 'Kan ikke gemme .env-filen. Opret den manuelt. ',
  ],
  'install' => 'Installation ',
  'installed' => 
  [
    'success_log_message' => 'Laravel Installer er INSTALLED ',
  ],
  'final' => 
  [
    'title' => 'Installationen er afsluttet ',
    'templateTitle' => 'Installationen er afsluttet ',
    'finished' => 'Applikationen er installeret. ',
    'migration' => 'Migrering &amp; Seed Console-output: ',
    'console' => 'Output fra applikationskonsol: ',
    'log' => 'Indgang i installationslog: ',
    'env' => 'Endeligt .env-fil: ',
    'exit' => 'Klik her for at afslutte ',
  ],
  'updater' => 
  [
    'title' => 'Laravel Updater ',
    'welcome' => 
    [
      'title' => 'Velkommen til Updater ',
      'message' => 'Velkommen til opdateringsguiden. ',
    ],
    'overview' => 
    [
      'title' => 'Oversigt ',
      'message' => 'Der er 1 opdatering. | Der er: antal opdateringer. ',
      'install_updates' => 'Installationsopdateringer ',
    ],
    'final' => 
    [
      'title' => 'Afsluttet ',
      'finished' => 'Applications database er opdateret. ',
      'exit' => 'Klik her for at afslutte ',
    ],
    'log' => 
    [
      'success_message' => 'Laravel Installer er opdateret på',
    ],
  ],
];