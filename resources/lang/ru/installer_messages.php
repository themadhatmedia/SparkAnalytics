<?php return [
  'title' => 'Программа установки Laravel ',
  'next' => 'Следующий шаг ',
  'back' => 'Предыдущая ',
  'finish' => 'Установить ',
  'forms' => 
  [
    'errorTitle' => 'Произошли следующие ошибки: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Приветствие ',
    'title' => 'Программа установки Laravel ',
    'message' => 'Простой мастер установки и настройки. ',
    'next' => 'Проверить требования ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Шаг 1 | Требования к серверу ',
    'title' => 'Требования к серверу ',
    'next' => 'Проверить права доступа ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Шаг 2 | Разрешения ',
    'title' => 'Разрешения ',
    'next' => 'Настроить среду ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Шаг 3 | Параметры среды ',
      'title' => 'Параметры среды ',
      'desc' => 'Выберите способ настройки файлаapps.env</code> приложений. ',
      'wizard-button' => 'Настройка мастера форм ',
      'classic-button' => 'Классический текстовый редактор ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Шаг 3 | Параметры среды | Пошаговый мастер ',
      'title' => 'Управляемый мастер <code>.env</code> ',
      'tabs' => 
      [
        'environment' => 'Окружающая среда ',
        'database' => 'База данных ',
        'application' => 'Приложение ',
      ],
      'form' => 
      [
        'name_required' => 'Требуется имя среды. ',
        'app_name_label' => 'Имя приложения ',
        'app_name_placeholder' => 'Имя приложения ',
        'app_environment_label' => 'Среда приложений ',
        'app_environment_label_local' => 'Локальный ',
        'app_environment_label_developement' => 'Развитие ',
        'app_environment_label_qa' => 'Qa ',
        'app_environment_label_production' => 'Производство ',
        'app_environment_label_other' => 'Прочие ',
        'app_environment_placeholder_other' => 'Введите свою среду ... ',
        'app_debug_label' => 'Отладка приложения ',
        'app_debug_label_true' => 'Истина ',
        'app_debug_label_false' => 'False ',
        'app_log_level_label' => 'Уровень протокола приложения ',
        'app_log_level_label_debug' => 'отладка ',
        'app_log_level_label_info' => 'info ',
        'app_log_level_label_notice' => 'обратите внимание ',
        'app_log_level_label_warning' => 'предупреждение ',
        'app_log_level_label_error' => 'ошибка ',
        'app_log_level_label_critical' => 'критический ',
        'app_log_level_label_alert' => 'оповещение ',
        'app_log_level_label_emergency' => 'Чрезвычайное положение ',
        'app_url_label' => 'Url Приложения ',
        'app_url_placeholder' => 'Url Приложения ',
        'db_connection_failed' => 'Не удалось соединиться с базой данных. ',
        'db_connection_label' => 'Соединение базы данных ',
        'db_connection_label_mysql' => 'mysql ',
        'db_connection_label_sqlite' => 'sqlite ',
        'db_connection_label_pgsql' => 'pgsql ',
        'db_connection_label_sqlsrv' => 'sqlsrv ',
        'db_host_label' => 'Хост базы данных ',
        'db_host_placeholder' => 'Хост базы данных ',
        'db_port_label' => 'Порт базы данных ',
        'db_port_placeholder' => 'Порт базы данных ',
        'db_name_label' => 'Имя базы данных ',
        'db_name_placeholder' => 'Имя базы данных ',
        'db_username_label' => 'Имя пользователя базы данных ',
        'db_username_placeholder' => 'Имя пользователя базы данных ',
        'db_password_label' => 'Пароль базы данных ',
        'db_password_placeholder' => 'Пароль базы данных ',
        'app_tabs' => 
        [
          'more_info' => 'Дополнительная информация ',
          'broadcasting_title' => 'Трансляция, кэширование, сеанс и очередь ',
          'broadcasting_label' => 'Драйвер широковещания ',
          'broadcasting_placeholder' => 'Драйвер широковещания ',
          'cache_label' => 'Драйвер кэша ',
          'cache_placeholder' => 'Драйвер кэша ',
          'session_label' => 'Драйвер сеанса ',
          'session_placeholder' => 'Драйвер сеанса ',
          'queue_label' => 'Драйвер очереди ',
          'queue_placeholder' => 'Драйвер очереди ',
          'redis_label' => 'Драйвер Redis ',
          'redis_host' => 'Хост Redis ',
          'redis_password' => 'Пароль Redis ',
          'redis_port' => 'Порт Redis ',
          'mail_label' => 'Почта ',
          'mail_driver_label' => 'Драйвер почты ',
          'mail_driver_placeholder' => 'Драйвер почты ',
          'mail_host_label' => 'Хост почты ',
          'mail_host_placeholder' => 'Хост почты ',
          'mail_port_label' => 'Почтовый порт ',
          'mail_port_placeholder' => 'Почтовый порт ',
          'mail_username_label' => 'Имя пользователя почты ',
          'mail_username_placeholder' => 'Имя пользователя почты ',
          'mail_password_label' => 'Пароль для почты ',
          'mail_password_placeholder' => 'Пароль для почты ',
          'mail_encryption_label' => 'Шифрование почты ',
          'mail_encryption_placeholder' => 'Шифрование почты ',
          'pusher_label' => 'Pusher ',
          'pusher_app_id_label' => 'ID программы Pusher ',
          'pusher_app_id_palceholder' => 'ID программы Pusher ',
          'pusher_app_key_label' => 'Ключ приложения Pusher ',
          'pusher_app_key_palceholder' => 'Ключ приложения Pusher ',
          'pusher_app_secret_label' => 'Пароль приложения Pusher ',
          'pusher_app_secret_palceholder' => 'Пароль приложения Pusher ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Настроить базу данных ',
          'setup_application' => 'Программа установки ',
          'install' => 'Установить ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Шаг 3 | Параметры среды | Классический редактор ',
      'title' => 'Классический редактор среды ',
      'save' => 'Сохранить .env ',
      'back' => 'Использовать мастер форм ',
      'install' => 'Сохранить и установить ',
    ],
    'success' => 'Параметры файла .env сохранены. ',
    'errors' => 'Не удалось сохранить файл .env, создайте его вручную. ',
  ],
  'install' => 'Установить ',
  'installed' => 
  [
    'success_log_message' => 'Программа установки Laravel успешно установлена на ',
  ],
  'final' => 
  [
    'title' => 'Установка завершена ',
    'templateTitle' => 'Установка завершена ',
    'finished' => 'Приложение успешно установлено. ',
    'migration' => 'Вывод &amp; вывода консоли: ',
    'console' => 'Вывод консоли приложения: ',
    'log' => 'Запись журнала установки: ',
    'env' => 'Конечный файл .env: ',
    'exit' => 'Щелкните здесь для выхода ',
  ],
  'updater' => 
  [
    'title' => 'Laravel Updater ',
    'welcome' => 
    [
      'title' => 'Добро Пожаловать В Обновление ',
      'message' => 'Вас приветствует мастер обновления. ',
    ],
    'overview' => 
    [
      'title' => 'Общий обзор ',
      'message' => 'Есть 1 обновление. | Есть: обновления числа. ',
      'install_updates' => 'Установить обновления ',
    ],
    'final' => 
    [
      'title' => 'Завершено ',
      'finished' => 'База данных приложения успешно обновлена. ',
      'exit' => 'Щелкните здесь для выхода ',
    ],
    'log' => 
    [
      'success_message' => 'Программа установки Laravel успешно обновилась',
    ],
  ],
];