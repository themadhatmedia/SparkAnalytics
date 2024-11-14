<?php return [
  'title' => 'Programme d\'installation de Laravel ',
  'next' => 'Etape suivante ',
  'back' => 'Précédent ',
  'finish' => 'Installation ',
  'forms' => 
  [
    'errorTitle' => 'Les erreurs suivantes se sont produites: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Bienvenue ',
    'title' => 'Programme d\'installation de Laravel ',
    'message' => 'Assistant d\'installation et de configuration facile. ',
    'next' => 'Vérifier les exigences ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Etape 1 | Configuration requise pour le serveur ',
    'title' => 'Configuration requise du serveur ',
    'next' => 'Vérifier les droits d\'accès ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Etape 2 | Droits d\'accès ',
    'title' => 'Droits ',
    'next' => 'Configuration de l\'environnement ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Etape 3 | Paramètres d\'environnement ',
      'title' => 'Paramètres d\'environnement ',
      'desc' => 'Sélectionnez le mode de configuration du fichier d\'applications <code>.env</code> . ',
      'wizard-button' => 'Configuration de l\'assistant de formulaire ',
      'classic-button' => 'Editeur de texte classique ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Etape 3 | Paramètres de l\'environnement | Assistant guidé ',
      'title' => 'Assistant <code>.env</code> guidé ',
      'tabs' => 
      [
        'environment' => 'Environnement ',
        'database' => 'Base de données ',
        'application' => 'Application ',
      ],
      'form' => 
      [
        'name_required' => 'Un nom d\'environnement est requis. ',
        'app_name_label' => 'Nom de l\'application ',
        'app_name_placeholder' => 'Nom de l\'application ',
        'app_environment_label' => 'Environnement d\'application ',
        'app_environment_label_local' => 'Locale ',
        'app_environment_label_developement' => 'Développement ',
        'app_environment_label_qa' => 'Qa ',
        'app_environment_label_production' => 'Production ',
        'app_environment_label_other' => 'Autres ',
        'app_environment_placeholder_other' => 'Entrez votre environnement ... ',
        'app_debug_label' => 'Débogage App ',
        'app_debug_label_true' => 'Vrai ',
        'app_debug_label_false' => 'Faux ',
        'app_log_level_label' => 'Niveau du journal d\'application ',
        'app_log_level_label_debug' => 'Débogage ',
        'app_log_level_label_info' => 'Informations ',
        'app_log_level_label_notice' => 'Avis ',
        'app_log_level_label_warning' => 'Avertissement ',
        'app_log_level_label_error' => 'Erreur ',
        'app_log_level_label_critical' => 'Critique ',
        'app_log_level_label_alert' => 'Alerte ',
        'app_log_level_label_emergency' => 'Urgence ',
        'app_url_label' => 'URL de l\'application ',
        'app_url_placeholder' => 'URL de l\'application ',
        'db_connection_failed' => 'Impossible de se connecter à la base de données. ',
        'db_connection_label' => 'Connexion base de données ',
        'db_connection_label_mysql' => 'Mysql ',
        'db_connection_label_sqlite' => 'SQLite ',
        'db_connection_label_pgsql' => 'Pgsql ',
        'db_connection_label_sqlsrv' => 'Sqlsrv ',
        'db_host_label' => 'Hôte de base ',
        'db_host_placeholder' => 'Hôte de base ',
        'db_port_label' => 'Port base de données ',
        'db_port_placeholder' => 'Port base de données ',
        'db_name_label' => 'Nom de la base ',
        'db_name_placeholder' => 'Nom de la base ',
        'db_username_label' => 'Nom d\'utilisateur base de données ',
        'db_username_placeholder' => 'Nom d\'utilisateur base de données ',
        'db_password_label' => 'Mot de passe BD ',
        'db_password_placeholder' => 'Mot de passe BD ',
        'app_tabs' => 
        [
          'more_info' => 'Plus d\'informations ',
          'broadcasting_title' => 'Diffusion, mise en cache, session et file d\'attente ',
          'broadcasting_label' => 'Pilote de diffusion ',
          'broadcasting_placeholder' => 'Pilote de diffusion ',
          'cache_label' => 'Pilote de cache ',
          'cache_placeholder' => 'Pilote de cache ',
          'session_label' => 'Pilote de session ',
          'session_placeholder' => 'Pilote de session ',
          'queue_label' => 'Pilote de file ',
          'queue_placeholder' => 'Pilote de file ',
          'redis_label' => 'Redis Driver ',
          'redis_host' => 'Hôte Redis ',
          'redis_password' => 'Mot de passe Redis ',
          'redis_port' => 'Port Redis ',
          'mail_label' => 'Courrier ',
          'mail_driver_label' => 'Pilote de messagerie ',
          'mail_driver_placeholder' => 'Pilote de messagerie ',
          'mail_host_label' => 'Hôte de messagerie ',
          'mail_host_placeholder' => 'Hôte de messagerie ',
          'mail_port_label' => 'Port du courrier ',
          'mail_port_placeholder' => 'Port du courrier ',
          'mail_username_label' => 'Nom d\'utilisateur du courrier ',
          'mail_username_placeholder' => 'Nom d\'utilisateur du courrier ',
          'mail_password_label' => 'Mot de passe courrier ',
          'mail_password_placeholder' => 'Mot de passe courrier ',
          'mail_encryption_label' => 'Chiffrement du courrier ',
          'mail_encryption_placeholder' => 'Chiffrement du courrier ',
          'pusher_label' => 'Pusher ',
          'pusher_app_id_label' => 'ID application Pusher ',
          'pusher_app_id_palceholder' => 'ID application Pusher ',
          'pusher_app_key_label' => 'Clé d\'application Pusher ',
          'pusher_app_key_palceholder' => 'Clé d\'application Pusher ',
          'pusher_app_secret_label' => 'Pusher App Secret ',
          'pusher_app_secret_palceholder' => 'Pusher App Secret ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Configuration de la base ',
          'setup_application' => 'Configuration de l\'application ',
          'install' => 'Installation ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Etape 3 | Paramètres d\'environnement | Editeur classique ',
      'title' => 'Editeur d\'environnement classique ',
      'save' => 'Sauvegarder .env ',
      'back' => 'Assistant de formulaire d\'utilisation ',
      'install' => 'Enregistrer et installer ',
    ],
    'success' => 'Vos paramètres de fichier .env ont été sauvegardés. ',
    'errors' => 'Impossible de sauvegarder le fichier .env, créez-le manuellement. ',
  ],
  'install' => 'Installation ',
  'installed' => 
  [
    'success_log_message' => 'Le programme d\'installation de Laravel a réussi ',
  ],
  'final' => 
  [
    'title' => 'Installation terminée ',
    'templateTitle' => 'Installation terminée ',
    'finished' => 'L\'installation de l\'application a abouti. ',
    'migration' => 'Sortie de la console Migration &amp; Seed: ',
    'console' => 'Sortie de la console d\'application: ',
    'log' => 'Entrée du journal d\'installation: ',
    'env' => 'Fichier .env final: ',
    'exit' => 'Cliquez ici pour quitter ',
  ],
  'updater' => 
  [
    'title' => 'Laravel Updater ',
    'welcome' => 
    [
      'title' => 'Bienvenue dans le programme de mise à jour ',
      'message' => 'Bienvenue dans l\'assistant de mise à jour. ',
    ],
    'overview' => 
    [
      'title' => 'Aperçu ',
      'message' => 'Il y a 1 mise à jour. | Il y a: nombre de mises à jour. ',
      'install_updates' => 'Mises à jour des ',
    ],
    'final' => 
    [
      'title' => 'Terminé ',
      'finished' => 'La base de données de l\'application a été mise à jour. ',
      'exit' => 'Cliquez ici pour quitter ',
    ],
    'log' => 
    [
      'success_message' => 'Le programme d\'installation de Laravel a réussi à',
    ],
  ],
];