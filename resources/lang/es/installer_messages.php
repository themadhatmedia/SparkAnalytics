<?php return [
  'title' => 'Laravel Installer ',
  'next' => 'Siguiente paso ',
  'back' => 'Anterior ',
  'finish' => 'Instalación ',
  'forms' => 
  [
    'errorTitle' => 'Se han producido los siguientes errores: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Bienvenido ',
    'title' => 'Laravel Installer ',
    'message' => 'Asistente de instalación y configuración. ',
    'next' => 'Requisitos de comprobación ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Paso 1 | Requisitos del servidor ',
    'title' => 'Requisitos del servidor ',
    'next' => 'Comprobar permisos ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Paso 2 | Permisos ',
    'title' => 'Permisos ',
    'next' => 'Configurar entorno ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Paso 3 | Valores del entorno ',
      'title' => 'Configuración del entorno ',
      'desc' => 'Seleccione cómo desea configurar el archivo de aplicaciones <code>.env</code> . ',
      'wizard-button' => 'Configuración del asistente de formulario ',
      'classic-button' => 'Editor de texto clásico ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Paso 3 | Configuración del entorno | Asistente guiado ',
      'title' => 'Asistente <code>.env</code> guiado ',
      'tabs' => 
      [
        'environment' => 'Medio ambiente ',
        'database' => 'Datos ',
        'application' => 'Solicitud ',
      ],
      'form' => 
      [
        'name_required' => 'Se necesita un nombre de entorno. ',
        'app_name_label' => 'Nombre de aplicación ',
        'app_name_placeholder' => 'Nombre de aplicación ',
        'app_environment_label' => 'Entorno de aplicaciones ',
        'app_environment_label_local' => 'Local ',
        'app_environment_label_developement' => 'Desarrollo ',
        'app_environment_label_qa' => 'Qa ',
        'app_environment_label_production' => 'Producción ',
        'app_environment_label_other' => 'Otros ',
        'app_environment_placeholder_other' => 'Especifique su entorno ... ',
        'app_debug_label' => 'Depuración de aplicaciones ',
        'app_debug_label_true' => 'Verdadero ',
        'app_debug_label_false' => 'Falso ',
        'app_log_level_label' => 'Nivel de registro de aplicación ',
        'app_log_level_label_debug' => 'Depuración ',
        'app_log_level_label_info' => 'Información ',
        'app_log_level_label_notice' => 'aviso ',
        'app_log_level_label_warning' => 'Aviso ',
        'app_log_level_label_error' => 'Error ',
        'app_log_level_label_critical' => 'Crítico ',
        'app_log_level_label_alert' => 'alerta ',
        'app_log_level_label_emergency' => 'Emergencia ',
        'app_url_label' => 'Url De Aplicación ',
        'app_url_placeholder' => 'Url De Aplicación ',
        'db_connection_failed' => 'No se ha podido conectar con la base de datos. ',
        'db_connection_label' => 'Conexión de base ',
        'db_connection_label_mysql' => 'mysql ',
        'db_connection_label_sqlite' => 'sqlite ',
        'db_connection_label_pgsql' => 'pgsql ',
        'db_connection_label_sqlsrv' => 'sqlsrv ',
        'db_host_label' => 'Base de datos ',
        'db_host_placeholder' => 'Base de datos ',
        'db_port_label' => 'Puerto de base ',
        'db_port_placeholder' => 'Puerto de base ',
        'db_name_label' => 'Nombre de base ',
        'db_name_placeholder' => 'Nombre de base ',
        'db_username_label' => 'Nombre de usuario ',
        'db_username_placeholder' => 'Nombre de usuario ',
        'db_password_label' => 'Contraseña de base ',
        'db_password_placeholder' => 'Contraseña de base ',
        'app_tabs' => 
        [
          'more_info' => 'Más información ',
          'broadcasting_title' => 'Difusión, Caching, Sesión y Cola ',
          'broadcasting_label' => 'Controlador de difusión ',
          'broadcasting_placeholder' => 'Controlador de difusión ',
          'cache_label' => 'Controlador de caché ',
          'cache_placeholder' => 'Controlador de caché ',
          'session_label' => 'Controlador de sesión ',
          'session_placeholder' => 'Controlador de sesión ',
          'queue_label' => 'Controlador de colas ',
          'queue_placeholder' => 'Controlador de colas ',
          'redis_label' => 'Controlador Redis ',
          'redis_host' => 'Reds Host ',
          'redis_password' => 'Contraseña de Reds ',
          'redis_port' => 'Puerto rojo ',
          'mail_label' => 'Correo electrónico ',
          'mail_driver_label' => 'Controlador de correo ',
          'mail_driver_placeholder' => 'Controlador de correo ',
          'mail_host_label' => 'Host de correo ',
          'mail_host_placeholder' => 'Host de correo ',
          'mail_port_label' => 'Puerto de correo ',
          'mail_port_placeholder' => 'Puerto de correo ',
          'mail_username_label' => 'Nombre de usuario ',
          'mail_username_placeholder' => 'Nombre de usuario ',
          'mail_password_label' => 'Contraseña de correo ',
          'mail_password_placeholder' => 'Contraseña de correo ',
          'mail_encryption_label' => 'Cifrado de correo ',
          'mail_encryption_placeholder' => 'Cifrado de correo ',
          'pusher_label' => 'Empujadores ',
          'pusher_app_id_label' => 'ID de aplicación de empujador ',
          'pusher_app_id_palceholder' => 'ID de aplicación de empujador ',
          'pusher_app_key_label' => 'Tecla de aplicación de empujador ',
          'pusher_app_key_palceholder' => 'Tecla de aplicación de empujador ',
          'pusher_app_secret_label' => 'Pusher App Secret ',
          'pusher_app_secret_palceholder' => 'Pusher App Secret ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Base de datos ',
          'setup_application' => 'Aplicación de configuración ',
          'install' => 'Instalación ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Paso 3 | Configuración del entorno | Editor clásico ',
      'title' => 'Editor de entorno clásico ',
      'save' => 'Guardar .env ',
      'back' => 'Asistente de formulario de uso ',
      'install' => 'Guardar e instalar ',
    ],
    'success' => 'Se han guardado los valores de archivo .env. ',
    'errors' => 'No se puede guardar el archivo .env. Por favor, créelo manualmente. ',
  ],
  'install' => 'Instalación ',
  'installed' => 
  [
    'success_log_message' => 'El instalador de Laravel se ha instalado correctamente en ',
  ],
  'final' => 
  [
    'title' => 'Instalación finalizada ',
    'templateTitle' => 'Instalación finalizada ',
    'finished' => 'La aplicación se ha instalado correctamente. ',
    'migration' => 'Salida de la consola de migración y semillas: ',
    'console' => 'Salida de consola de aplicaciones: ',
    'log' => 'Entrada de registro de instalación: ',
    'env' => 'Archivo .env Final: ',
    'exit' => 'Pulse aquí para salir ',
  ],
  'updater' => 
  [
    'title' => 'Laravel Updater ',
    'welcome' => 
    [
      'title' => 'Bienvenido Al Actualizador ',
      'message' => 'Bienvenido al asistente de actualización. ',
    ],
    'overview' => 
    [
      'title' => 'Visión general ',
      'message' => 'Hay 1 actualización. | Hay: número de actualizaciones. ',
      'install_updates' => 'Instalar actualizaciones ',
    ],
    'final' => 
    [
      'title' => 'Acabado ',
      'finished' => 'La base de datos de la aplicación se ha actualizado correctamente. ',
      'exit' => 'Pulse aquí para salir ',
    ],
    'log' => 
    [
      'success_message' => 'El instalador de Laravel se ha actualizado correctamente',
    ],
  ],
];