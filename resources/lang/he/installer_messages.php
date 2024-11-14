<?php return [
  'title' => 'תוכנית ההתקנה של לארובל ',
  'next' => 'השלב הבא ',
  'back' => 'הקודם ',
  'finish' => 'התקנה ',
  'forms' => 
  [
    'errorTitle' => 'אירעו השגיאות הבאות: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'ברוכים הבאים ',
    'title' => 'תוכנית ההתקנה של לארובל ',
    'message' => 'אשף התקנה וקביעת התקנה קלים. ',
    'next' => 'בדיקת דרישות ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'שלב 1 | דרישות שרת ',
    'title' => 'דרישות שרת ',
    'next' => 'בדיקת הרשאות ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'שלב 2 | הרשאות ',
    'title' => 'הרשאות ',
    'next' => 'הגדרת סביבה ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'שלב 3 | הגדרות סביבה ',
      'title' => 'הגדרות סביבה ',
      'desc' => 'בחרו כיצד ברצונכם להגדיר את תצורת היישום <code>.env</code> . ',
      'wizard-button' => 'הגדרת אשף הטפסים ',
      'classic-button' => 'עורך תמליל קלאסי ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'שלב 3 | הגדרות סביבה | אשף מונחה ',
      'title' => 'אשף <code>.env</code> ',
      'tabs' => 
      [
        'environment' => 'סביבה ',
        'database' => 'מסד נתונים ',
        'application' => 'יישום ',
      ],
      'form' => 
      [
        'name_required' => 'דרוש שם סביבה. ',
        'app_name_label' => 'שם יישום ',
        'app_name_placeholder' => 'שם יישום ',
        'app_environment_label' => 'סביבת יישומים ',
        'app_environment_label_local' => 'מקומי ',
        'app_environment_label_developement' => 'פיתוח ',
        'app_environment_label_qa' => 'קא ',
        'app_environment_label_production' => 'ייצור ',
        'app_environment_label_other' => 'אחר ',
        'app_environment_placeholder_other' => 'ציינו את הסביבה שלכם ... ',
        'app_debug_label' => 'ניפוי יישומים ',
        'app_debug_label_true' => 'True ',
        'app_debug_label_false' => 'False ',
        'app_log_level_label' => 'רמת יומן יישומים ',
        'app_log_level_label_debug' => 'ניפוי ',
        'app_log_level_label_info' => 'מידע ',
        'app_log_level_label_notice' => 'הודעה ',
        'app_log_level_label_warning' => 'אזהרה ',
        'app_log_level_label_error' => 'שגיאה ',
        'app_log_level_label_critical' => 'קריטי ',
        'app_log_level_label_alert' => 'התראה ',
        'app_log_level_label_emergency' => 'מצב חירום ',
        'app_url_label' => 'URL של יישום ',
        'app_url_placeholder' => 'URL של יישום ',
        'db_connection_failed' => 'לא ניתן להתחבר למסד הנתונים. ',
        'db_connection_label' => 'חיבור למסד נתונים ',
        'db_connection_label_mysql' => 'Mysql ',
        'db_connection_label_sqlite' => 'Sqlite ',
        'db_connection_label_pgsql' => 'Pgsql ',
        'db_connection_label_sqlsrv' => 'Sqlsrv ',
        'db_host_label' => 'מארח בסיס נתונים ',
        'db_host_placeholder' => 'מארח בסיס נתונים ',
        'db_port_label' => 'יציאת מסד נתונים ',
        'db_port_placeholder' => 'יציאת מסד נתונים ',
        'db_name_label' => 'שם מסד נתונים ',
        'db_name_placeholder' => 'שם מסד נתונים ',
        'db_username_label' => 'שם משתמש של מסד נתונים ',
        'db_username_placeholder' => 'שם משתמש של מסד נתונים ',
        'db_password_label' => 'סיסמת מסד נתונים ',
        'db_password_placeholder' => 'סיסמת מסד נתונים ',
        'app_tabs' => 
        [
          'more_info' => 'מידע נוסף ',
          'broadcasting_title' => 'שידור, אחסון במטמון, מהלך עבודה, &amp; תור ',
          'broadcasting_label' => 'מנהל שידור ',
          'broadcasting_placeholder' => 'מנהל שידור ',
          'cache_label' => 'מנהל התקן מטמון ',
          'cache_placeholder' => 'מנהל התקן מטמון ',
          'session_label' => 'מנהל מהלך עבודה ',
          'session_placeholder' => 'מנהל מהלך עבודה ',
          'queue_label' => 'מנהל תור ',
          'queue_placeholder' => 'מנהל תור ',
          'redis_label' => 'מנהל התקן Redis ',
          'redis_host' => 'מארח Redis ',
          'redis_password' => 'סיסמת Redis ',
          'redis_port' => 'יציאה של Redis ',
          'mail_label' => 'דואר ',
          'mail_driver_label' => 'מנהל דואר ',
          'mail_driver_placeholder' => 'מנהל דואר ',
          'mail_host_label' => 'מארח דואר ',
          'mail_host_placeholder' => 'מארח דואר ',
          'mail_port_label' => 'יציאת דואר ',
          'mail_port_placeholder' => 'יציאת דואר ',
          'mail_username_label' => 'שם משתמש דואר ',
          'mail_username_placeholder' => 'שם משתמש דואר ',
          'mail_password_label' => 'סיסמת דואר ',
          'mail_password_placeholder' => 'סיסמת דואר ',
          'mail_encryption_label' => 'הצפנת דואר ',
          'mail_encryption_placeholder' => 'הצפנת דואר ',
          'pusher_label' => 'אשר ',
          'pusher_app_id_label' => 'זיהוי יישום של Pusher ',
          'pusher_app_id_palceholder' => 'זיהוי יישום של Pusher ',
          'pusher_app_key_label' => 'מפתח יישום דוחף ',
          'pusher_app_key_palceholder' => 'מפתח יישום דוחף ',
          'pusher_app_secret_label' => 'סוד יישום דוחף ',
          'pusher_app_secret_palceholder' => 'סוד יישום דוחף ',
        ],
        'buttons' => 
        [
          'setup_database' => 'בסיס נתונים להגדרות ',
          'setup_application' => 'יישום התקנה ',
          'install' => 'התקנה ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'שלב 3 | הגדרות סביבה | עורך קלאסי ',
      'title' => 'עורך סביבה קלאסי ',
      'save' => 'שמירה .env ',
      'back' => 'שימוש באשף \' טופס \' ',
      'install' => 'שמירה והתקנה ',
    ],
    'success' => 'הגדרות קובץ .env נשמרו. ',
    'errors' => 'לא ניתן לשמור את קובץ .env, נא ליצור אותו ידנית. ',
  ],
  'install' => 'התקנה ',
  'installed' => 
  [
    'success_log_message' => 'תוכנית ההתקנה של Laravel בוצעה בהצלחה ',
  ],
  'final' => 
  [
    'title' => 'ההתקנה הסתיימה ',
    'templateTitle' => 'ההתקנה הסתיימה ',
    'finished' => 'היישום הותקן בהצלחה. ',
    'migration' => 'פלט &הגירה של הגירה של הגירה: ',
    'console' => 'פלט קונסול של יישום: ',
    'log' => 'רישום יומן התקנה: ',
    'env' => 'קובץ .env סופי: ',
    'exit' => 'לחצו כאן כדי לצאת. ',
  ],
  'updater' => 
  [
    'title' => 'עדף Laravel ',
    'welcome' => 
    [
      'title' => 'ברוכים הבאים אל המעדכן ',
      'message' => 'ברוכים הבאים אל אשף העדכון. ',
    ],
    'overview' => 
    [
      'title' => 'סקירה כללית ',
      'message' => 'יש עדכון אחד. | יש: מספר עדכונים. ',
      'install_updates' => 'התקנת עדכונים ',
    ],
    'final' => 
    [
      'title' => 'הסתיים ',
      'finished' => 'מסד הנתונים של היישום עודכן בהצלחה. ',
      'exit' => 'לחצו כאן כדי לצאת. ',
    ],
    'log' => 
    [
      'success_message' => 'תוכנית ההתקנה של Laravel בוצעה בהצלחה',
    ],
  ],
];