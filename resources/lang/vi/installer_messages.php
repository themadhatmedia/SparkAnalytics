<?php return [
  'title' => 'Trình cài đặt Laravel ',
  'next' => 'Bước tiếp theo ',
  'back' => 'Trước ',
  'finish' => 'Cài đặt ',
  'forms' => 
  [
    'errorTitle' => 'Ðã xảy ra lỗi sau: ',
  ],
  'welcome' => 
  [
    'templateTitle' => 'Chào mừng ',
    'title' => 'Trình cài đặt Laravel ',
    'message' => 'Cài đặt dễ dàng và thiết lập Wizard. ',
    'next' => 'Yêu cầu kiểm tra ',
  ],
  'requirements' => 
  [
    'templateTitle' => 'Bước 1 | Máy chủ yêu cầu ',
    'title' => 'Yêu cầu máy chủ ',
    'next' => 'Kiểm tra quyền ',
  ],
  'permissions' => 
  [
    'templateTitle' => 'Bước 2 | Permissis ',
    'title' => 'Quyền ',
    'next' => 'Cấu hình môi ',
  ],
  'environment' => 
  [
    'menu' => 
    [
      'templateTitle' => 'Bước 3 | Cài đặt môi trường ',
      'title' => 'Thiết lập môi ',
      'desc' => 'Vui lòng chọn cách bạn muốn cấu hình tập tin ứng dụng <code>.env</code> . ',
      'wizard-button' => 'Cài đặt Wizard ',
      'classic-button' => 'Trình soạn thảo văn bản ',
    ],
    'wizard' => 
    [
      'templateTitle' => 'Bước 3 | Cài đặt môi trường | Cài đặt wizard ',
      'title' => 'Wizard <code>.env</code> ',
      'tabs' => 
      [
        'environment' => 'Môi trường ',
        'database' => 'Dữ liệu ',
        'application' => 'Ứng dụng ',
      ],
      'form' => 
      [
        'name_required' => 'Tên môi trường được yêu cầu. ',
        'app_name_label' => 'Tên ứng dụng ',
        'app_name_placeholder' => 'Tên ứng dụng ',
        'app_environment_label' => 'Môi trường Ứng ',
        'app_environment_label_local' => 'Địa phương ',
        'app_environment_label_developement' => 'Phát triển ',
        'app_environment_label_qa' => 'Qa ',
        'app_environment_label_production' => 'Sản xuất ',
        'app_environment_label_other' => 'Khác ',
        'app_environment_placeholder_other' => 'Nhập môi trường của bạn ... ',
        'app_debug_label' => 'Lỗi ứng dụng ',
        'app_debug_label_true' => 'Đúng. ',
        'app_debug_label_false' => 'Sai ',
        'app_log_level_label' => 'Mức nhật ký ứng dụng ',
        'app_log_level_label_debug' => 'Gỡ lỗi ',
        'app_log_level_label_info' => 'thông tin ',
        'app_log_level_label_notice' => 'thông báo ',
        'app_log_level_label_warning' => 'cảnh báo ',
        'app_log_level_label_error' => 'lỗi ',
        'app_log_level_label_critical' => 'Quan trọng ',
        'app_log_level_label_alert' => 'cảnh báo ',
        'app_log_level_label_emergency' => 'Khẩn cấp ',
        'app_url_label' => 'Url Ứng Dụng ',
        'app_url_placeholder' => 'Url Ứng Dụng ',
        'db_connection_failed' => 'Không thể kết nối đến cơ sở dư ̃ liệu. ',
        'db_connection_label' => 'Kết Nối Cơ ',
        'db_connection_label_mysql' => 'Mysql ',
        'db_connection_label_sqlite' => 'Sqlite ',
        'db_connection_label_pgsql' => 'pgsql ',
        'db_connection_label_sqlsrv' => 'sqlsrv ',
        'db_host_label' => 'Máy chủ co ',
        'db_host_placeholder' => 'Máy chủ co ',
        'db_port_label' => 'Cổng cơ sở ',
        'db_port_placeholder' => 'Cổng cơ sở ',
        'db_name_label' => 'Tên Cơ sở ',
        'db_name_placeholder' => 'Tên Cơ sở ',
        'db_username_label' => 'Tên Người dùng Cơ sở ',
        'db_username_placeholder' => 'Tên Người dùng Cơ sở ',
        'db_password_label' => 'Mật Khẩu ',
        'db_password_placeholder' => 'Mật Khẩu ',
        'app_tabs' => 
        [
          'more_info' => 'Thêm thông tin ',
          'broadcasting_title' => 'Phát thanh, Lưu trữ, Phiên chạy, &amp; Hàng đợi ',
          'broadcasting_label' => 'Trình điều khiển ',
          'broadcasting_placeholder' => 'Trình điều khiển ',
          'cache_label' => 'Bộ nhớ tạm ',
          'cache_placeholder' => 'Bộ nhớ tạm ',
          'session_label' => 'Tài xế phiên ',
          'session_placeholder' => 'Tài xế phiên ',
          'queue_label' => 'Trình điều khiển ',
          'queue_placeholder' => 'Trình điều khiển ',
          'redis_label' => 'Trình điều khiển soạn thảo ',
          'redis_host' => 'Máy chủ Redis ',
          'redis_password' => 'Mật khẩu Redis ',
          'redis_port' => 'Cổng Redis ',
          'mail_label' => 'Thư mục ',
          'mail_driver_label' => 'Tài xế thư ',
          'mail_driver_placeholder' => 'Tài xế thư ',
          'mail_host_label' => 'Máy chủ thư ',
          'mail_host_placeholder' => 'Máy chủ thư ',
          'mail_port_label' => 'Cổng thư ',
          'mail_port_placeholder' => 'Cổng thư ',
          'mail_username_label' => 'Tên người dùng thư ',
          'mail_username_placeholder' => 'Tên người dùng thư ',
          'mail_password_label' => 'Mật khẩu thư ',
          'mail_password_placeholder' => 'Mật khẩu thư ',
          'mail_encryption_label' => 'Mã hóa thư ',
          'mail_encryption_placeholder' => 'Mã hóa thư ',
          'pusher_label' => 'Pusher ',
          'pusher_app_id_label' => 'Id ứng dụng Pusher ',
          'pusher_app_id_palceholder' => 'Id ứng dụng Pusher ',
          'pusher_app_key_label' => 'Khóa ứng dụng Pusher ',
          'pusher_app_key_palceholder' => 'Khóa ứng dụng Pusher ',
          'pusher_app_secret_label' => 'Secret% 1 ',
          'pusher_app_secret_palceholder' => 'Secret% 1 ',
        ],
        'buttons' => 
        [
          'setup_database' => 'Cơ sở dữ ',
          'setup_application' => 'Comment ',
          'install' => 'Cài đặt ',
        ],
      ],
    ],
    'classic' => 
    [
      'templateTitle' => 'Bước 3 | Môi trường thiết lập | Cổ điển biên tập ',
      'title' => 'Trình soạn thảo môi trường ',
      'save' => 'Lưu .env ',
      'back' => 'Dùng pháp sư biểu mẫu ',
      'install' => 'Lưu và Cài đặt ',
    ],
    'success' => 'Cài đặt tập tin .env đã được lưu. ',
    'errors' => 'Không thể lưu tập tin .env, vui lòng tạo nó thủ công. ',
  ],
  'install' => 'Cài đặt ',
  'installed' => 
  [
    'success_log_message' => 'Trình cài đặt laravel được cài đặt thành công vào ',
  ],
  'final' => 
  [
    'title' => 'Cài đặt đã hoàn tất ',
    'templateTitle' => 'Cài đặt đã hoàn tất ',
    'finished' => 'Ứng dụng đã được cài đặt thành công. ',
    'migration' => 'Di chuyển &amp; Sản xuất Bảng điều khiển Seed: ',
    'console' => 'Bộ điều khiển ứng dụng: ',
    'log' => 'Mục nhật ký cài đặt: ',
    'env' => 'Tập tin cuối cùng .env: ',
    'exit' => 'Nhấp vào đây để thoát ',
  ],
  'updater' => 
  [
    'title' => 'Laravel Updater ',
    'welcome' => 
    [
      'title' => 'Chào Mừng Đến Với Người Cập Nhật ',
      'message' => 'Chào mừng đến với wizard cập nhật. ',
    ],
    'overview' => 
    [
      'title' => 'Tổng quan ',
      'message' => 'Có 1 bản cập nhật. | Có: các bản cập nhật. ',
      'install_updates' => 'Cài đặt cập nhật ',
    ],
    'final' => 
    [
      'title' => 'Đã xong ',
      'finished' => 'Cơ sở dữ liệu ứng dụng đã được cập nhật thành công ',
      'exit' => 'Nhấp vào đây để thoát ',
    ],
    'log' => 
    [
      'success_message' => 'Trình cài đặt laravel được cập nhật thành công vào',
    ],
  ],
];