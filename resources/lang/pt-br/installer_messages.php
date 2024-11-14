<?php return [
  'title' => 'Instalador Laravel ',
  'next' => 'Próxima Etapa ',
  'back' => 'Anterior ',
  'finish' => 'Instalação ',
  'forms' => 
  [
    'errorTitle' => 'Ocorreram os seguintes erros: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Bem-vindo ',
    'title' => 'Instalador Laravel ',
    'message' => 'Fácil Assistente de Instalação e Configuração. ',
    'next' => 'Verificar Requisitos ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Etapa 1 | Requisitos do Servidor ',
    'title' => 'Requisitos do Servidor ',
    'next' => 'Verificar Permissões ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Etapa 2 | Permissões ',
    'title' => 'Permissões ',
    'next' => 'Configurar Ambiente ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Etapa 3 | Configurações do Ambiente ',
      'title' => 'Configurações do Ambiente ',
      'desc' => 'Por favor, selecione como você deseja configurar o arquivo apps <code>.env</code> . ',
      'wizard-button' => 'Configuração do Assistente de Formul ',
      'classic-button' => 'Editor de Texto clássico ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Etapa 3 | Configurações do Meio Ambiente | Assistente Orientado ',
      'title' => 'Assistente Guiado <code>.env</code> ',
      'tabs' => 
      [
        'environment' => 'Ambiente ',
        'database' => 'Banco de ',
        'application' => 'Aplicação ',
      ],
      'form' => 
      [
        'name_required' => 'Um nome de ambiente é necessário. ',
        'app_name_label' => 'Nome do app ',
        'app_name_placeholder' => 'Nome do app ',
        'app_environment_label' => 'Ambiente do App ',
        'app_environment_label_local' => 'Local ',
        'app_environment_label_developement' => 'Desenvolvimento ',
        'app_environment_label_qa' => 'Qa ',
        'app_environment_label_production' => 'Produção ',
        'app_environment_label_other' => 'Outro ',
        'app_environment_placeholder_other' => 'Digite seu ambiente ... ',
        'app_debug_label' => 'Debug do app ',
        'app_debug_label_true' => 'True ',
        'app_debug_label_false' => 'Falso ',
        'app_log_level_label' => 'Nível de Log de Aplicativos ',
        'app_log_level_label_debug' => 'debug ',
        'app_log_level_label_info' => 'info ',
        'app_log_level_label_notice' => 'aviso prévio ',
        'app_log_level_label_warning' => 'aviso ',
        'app_log_level_label_error' => 'erro ',
        'app_log_level_label_critical' => 'crítico ',
        'app_log_level_label_alert' => 'alerta ',
        'app_log_level_label_emergency' => 'emergência ',
        'app_url_label' => 'Url Do App ',
        'app_url_placeholder' => 'Url Do App ',
        'db_connection_failed' => 'Não foi possível conectar-se ao banco de dados. ',
        'db_connection_label' => 'Conexão do Banco ',
        'db_connection_label_mysql' => 'mysql ',
        'db_connection_label_sqlite' => 'sqlite ',
        'db_connection_label_pgsql' => 'pgsql ',
        'db_connection_label_sqlsrv' => 'sqlsrv ',
        'db_host_label' => 'Host de Dados ',
        'db_host_placeholder' => 'Host de Dados ',
        'db_port_label' => 'Porta de Bancos ',
        'db_port_placeholder' => 'Porta de Bancos ',
        'db_name_label' => 'Nome do Banco ',
        'db_name_placeholder' => 'Nome do Banco ',
        'db_username_label' => 'Nome do Usuário do Banco ',
        'db_username_placeholder' => 'Nome do Usuário do Banco ',
        'db_password_label' => 'Senha do Banco ',
        'db_password_placeholder' => 'Senha do Banco ',
        'app_tabs' => 
        [
          'more_info' => 'Mais Info ',
          'broadcasting_title' => 'Radiodifusão, Caching, Sessão, &amp; Fila ',
          'broadcasting_label' => 'Driver de Transm ',
          'broadcasting_placeholder' => 'Driver de Transm ',
          'cache_label' => 'Driver de Cache ',
          'cache_placeholder' => 'Driver de Cache ',
          'session_label' => 'Driver de Sessão ',
          'session_placeholder' => 'Driver de Sessão ',
          'queue_label' => 'Driver de Fila ',
          'queue_placeholder' => 'Driver de Fila ',
          'redis_label' => 'Driver de Redis ',
          'redis_host' => 'Host da Redis ',
          'redis_password' => 'Senha do Redis ',
          'redis_port' => 'Porta Redis ',
          'mail_label' => 'Correio ',
          'mail_driver_label' => 'Driver de Corre ',
          'mail_driver_placeholder' => 'Driver de Corre ',
          'mail_host_label' => 'Host de e-mail ',
          'mail_host_placeholder' => 'Host de e-mail ',
          'mail_port_label' => 'Porta de e-mail ',
          'mail_port_placeholder' => 'Porta de e-mail ',
          'mail_username_label' => 'Nome de Username ',
          'mail_username_placeholder' => 'Nome de Username ',
          'mail_password_label' => 'Senha de e-mail ',
          'mail_password_placeholder' => 'Senha de e-mail ',
          'mail_encryption_label' => 'Criptografia De Correio ',
          'mail_encryption_placeholder' => 'Criptografia De Correio ',
          'pusher_label' => 'Empurrador ',
          'pusher_app_id_label' => 'Id do App Ppusher ',
          'pusher_app_id_palceholder' => 'Id do App Ppusher ',
          'pusher_app_key_label' => 'Chave do App Ppusher ',
          'pusher_app_key_palceholder' => 'Chave do App Ppusher ',
          'pusher_app_secret_label' => 'Pusher App Secret ',
          'pusher_app_secret_palceholder' => 'Pusher App Secret ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Banco de Dados ',
          'setup_application' => 'Aplicativo de Configuração ',
          'install' => 'Instalação ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Etapa 3 | Configurações do Ambiente | Editor Classic ',
      'title' => 'Editor de Ambiente clássico ',
      'save' => 'Salvar .env ',
      'back' => 'Assistente de Uso de Uso ',
      'install' => 'Salvar e Instalar ',
    ],
    'success' => 'Suas configurações de arquivo .env foram salvas. ',
    'errors' => 'Não é possível salvar o arquivo .env, crie-o manualmente. ',
  ],
  'install' => 'Instalação ',
  'installed' => 
  [
    'success_log_message' => 'Laravel Installer INSTALADO com sucesso ',
  ],
  'final' => 
  [
    'title' => 'Instalação Concluída ',
    'templateTitle' => 'Instalação Concluída ',
    'finished' => 'Aplicativo foi instalado com sucesso. ',
    'migration' => 'Saída do Console de Migração &amp; Seed: ',
    'console' => 'Saída do Console de Aplicativos: ',
    'log' => 'Entrada de Log de Instalação: ',
    'env' => 'Arquivo final .env: ',
    'exit' => 'Clique aqui para sair ',
  ],
  'updater' => 
  [
    'title' => 'Atualizador De Laravel ',
    'welcome' => 
    [
      'title' => 'Bem-vindo Ao Atualizador ',
      'message' => 'Bem-vindo ao assistente de atualização. ',
    ],
    'overview' => 
    [
      'title' => 'Visão geral ',
      'message' => 'Há atualização de 1. | Há: atualizações de número. ',
      'install_updates' => 'Instalar Atualizações ',
    ],
    'final' => 
    [
      'title' => 'Concluído ',
      'finished' => 'O banco de dados do aplicativo foi atualizado com sucesso. ',
      'exit' => 'Clique aqui para sair ',
    ],
    'log' => 
    [
      'success_message' => 'Laravel Installer ATUALIZADO com sucesso',
    ],
  ],
];