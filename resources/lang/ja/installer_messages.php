<?php return [
  'title' => 'Laravel インストーラー',
  'next' => '次のステップ',
  'back' => '前へ',
  'finish' => 'インストール',
  'forms' => 
  [
    'errorTitle' => '次のエラーが発生しました :',
  ],
  'welcome' => 
  [
    'templateTitle' => 'ようこそ',
    'title' => 'Laravel インストーラー',
    'message' => '簡易インストールおよびセットアップ・ウィザード。',
    'next' => '要件の確認',
  ],
  'requirements' => 
  [
    'templateTitle' => 'ステップ 1 | サーバー要件',
    'title' => 'サーバー要件',
    'next' => '許可の確認',
  ],
  'permissions' => 
  [
    'templateTitle' => 'ステップ 2 | 許可',
    'title' => 'アクセス権',
    'next' => '環境の構成',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'ステップ 3 | 環境設定',
      'title' => '環境設定',
      'desc' => 'アプリケーション <code>.env</code> ファイルをどのように構成するかを選択してください。',
      'wizard-button' => 'フォームウィザードのセットアップ',
      'classic-button' => '標準テキスト・エディター',
    ],
    'wizard' => 
    [
      'templateTitle' => 'ステップ 3 | 環境設定 | ガイド付きウィザード',
      'title' => 'ガイドされた <code>.env</code> ウィザード',
      'tabs' => 
      [
        'environment' => '環境',
        'database' => 'データベース',
        'application' => 'アプリケーション',
      ],
      'form' => 
      [
        'name_required' => '環境名が必要です。',
        'app_name_label' => 'アプリケーション名',
        'app_name_placeholder' => 'アプリケーション名',
        'app_environment_label' => 'アプリケーション環境',
        'app_environment_label_local' => '■ 普通',
        'app_environment_label_developement' => '発展',
        'app_environment_label_qa' => 'カーイ',
        'app_environment_label_production' => '生産',
        'app_environment_label_other' => 'その他',
        'app_environment_placeholder_other' => '環境の入力 ...',
        'app_debug_label' => 'アプリケーション・デバッグ',
        'app_debug_label_true' => 'トゥルー',
        'app_debug_label_false' => '偽',
        'app_log_level_label' => 'App ログ・レベル',
        'app_log_level_label_debug' => 'デバッグ',
        'app_log_level_label_info' => '情報',
        'app_log_level_label_notice' => '通知',
        'app_log_level_label_warning' => '警告',
        'app_log_level_label_error' => 'エラー',
        'app_log_level_label_critical' => 'クリティカル',
        'app_log_level_label_alert' => 'アラート',
        'app_log_level_label_emergency' => '緊急事態',
        'app_url_label' => 'アプリケーション URL',
        'app_url_placeholder' => 'アプリケーション URL',
        'db_connection_failed' => 'データベースに接続できませんでした。',
        'db_connection_label' => 'データベース接続',
        'db_connection_label_mysql' => 'ミシュクル',
        'db_connection_label_sqlite' => 'sqlite',
        'db_connection_label_pgsql' => 'プグプル',
        'db_connection_label_sqlsrv' => 'sqlsrv',
        'db_host_label' => 'データベース・ホスト',
        'db_host_placeholder' => 'データベース・ホスト',
        'db_port_label' => 'データベース・ポート',
        'db_port_placeholder' => 'データベース・ポート',
        'db_name_label' => 'データベース名',
        'db_name_placeholder' => 'データベース名',
        'db_username_label' => 'データベース・ユーザー名',
        'db_username_placeholder' => 'データベース・ユーザー名',
        'db_password_label' => 'データベース・パスワード',
        'db_password_placeholder' => 'データベース・パスワード',
        'app_tabs' => 
        [
          'more_info' => '詳細情報',
          'broadcasting_title' => 'ブロードキャスト、キャッシング、セッション、 &amp; キュー',
          'broadcasting_label' => 'ブロードキャスト・ドライバー',
          'broadcasting_placeholder' => 'ブロードキャスト・ドライバー',
          'cache_label' => 'キャッシュ・ドライバー',
          'cache_placeholder' => 'キャッシュ・ドライバー',
          'session_label' => 'セッション・ドライバー',
          'session_placeholder' => 'セッション・ドライバー',
          'queue_label' => 'キュー・ドライバー',
          'queue_placeholder' => 'キュー・ドライバー',
          'redis_label' => 'ルディ・ドライバー',
          'redis_host' => '再実行ホスト',
          'redis_password' => 'Redis パスワード',
          'redis_port' => 'ルディ・ポート',
          'mail_label' => 'メール',
          'mail_driver_label' => 'メール・ドライバー',
          'mail_driver_placeholder' => 'メール・ドライバー',
          'mail_host_label' => 'メール・ホスト',
          'mail_host_placeholder' => 'メール・ホスト',
          'mail_port_label' => 'メール・ポート',
          'mail_port_placeholder' => 'メール・ポート',
          'mail_username_label' => 'メール・ユーザー名',
          'mail_username_placeholder' => 'メール・ユーザー名',
          'mail_password_label' => 'メール・パスワード',
          'mail_password_placeholder' => 'メール・パスワード',
          'mail_encryption_label' => 'メール暗号化',
          'mail_encryption_placeholder' => 'メール暗号化',
          'pusher_label' => 'プッシャー',
          'pusher_app_id_label' => 'プッシャー・アプリケーション ID',
          'pusher_app_id_palceholder' => 'プッシャー・アプリケーション ID',
          'pusher_app_key_label' => 'プッシャー・アプリケーション・キー',
          'pusher_app_key_palceholder' => 'プッシャー・アプリケーション・キー',
          'pusher_app_secret_label' => 'プッシャー・アプリケーション秘密',
          'pusher_app_secret_palceholder' => 'プッシャー・アプリケーション秘密',
        ],
        'buttons' => 
        [
          'setup_database' => 'データベースのセットアップ',
          'setup_application' => 'セットアップ・アプリケーション',
          'install' => 'インストール',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'ステップ 3 | 環境設定 | クラシック・エディター',
      'title' => 'クラシック環境エディター',
      'save' => '保存 .env',
      'back' => 'フォームの使用ウィザード',
      'install' => '保存してインストール',
    ],
    'success' => '.env ファイルの設定が保存されました。',
    'errors' => '.env ファイルを保存できません。手動で作成してください。',
  ],
  'install' => 'インストール',
  'installed' => 
  [
    'success_log_message' => 'Laravel インストーラーは正常にインストールされました',
  ],
  'final' => 
  [
    'title' => 'インストール完了',
    'templateTitle' => 'インストール完了',
    'finished' => 'アプリケーションは正常にインストールされました。',
    'migration' => 'マイグレーション &amp; シード・コンソール出力:',
    'console' => 'アプリケーション・コンソール出力:',
    'log' => 'インストール・ログ項目:',
    'env' => '最終 .env ファイル:',
    'exit' => 'ここをクリックして終了',
  ],
  'updater' => 
  [
    'title' => 'ララベル・アップデーター',
    'welcome' => 
    [
      'title' => 'アップデーターへようこそ',
      'message' => '更新ウィザードへようこそ。',
    ],
    'overview' => 
    [
      'title' => '概要',
      'message' => '更新が 1 つあります。 | 番号の更新があります。',
      'install_updates' => '更新のインストール',
    ],
    'final' => 
    [
      'title' => '終了しました',
      'finished' => 'アプリケーションのデータベースが正常に更新されました。',
      'exit' => 'ここをクリックして終了',
    ],
    'log' => 
    [
      'success_message' => 'Laravel インストーラーは正常に更新されました',
    ],
  ],
];