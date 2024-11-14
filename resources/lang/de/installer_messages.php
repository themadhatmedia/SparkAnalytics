<?php return [
    'title' => 'Laravel-Installationsprogramm ',
    'next' => 'Nächster Schritt ',
    'back' => 'Vorherige ',
    'finish' => 'Installieren ',
    'forms' => 
    [
      'errorTitle' => 'Die folgenden Fehler sind aufgetreten: ',
    ],
    'welcome' => 
    [
      'templateTitle' => 'Begrüßung ',
      'title' => 'Laravel-Installationsprogramm ',
      'message' => 'Einfacher Installations-und Konfigurationsassistent. ',
      'next' => 'Anforderungen prüfen ',
    ],
    'requirements' => 
    [
      'templateTitle' => 'Schritt 1 | Servervoraussetzungen ',
      'title' => 'Servervoraussetzungen ',
      'next' => 'Berechtigungen prüfen ',
    ],
    'permissions' => 
    [
      'templateTitle' => 'Schritt 2 | Berechtigungen ',
      'title' => 'Berechtigungen ',
      'next' => 'Umgebung konfigurieren ',
    ],
    'environment' => 
    [
      'menu' => 
      [
        'templateTitle' => 'Schritt 3 | Umgebungseinstellungen ',
        'title' => 'Umgebungseinstellungen ',
        'desc' => 'Wählen Sie aus, wie Sie die Datei <code>.env</code> für die Apps konfigurieren möchten. ',
        'wizard-button' => 'Konfiguration des Formularassistenten ',
        'classic-button' => 'Klassischer Texteditor ',
      ],
      'wizard' => 
      [
        'templateTitle' => 'Schritt 3 | Umgebungseinstellungen | Geführter Assistent ',
        'title' => 'Geführter <code>.env</code> -Assistent ',
        'tabs' => 
        [
          'environment' => 'Umwelt ',
          'database' => 'Datenbank ',
          'application' => 'Anwendung ',
        ],
        'form' => 
        [
          'name_required' => 'Es ist ein Umgebungsname erforderlich. ',
          'app_name_label' => 'Anwendungsname ',
          'app_name_placeholder' => 'Anwendungsname ',
          'app_environment_label' => 'App-Umgebung ',
          'app_environment_label_local' => 'Lokal ',
          'app_environment_label_developement' => 'Entwicklung ',
          'app_environment_label_qa' => 'Qa ',
          'app_environment_label_production' => 'Produktion ',
          'app_environment_label_other' => 'Sonstige ',
          'app_environment_placeholder_other' => 'Geben Sie Ihre Umgebung ein ... ',
          'app_debug_label' => 'App-Debug ',
          'app_debug_label_true' => 'Wahr ',
          'app_debug_label_false' => 'Falsch ',
          'app_log_level_label' => 'App-Protokollebene ',
          'app_log_level_label_debug' => 'Debug ',
          'app_log_level_label_info' => 'Info ',
          'app_log_level_label_notice' => 'Hinweis ',
          'app_log_level_label_warning' => 'Warnung ',
          'app_log_level_label_error' => 'Fehler ',
          'app_log_level_label_critical' => 'Kritisch ',
          'app_log_level_label_alert' => 'Alert ',
          'app_log_level_label_emergency' => 'Notfall ',
          'app_url_label' => 'App-URL ',
          'app_url_placeholder' => 'App-URL ',
          'db_connection_failed' => 'Es konnte keine Verbindung zur Datenbank hergestellt werden. ',
          'db_connection_label' => 'Datenbankverbindung ',
          'db_connection_label_mysql' => 'mysql ',
          'db_connection_label_sqlite' => 'sqlite ',
          'db_connection_label_pgsql' => 'pgsql ',
          'db_connection_label_sqlsrv' => 'sqlsrv ',
          'db_host_label' => 'Datenbankhost ',
          'db_host_placeholder' => 'Datenbankhost ',
          'db_port_label' => 'Datenbankport ',
          'db_port_placeholder' => 'Datenbankport ',
          'db_name_label' => 'Datenbankname ',
          'db_name_placeholder' => 'Datenbankname ',
          'db_username_label' => 'Datenbankbenutzername ',
          'db_username_placeholder' => 'Datenbankbenutzername ',
          'db_password_label' => 'Datenbankkennwort ',
          'db_password_placeholder' => 'Datenbankkennwort ',
          'app_tabs' => 
          [
            'more_info' => 'Weitere Informationen ',
            'broadcasting_title' => 'Rundfunk, Caching, Sitzung und Warteschlange ',
            'broadcasting_label' => 'Broadcast-Treiber ',
            'broadcasting_placeholder' => 'Broadcast-Treiber ',
            'cache_label' => 'Cache-Treiber ',
            'cache_placeholder' => 'Cache-Treiber ',
            'session_label' => 'Sitzungstreiber ',
            'session_placeholder' => 'Sitzungstreiber ',
            'queue_label' => 'Warteschlangentreiber ',
            'queue_placeholder' => 'Warteschlangentreiber ',
            'redis_label' => 'Redig-Treiber ',
            'redis_host' => 'Redis-Host ',
            'redis_password' => 'Kennwort redigiert ',
            'redis_port' => 'Port für redis ',
            'mail_label' => 'E-Mail ',
            'mail_driver_label' => 'Mail-Treiber ',
            'mail_driver_placeholder' => 'Mail-Treiber ',
            'mail_host_label' => 'Mail-Host ',
            'mail_host_placeholder' => 'Mail-Host ',
            'mail_port_label' => 'Mail-Port ',
            'mail_port_placeholder' => 'Mail-Port ',
            'mail_username_label' => 'Mail-Benutzername ',
            'mail_username_placeholder' => 'Mail-Benutzername ',
            'mail_password_label' => 'Mail-Kennwort ',
            'mail_password_placeholder' => 'Mail-Kennwort ',
            'mail_encryption_label' => 'Mailverschlüsselung ',
            'mail_encryption_placeholder' => 'Mailverschlüsselung ',
            'pusher_label' => 'Schieber ',
            'pusher_app_id_label' => 'Schieber-App-ID ',
            'pusher_app_id_palceholder' => 'Schieber-App-ID ',
            'pusher_app_key_label' => 'Schieber-App-Schlüssel ',
            'pusher_app_key_palceholder' => 'Schieber-App-Schlüssel ',
            'pusher_app_secret_label' => 'Schlüssel für Push-App ',
            'pusher_app_secret_palceholder' => 'Schlüssel für Push-App ',
          ],
          'buttons' => 
          [
            'setup_database' => 'Datenbank einrichten ',
            'setup_application' => 'Anwendung einrichten ',
            'install' => 'Installieren ',
          ],
        ],
      ],
      'classic' => 
      [
        'templateTitle' => 'Schritt 3 | Umgebungseinstellungen | Klassischer Editor ',
        'title' => 'Klassischer Umgebungseditor ',
        'save' => '.env speichern ',
        'back' => 'Formularassistent verwenden ',
        'install' => 'Speichern und installieren ',
      ],
      'success' => 'Ihre .env-Dateieinstellungen wurden gespeichert. ',
      'errors' => 'Die .env-Datei kann nicht gespeichert werden. Erstellen Sie sie manuell. ',
    ],
    'install' => 'Installieren ',
    'installed' => 
    [
      'success_log_message' => 'Laravel Installer erfolgreich installiert auf ',
    ],
    'final' => 
    [
      'title' => 'Installation beendet ',
      'templateTitle' => 'Installation beendet ',
      'finished' => 'Die Anwendung wurde erfolgreich installiert. ',
      'migration' => 'Ausgabe der Migration &amp; Seed-Konsole: ',
      'console' => 'Ausgabe der Anwendungskonsole: ',
      'log' => 'Installationsprotokolleintrag: ',
      'env' => 'Final .env-Datei: ',
      'exit' => 'Zum Beenden hier klicken ',
    ],
    'updater' => 
    [
      'title' => 'Laravel Updater ',
      'welcome' => 
      [
        'title' => 'Willkommen bei The Updater ',
        'message' => 'Willkommen beim Aktualisierungsassistenten. ',
      ],
      'overview' => 
      [
        'title' => 'Überblick ',
        'message' => 'Es gibt 1 Update. | Es gibt: Nummernupdates. ',
        'install_updates' => 'Aktualisierungen installieren ',
      ],
      'final' => 
      [
        'title' => 'Fertig ',
        'finished' => 'Die Datenbank der Anwendung wurde erfolgreich aktualisiert. ',
        'exit' => 'Zum Beenden hier klicken ',
      ],
      'log' => 
      [
        'success_message' => 'Laravel Installer erfolgreich aktualisiert am',
      ],
    ],
];