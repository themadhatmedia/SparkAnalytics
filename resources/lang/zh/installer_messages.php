<?php return [
    'title' => 'Laravel 安装程序',
    'next' => '下一步',
    'back' => '上一个',
    'finish' => '安装',
    'forms' => 
    [
      'errorTitle' => '发生以下错误 :',
    ],
    'welcome' => 
    [
      'templateTitle' => '欢迎',
      'title' => 'Laravel 安装程序',
      'message' => '轻松安装和设置向导。',
      'next' => '检查需求',
    ],
    'requirements' => 
    [
      'templateTitle' => '步骤 1 | 服务器需求',
      'title' => '服务器需求',
      'next' => '检查许可权',
    ],
    'permissions' => 
    [
      'templateTitle' => '步骤 2 | 许可权',
      'title' => '许可权',
      'next' => '配置环境',
    ],
    'environment' => 
    [
      'menu' => 
      [
        'templateTitle' => '步骤 3 | 环境设置',
        'title' => '环境设置',
        'desc' => '请选择要如何配置应用程序 <code>.env</code> 文件。',
        'wizard-button' => '表单向导设置',
        'classic-button' => '经典文本编辑器',
      ],
      'wizard' => 
      [
        'templateTitle' => '步骤 3 | 环境设置 | 引导向导',
        'title' => '指导 <code>.env</code> 向导',
        'tabs' => 
        [
          'environment' => '环境',
          'database' => '数据库',
          'application' => '申请',
        ],
        'form' => 
        [
          'name_required' => '环境名称是必需的。',
          'app_name_label' => '应用程序名称',
          'app_name_placeholder' => '应用程序名称',
          'app_environment_label' => '应用程序环境',
          'app_environment_label_local' => '本地',
          'app_environment_label_developement' => '发展',
          'app_environment_label_qa' => '加',
          'app_environment_label_production' => '生产',
          'app_environment_label_other' => '其他',
          'app_environment_placeholder_other' => '输入您的环境...',
          'app_debug_label' => '应用程序调试',
          'app_debug_label_true' => '真的',
          'app_debug_label_false' => '假的',
          'app_log_level_label' => '应用程序日志级别',
          'app_log_level_label_debug' => '调试',
          'app_log_level_label_info' => '信息',
          'app_log_level_label_notice' => '通知',
          'app_log_level_label_warning' => '警告',
          'app_log_level_label_error' => '错误',
          'app_log_level_label_critical' => '关键',
          'app_log_level_label_alert' => '警戒',
          'app_log_level_label_emergency' => '急急',
          'app_url_label' => '应用程序 URL',
          'app_url_placeholder' => '应用程序 URL',
          'db_connection_failed' => '无法连接到数据库。',
          'db_connection_label' => '数据库连接',
          'db_connection_label_mysql' => '米什 ql',
          'db_connection_label_sqlite' => 'Sqlite',
          'db_connection_label_pgsql' => 'pgsql',
          'db_connection_label_sqlsrv' => 'Sqlsrv',
          'db_host_label' => '数据库主机',
          'db_host_placeholder' => '数据库主机',
          'db_port_label' => '数据库端口',
          'db_port_placeholder' => '数据库端口',
          'db_name_label' => '数据库名称',
          'db_name_placeholder' => '数据库名称',
          'db_username_label' => '数据库用户名',
          'db_username_placeholder' => '数据库用户名',
          'db_password_label' => '数据库密码',
          'db_password_placeholder' => '数据库密码',
          'app_tabs' => 
          [
            'more_info' => '更多信息',
            'broadcasting_title' => '广播，高速缓存，会话和队列',
            'broadcasting_label' => '广播驱动程序',
            'broadcasting_placeholder' => '广播驱动程序',
            'cache_label' => '高速缓存驱动程序',
            'cache_placeholder' => '高速缓存驱动程序',
            'session_label' => '会话驱动程序',
            'session_placeholder' => '会话驱动程序',
            'queue_label' => '队列驱动程序',
            'queue_placeholder' => '队列驱动程序',
            'redis_label' => 'Redis 驱动程序',
            'redis_host' => '编辑主机',
            'redis_password' => 'Redis 密码',
            'redis_port' => 'Redis 端口',
            'mail_label' => '邮件',
            'mail_driver_label' => '邮件驱动程序',
            'mail_driver_placeholder' => '邮件驱动程序',
            'mail_host_label' => '邮件主机',
            'mail_host_placeholder' => '邮件主机',
            'mail_port_label' => '邮件端口',
            'mail_port_placeholder' => '邮件端口',
            'mail_username_label' => '邮件用户名',
            'mail_username_placeholder' => '邮件用户名',
            'mail_password_label' => '邮件密码',
            'mail_password_placeholder' => '邮件密码',
            'mail_encryption_label' => '邮件加密',
            'mail_encryption_placeholder' => '邮件加密',
            'pusher_label' => '推车',
            'pusher_app_id_label' => '推送应用程序标识',
            'pusher_app_id_palceholder' => '推送应用程序标识',
            'pusher_app_key_label' => '推送应用程序密钥',
            'pusher_app_key_palceholder' => '推送应用程序密钥',
            'pusher_app_secret_label' => '推送应用程序密钥',
            'pusher_app_secret_palceholder' => '推送应用程序密钥',
          ],
          'buttons' => 
          [
            'setup_database' => '设置数据库',
            'setup_application' => '设置应用程序',
            'install' => '安装',
          ],
        ],
      ],
      'classic' => 
      [
        'templateTitle' => '步骤 3 | 环境设置 | 经典编辑器',
        'title' => '经典环境编辑器',
        'save' => '保存 .env',
        'back' => '使用表单向导',
        'install' => '保存并安装',
      ],
      'success' => '已保存 .env 文件设置。',
      'errors' => '无法保存 .env 文件，请手动创建。',
    ],
    'install' => '安装',
    'installed' => 
    [
      'success_log_message' => '已成功安装 Laravel 安装程序',
    ],
    'final' => 
    [
      'title' => '安装已完成',
      'templateTitle' => '安装已完成',
      'finished' => '已成功安装应用程序。',
      'migration' => '迁移和种子控制台输出:',
      'console' => '应用程序控制台输出:',
      'log' => '安装日志条目:',
      'env' => '最终 .env 文件:',
      'exit' => '单击此处以退出',
    ],
    'updater' => 
    [
      'title' => 'Laravel Updater',
      'welcome' => 
      [
        'title' => '欢迎使用更新程序',
        'message' => '欢迎使用更新向导。',
      ],
      'overview' => 
      [
        'title' => '概述',
        'message' => '有 1 个更新。| 有 :数字更新。',
        'install_updates' => '安装更新',
      ],
      'final' => 
      [
        'title' => '完了',
        'finished' => '应用程序的数据库已成功更新。',
        'exit' => '单击此处以退出',
      ],
      'log' => 
      [
        'success_message' => '已成功更新 Laravel 安装程序',
      ],
    ],
];