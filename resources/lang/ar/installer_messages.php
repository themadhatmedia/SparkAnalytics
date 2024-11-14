<?php return [
    'title' => 'برنامج تركيب Laravel ',
    'next' => 'الخطوة التالية ',
    'back' => 'سابق ',
    'finish' => 'تركيب ',
    'forms' => 
    [
      'errorTitle' => 'حدثت الأخطاء التالية : ',
    ],
    'welcome' => 
    [
      'templateTitle' => 'أهلا بكم ',
      'title' => 'برنامج تركيب Laravel ',
      'message' => 'برنامج المعالجة الخاص بالتركيب والاعداد. ',
      'next' => 'التحقق من المتطلبات ',
    ],
    'requirements' => 
    [
      'templateTitle' => 'الخطوة 1 | متطلبات وحدة الخدمة ',
      'title' => 'متطلبات وحدة الخدمة ',
      'next' => 'التحقق من التصاريح ',
    ],
    'permissions' => 
    [
      'templateTitle' => 'الخطوة 2 | التصاريح ',
      'title' => 'التصاريح ',
      'next' => 'توصيف بيئة التشغيل ',
    ],
    'environment' => 
    [
      'menu' => 
      [
        'templateTitle' => 'الخطوة 3 | محددات بيئة التشغيل ',
        'title' => 'محددات بيئة التشغيل ',
        'desc' => 'برجاء تحديد الطريقة المطلوب استخدامها في توصيف ملف apps <code>.env</code> . ',
        'wizard-button' => 'اعداد برنامج معالجة النموذج ',
        'classic-button' => 'برنامج تحرير النصوص التقليدية ',
      ],
      'wizard' => 
      [
        'templateTitle' => 'الخطوة 3 | المحددات البيئية | برنامج المعالجة الموجه ',
        'title' => 'موجه <code>.env</code> الموجه ',
        'tabs' => 
        [
          'environment' => 'البيئة ',
          'database' => 'قاعدة البيانات ',
          'application' => 'التطبيق ',
        ],
        'form' => 
        [
          'name_required' => 'يجب تحديد اسم بيئة التشغيل. ',
          'app_name_label' => 'اسم التطبيق ',
          'app_name_placeholder' => 'اسم التطبيق ',
          'app_environment_label' => 'بيئة التشغيل ',
          'app_environment_label_local' => 'محلية ',
          'app_environment_label_developement' => 'التنمية ',
          'app_environment_label_qa' => 'Qa ',
          'app_environment_label_production' => 'الانتاج ',
          'app_environment_label_other' => 'مسائل أخرى ',
          'app_environment_placeholder_other' => 'أدخل البيئة الخاصة بك ... ',
          'app_debug_label' => 'تصحيح أخطاء التطبيق ',
          'app_debug_label_true' => 'صحيح. ',
          'app_debug_label_false' => 'خطأ ',
          'app_log_level_label' => 'مستوى سجل التطبيق ',
          'app_log_level_label_debug' => 'تصحيح الأخطاء ',
          'app_log_level_label_info' => 'المعلومات ',
          'app_log_level_label_notice' => 'اشعار ',
          'app_log_level_label_warning' => 'تحذير ',
          'app_log_level_label_error' => 'خطأ ',
          'app_log_level_label_critical' => 'هام ',
          'app_log_level_label_alert' => 'تنبيه ',
          'app_log_level_label_emergency' => 'طوارئ ',
          'app_url_label' => 'عنوان Url ',
          'app_url_placeholder' => 'عنوان Url ',
          'db_connection_failed' => 'لا يمكن الاتصال بقاعدة البيانات. ',
          'db_connection_label' => 'وصلة قاعدة البيانات ',
          'db_connection_label_mysql' => 'mysql ',
          'db_connection_label_sqlite' => 'sqlite ',
          'db_connection_label_pgsql' => 'pgsql ',
          'db_connection_label_sqlsrv' => 'sqlsrv ',
          'db_host_label' => 'نظام قاعدة البيانات الرئيسي ',
          'db_host_placeholder' => 'نظام قاعدة البيانات الرئيسي ',
          'db_port_label' => 'منفذ قاعدة البيانات ',
          'db_port_placeholder' => 'منفذ قاعدة البيانات ',
          'db_name_label' => 'اسم قاعدة البيانات ',
          'db_name_placeholder' => 'اسم قاعدة البيانات ',
          'db_username_label' => 'اسم مستخدم قاعدة البيانات ',
          'db_username_placeholder' => 'اسم مستخدم قاعدة البيانات ',
          'db_password_label' => 'كلمة سرية قاعدة البيانات ',
          'db_password_placeholder' => 'كلمة سرية قاعدة البيانات ',
          'app_tabs' => 
          [
            'more_info' => 'مزيد من المعلومات ',
            'broadcasting_title' => 'اذاعة ، تخزين بالذاكرة الوسيطة ، جلسة ، & صف ',
            'broadcasting_label' => 'برنامج تشغيل الاشعارات ',
            'broadcasting_placeholder' => 'برنامج تشغيل الاشعارات ',
            'cache_label' => 'وحدة تشغيل الذاكرة الوسيطة ',
            'cache_placeholder' => 'وحدة تشغيل الذاكرة الوسيطة ',
            'session_label' => 'وحدة تشغيل الجلسة ',
            'session_placeholder' => 'وحدة تشغيل الجلسة ',
            'queue_label' => 'مشغل الصف ',
            'queue_placeholder' => 'مشغل الصف ',
            'redis_label' => 'برنامج التشغيل Redriver ',
            'redis_host' => 'اعادة النظام الرئيسي ',
            'redis_password' => 'اعادة كلمة السرية ',
            'redis_port' => 'منفذ Redt ',
            'mail_label' => 'البريد ',
            'mail_driver_label' => 'وحدة تشغيل البريد ',
            'mail_driver_placeholder' => 'وحدة تشغيل البريد ',
            'mail_host_label' => 'النظام الرئيسي للبريد ',
            'mail_host_placeholder' => 'النظام الرئيسي للبريد ',
            'mail_port_label' => 'منفذ البريد ',
            'mail_port_placeholder' => 'منفذ البريد ',
            'mail_username_label' => 'اسم مستخدم البريد ',
            'mail_username_placeholder' => 'اسم مستخدم البريد ',
            'mail_password_label' => 'كلمة سرية البريد ',
            'mail_password_placeholder' => 'كلمة سرية البريد ',
            'mail_encryption_label' => 'تشفير البريد ',
            'mail_encryption_placeholder' => 'تشفير البريد ',
            'pusher_label' => 'Pتحة ',
            'pusher_app_id_label' => 'كود تطبيق Palwcs ',
            'pusher_app_id_palceholder' => 'كود تطبيق Palwcs ',
            'pusher_app_key_label' => 'مفتاح Prps App ',
            'pusher_app_key_palceholder' => 'مفتاح Prps App ',
            'pusher_app_secret_label' => 'سر تطبيق Prps App ',
            'pusher_app_secret_palceholder' => 'سر تطبيق Prps App ',
          ],
          'buttons' => 
          [
            'setup_database' => 'اعداد قاعدة البيانات ',
            'setup_application' => 'اعداد التطبيق ',
            'install' => 'تركيب ',
          ],
        ],
      ],
      'classic' => 
      [
        'templateTitle' => 'الخطوة 3 | محددات بيئة التشغيل | برنامج تحرير تقليدي ',
        'title' => 'محرر بيئة كلاسيكية ',
        'save' => 'حفظ .env ',
        'back' => 'استخدام نموذج النموذج ',
        'install' => 'حفظ وتركيب ',
      ],
      'success' => 'تم حفظ محددات ملف .env الخاص بك. ',
      'errors' => 'لا يمكن حفظ ملف .env ، برجاء تكوينه يدويا. ',
    ],
    'install' => 'تركيب ',
    'installed' => 
    [
      'success_log_message' => 'تم تركيب Laravel Installer بنجاح على ',
    ],
    'final' => 
    [
      'title' => 'تم انهاء التركيب ',
      'templateTitle' => 'تم انهاء التركيب ',
      'finished' => 'تم تركيب التطبيق بنجاح. ',
      'migration' => 'مخرجات الشاشة الرئيسية للانتقال & Seed : ',
      'console' => 'مخرجات شاشة التحكم الرئيسية للتطبيق : ',
      'log' => 'ادخال سجل التركيب : ',
      'env' => 'ملف .env النهائي : ',
      'exit' => 'اضغط هنا للخروج ',
    ],
    'updater' => 
    [
      'title' => 'redater aaraLa ',
      'welcome' => 
      [
        'title' => 'مرحبا بك في Updataer ',
        'message' => 'مرحبا بك في برنامج معالجة التعديل. ',
      ],
      'overview' => 
      [
        'title' => 'لمحة عامة ',
        'message' => 'يوجد 1 تعديل. | هناك تعديلات بالأرقام. ',
        'install_updates' => 'تركيب التعديلات ',
      ],
      'final' => 
      [
        'title' => 'منتهي ',
        'finished' => 'تم تحديث قاعدة بيانات التطبيق بنجاح. ',
        'exit' => 'اضغط هنا للخروج ',
      ],
      'log' => 
      [
        'success_message' => 'تم تعديل Laravel Installer بنجاح على',
      ],
    ],
];
?>