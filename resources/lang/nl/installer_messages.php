<?php return [
  'title' => 'Laravel-installateur ',
  'next' => 'Volgende stap ',
  'back' => 'Vorige ',
  'finish' => 'Installeren ',
  'forms' => 
  [
    'errorTitle' => 'De volgende fouten zijn opgetreden: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Welkom ',
    'title' => 'Laravel-installateur ',
    'message' => 'Easy Installation and Setup Wizard. ',
    'next' => 'Controlevereisten ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Stap 1 | Serververeisten ',
    'title' => 'Serververeisten ',
    'next' => 'Machtigingen controleren ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Stap 2 | Machtigingen ',
    'title' => 'Machtigingen ',
    'next' => 'Omgeving configureren ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Stap 3 | Omgevingsinstellingen ',
      'title' => 'Omgevingsinstellingen ',
      'desc' => 'Selecteer hoe u het bestandapps.env</code> wilt configureren. ',
      'wizard-button' => 'Setup formulierwizard ',
      'classic-button' => 'Klassieke teksteditor ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Stap 3 | Omgevingsinstellingen | Geleide wizard ',
      'title' => 'BegeleideWizard.env</code> -wizard ',
      'tabs' => 
      [
        'environment' => 'Milieu ',
        'database' => 'Database ',
        'application' => 'Toepassing ',
      ],
      'form' => 
      [
        'name_required' => 'Er is een omgevingsnaam vereist. ',
        'app_name_label' => 'App-naam ',
        'app_name_placeholder' => 'App-naam ',
        'app_environment_label' => 'App-omgeving ',
        'app_environment_label_local' => 'Lokaal ',
        'app_environment_label_developement' => 'Ontwikkeling ',
        'app_environment_label_qa' => 'Qa ',
        'app_environment_label_production' => 'Productie ',
        'app_environment_label_other' => 'Andere ',
        'app_environment_placeholder_other' => 'Voer uw omgeving in ... ',
        'app_debug_label' => 'Foutopsporing ',
        'app_debug_label_true' => 'Waar ',
        'app_debug_label_false' => 'Onwaar ',
        'app_log_level_label' => 'Niveau applogboek ',
        'app_log_level_label_debug' => 'fouten opsporen ',
        'app_log_level_label_info' => 'info ',
        'app_log_level_label_notice' => 'bericht ',
        'app_log_level_label_warning' => 'waarschuwing ',
        'app_log_level_label_error' => 'fout ',
        'app_log_level_label_critical' => 'kritisch ',
        'app_log_level_label_alert' => 'alertsignaal ',
        'app_log_level_label_emergency' => 'Noodgeval ',
        'app_url_label' => 'App-url ',
        'app_url_placeholder' => 'App-url ',
        'db_connection_failed' => 'Er kan geen verbinding worden gemaakt met de database. ',
        'db_connection_label' => 'Databaseverbinding ',
        'db_connection_label_mysql' => 'mysql ',
        'db_connection_label_sqlite' => 'sqlite ',
        'db_connection_label_pgsql' => 'pgsql ',
        'db_connection_label_sqlsrv' => 'sqlsrv ',
        'db_host_label' => 'Databasehost ',
        'db_host_placeholder' => 'Databasehost ',
        'db_port_label' => 'Databasepoort ',
        'db_port_placeholder' => 'Databasepoort ',
        'db_name_label' => 'Databasenaam ',
        'db_name_placeholder' => 'Databasenaam ',
        'db_username_label' => 'Gebruikersnaam database ',
        'db_username_placeholder' => 'Gebruikersnaam database ',
        'db_password_label' => 'Databasewachtwoord ',
        'db_password_placeholder' => 'Databasewachtwoord ',
        'app_tabs' => 
        [
          'more_info' => 'Meer info ',
          'broadcasting_title' => 'Uitzending, Caching, Sessie, &amp; wachtrij ',
          'broadcasting_label' => 'Broadcaststuurprogramma ',
          'broadcasting_placeholder' => 'Broadcaststuurprogramma ',
          'cache_label' => 'Cachestuurprogramma ',
          'cache_placeholder' => 'Cachestuurprogramma ',
          'session_label' => 'Sessiestuurprogramma ',
          'session_placeholder' => 'Sessiestuurprogramma ',
          'queue_label' => 'Wachtrijstuurprogramma ',
          'queue_placeholder' => 'Wachtrijstuurprogramma ',
          'redis_label' => 'Redis-stuurprogramma ',
          'redis_host' => 'Redis-host ',
          'redis_password' => 'Redis-wachtwoord ',
          'redis_port' => 'Poort roodbaars ',
          'mail_label' => 'Post ',
          'mail_driver_label' => 'Mailstuurprogramma ',
          'mail_driver_placeholder' => 'Mailstuurprogramma ',
          'mail_host_label' => 'Mailhost ',
          'mail_host_placeholder' => 'Mailhost ',
          'mail_port_label' => 'Mailpoort ',
          'mail_port_placeholder' => 'Mailpoort ',
          'mail_username_label' => 'Gebruikersnaam voor e-mail ',
          'mail_username_placeholder' => 'Gebruikersnaam voor e-mail ',
          'mail_password_label' => 'Wachtwoord voor e-mail ',
          'mail_password_placeholder' => 'Wachtwoord voor e-mail ',
          'mail_encryption_label' => 'Mailversleuteling ',
          'mail_encryption_placeholder' => 'Mailversleuteling ',
          'pusher_label' => 'Pusher ',
          'pusher_app_id_label' => 'Pusher-app-ID ',
          'pusher_app_id_palceholder' => 'Pusher-app-ID ',
          'pusher_app_key_label' => 'Pusher-appsleutel ',
          'pusher_app_key_palceholder' => 'Pusher-appsleutel ',
          'pusher_app_secret_label' => 'Pusher-app geheim ',
          'pusher_app_secret_palceholder' => 'Pusher-app geheim ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Database instellen ',
          'setup_application' => 'Setup-toepassing ',
          'install' => 'Installeren ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Stap 3 | Omgeving Instellingen | Klassieke Editor ',
      'title' => 'Editor voor klassieke omgeving ',
      'save' => 'Opslaan .env ',
      'back' => 'Wizard Formulier gebruiken ',
      'install' => 'Opslaan en installeren ',
    ],
    'success' => 'Uw .env-bestandsinstellingen zijn opgeslagen. ',
    'errors' => 'Kan het .env-bestand niet opslaan, maak het handmatig. ',
  ],
  'install' => 'Installeren ',
  'installed' => 
  [
    'success_log_message' => 'Laravel-installatieprogramma is geïnstalleerd op ',
  ],
  'final' => 
  [
    'title' => 'Installatie is voltooid ',
    'templateTitle' => 'Installatie is voltooid ',
    'finished' => 'De toepassing is geïnstalleerd. ',
    'migration' => 'Uitvoer-&amp; seed-consoleuitvoer: ',
    'console' => 'Uitvoer van toepassingsconsole: ',
    'log' => 'Invoerlogboekitem: ',
    'env' => 'Definitieve .env-bestand: ',
    'exit' => 'Klik hier om af te sluiten ',
  ],
  'updater' => 
  [
    'title' => 'Laravel Updater ',
    'welcome' => 
    [
      'title' => 'Welkom Bij De Updater ',
      'message' => 'Welkom bij de updatewizard. ',
    ],
    'overview' => 
    [
      'title' => 'Overzicht ',
      'message' => 'Er is 1 update. | Er zijn: aantal updates. ',
      'install_updates' => 'Updates installeren ',
    ],
    'final' => 
    [
      'title' => 'Voltooid ',
      'finished' => 'De database van de toepassing is bijgewerkt. ',
      'exit' => 'Klik hier om af te sluiten ',
    ],
    'log' => 
    [
      'success_message' => 'Laravel-installatieprogramma is bijgewerkt op',
    ],
  ],
];