<?php return [
  'title' => 'Installatore di Laravel ',
  'next' => 'Passo successivo ',
  'back' => 'Precedente ',
  'finish' => 'Installazione ',
  'forms' => 
  [
    'errorTitle' => 'Si sono verificati i seguenti errori: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Benvenuto ',
    'title' => 'Installatore di Laravel ',
    'message' => 'Easy Installation and Setup Wizard. ',
    'next' => 'Verifica requisiti ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Passo 1 | Requisiti server ',
    'title' => 'Requisiti del server ',
    'next' => 'Autorizzazioni Controllo ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Fase 2 | Permissioni ',
    'title' => 'Autorizzazioni ',
    'next' => 'Configura ambiente ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Passo 3 | Impostazioni ambiente ',
      'title' => 'Impostazioni ambiente ',
      'desc' => 'Selezionare come si desidera configurare il file delle app <code>.env</code> . ',
      'wizard-button' => 'Impostazione guidata del modulo ',
      'classic-button' => 'Editor di testo classico ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Passo 3 | Impostazioni dell\'ambiente | Procedura guidata guidata ',
      'title' => 'Guidata <code>.env</code> guidato ',
      'tabs' => 
      [
        'environment' => 'Ambiente ',
        'database' => 'Database ',
        'application' => 'Applicazione ',
      ],
      'form' => 
      [
        'name_required' => 'È richiesto un nome ambiente. ',
        'app_name_label' => 'Nome app ',
        'app_name_placeholder' => 'Nome app ',
        'app_environment_label' => 'Ambiente app ',
        'app_environment_label_local' => 'Locale ',
        'app_environment_label_developement' => 'Sviluppo ',
        'app_environment_label_qa' => 'Qa ',
        'app_environment_label_production' => 'Produzione ',
        'app_environment_label_other' => 'Altro ',
        'app_environment_placeholder_other' => 'Entra nel tuo ambiente ... ',
        'app_debug_label' => 'Debug App ',
        'app_debug_label_true' => 'Vero ',
        'app_debug_label_false' => 'Falso ',
        'app_log_level_label' => 'Livello di registrazione delle app ',
        'app_log_level_label_debug' => 'debug ',
        'app_log_level_label_info' => 'info ',
        'app_log_level_label_notice' => 'avviso ',
        'app_log_level_label_warning' => 'avvertenza ',
        'app_log_level_label_error' => 'errore ',
        'app_log_level_label_critical' => 'critico ',
        'app_log_level_label_alert' => 'avviso ',
        'app_log_level_label_emergency' => 'emergenza ',
        'app_url_label' => 'App Url ',
        'app_url_placeholder' => 'App Url ',
        'db_connection_failed' => 'Impossibile connettersi al database. ',
        'db_connection_label' => 'Connessione al database ',
        'db_connection_label_mysql' => 'mysql ',
        'db_connection_label_sqlite' => 'sqlite ',
        'db_connection_label_pgsql' => 'pgsql ',
        'db_connection_label_sqlsrv' => 'sqlsrv ',
        'db_host_label' => 'Host database ',
        'db_host_placeholder' => 'Host database ',
        'db_port_label' => 'Porta database ',
        'db_port_placeholder' => 'Porta database ',
        'db_name_label' => 'Nome database ',
        'db_name_placeholder' => 'Nome database ',
        'db_username_label' => 'Nome utente database ',
        'db_username_placeholder' => 'Nome utente database ',
        'db_password_label' => 'Password del database ',
        'db_password_placeholder' => 'Password del database ',
        'app_tabs' => 
        [
          'more_info' => 'Più info ',
          'broadcasting_title' => 'Broadcasting, Caching, Sessione, &amp; coda ',
          'broadcasting_label' => 'Driver broadcast ',
          'broadcasting_placeholder' => 'Driver broadcast ',
          'cache_label' => 'Driver della cache ',
          'cache_placeholder' => 'Driver della cache ',
          'session_label' => 'Driver di sessione ',
          'session_placeholder' => 'Driver di sessione ',
          'queue_label' => 'Driver di coda ',
          'queue_placeholder' => 'Driver di coda ',
          'redis_label' => 'Driver Redis ',
          'redis_host' => 'Host Redis ',
          'redis_password' => 'Password Redis ',
          'redis_port' => 'Porta Redis ',
          'mail_label' => 'Posta ',
          'mail_driver_label' => 'Driver di posta ',
          'mail_driver_placeholder' => 'Driver di posta ',
          'mail_host_label' => 'Host di posta ',
          'mail_host_placeholder' => 'Host di posta ',
          'mail_port_label' => 'Porta di posta ',
          'mail_port_placeholder' => 'Porta di posta ',
          'mail_username_label' => 'Nome utente di posta ',
          'mail_username_placeholder' => 'Nome utente di posta ',
          'mail_password_label' => 'Password di posta ',
          'mail_password_placeholder' => 'Password di posta ',
          'mail_encryption_label' => 'Crittografia Posta ',
          'mail_encryption_placeholder' => 'Crittografia Posta ',
          'pusher_label' => 'Pusher ',
          'pusher_app_id_label' => 'Id app Pusher ',
          'pusher_app_id_palceholder' => 'Id app Pusher ',
          'pusher_app_key_label' => 'Chiave App pusher ',
          'pusher_app_key_palceholder' => 'Chiave App pusher ',
          'pusher_app_secret_label' => 'Pusher App Secret ',
          'pusher_app_secret_palceholder' => 'Pusher App Secret ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Database di setup ',
          'setup_application' => 'Applicazione di impostazione ',
          'install' => 'Installazione ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Passo 3 | Impostazioni ambiente | Classic Editor ',
      'title' => 'Editor di ambiente classico ',
      'save' => 'Salva .env ',
      'back' => 'Procedura guidata di utilizzo ',
      'install' => 'Salvataggio e installazione ',
    ],
    'success' => 'Le impostazioni del file .env sono state salvate. ',
    'errors' => 'Impossibile salvare il file .env, crearlo manualmente. ',
  ],
  'install' => 'Installazione ',
  'installed' => 
  [
    'success_log_message' => 'Laravel Installer correttamente INSTALLATO su ',
  ],
  'final' => 
  [
    'title' => 'Installazione Finita ',
    'templateTitle' => 'Installazione Finita ',
    'finished' => 'L\'applicazione è stata installata correttamente. ',
    'migration' => 'Output della migrazione &amp; Seed Console: ',
    'console' => 'Output della console di applicazione: ',
    'log' => 'Voce di registrazione installazione: ',
    'env' => 'File .env finale: ',
    'exit' => 'Clicca qui per uscire ',
  ],
  'updater' => 
  [
    'title' => 'Laravel Updater ',
    'welcome' => 
    [
      'title' => 'Benvenuti To The Updater ',
      'message' => 'Benvenuti nella procedura guidata di aggiornamento. ',
    ],
    'overview' => 
    [
      'title' => 'Panoramica ',
      'message' => 'Ci sono 1 update. | Ci sono: aggiornamenti numero. ',
      'install_updates' => 'Installazione Aggiornamenti ',
    ],
    'final' => 
    [
      'title' => 'Finito ',
      'finished' => 'Il database dell\'applicazione è stato aggiornato correttamente. ',
      'exit' => 'Clicca qui per uscire ',
    ],
    'log' => 
    [
      'success_message' => 'Laravel Installer correttamente UPDATA su',
    ],
  ],
];