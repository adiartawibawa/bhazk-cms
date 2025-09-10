<?php

return [
    'user' => [
        'navigation' => [
            'group' => 'Manajemen Pengguna',
            'label' => 'Pengguna',
        ],
        'sections' => [
            'avatar'   => 'Foto Profil',
            'basic'    => 'Informasi Dasar',
            'security' => 'Keamanan & Preferensi',
        ],
        'fields' => [
            'avatar' => [
                'label'  => 'Foto Profil',
                'helper' => 'Unggah foto profil (maksimal 2MB)',
            ],
            'username'  => 'Nama Pengguna',
            'email'     => 'Alamat Email',
            'first_name' => 'Nama Depan',
            'last_name' => 'Nama Belakang',
            'password'  => 'Kata Sandi',
            'roles'     => 'Peran',
            'role_name' => 'Nama Peran',
            'timezone'  => 'Zona Waktu',
            'is_active' => [
                'label'  => 'Aktif',
                'helper' => 'Akun pengguna diaktifkan',
            ],
        ],
        'table' => [
            'avatar'     => 'Foto Profil',
            'username'   => 'Nama Pengguna',
            'email'      => 'Email',
            'full_name'  => 'Nama Lengkap',
            'is_active'  => 'Aktif',
            'last_login' => 'Terakhir Masuk',
            'created'    => 'Dibuat',
            'updated'    => 'Diperbarui',
            'created_by' => 'Dibuat Oleh',
            'updated_by' => 'Diperbarui Oleh',
        ],
        'filters' => [
            'role'            => 'Filter berdasarkan Peran',
            'active_users'    => 'Pengguna Aktif',
            'never_logged_in' => 'Belum Pernah Masuk',
            'start_date'      => 'Tanggal Awal',
            'end_date'        => 'Tanggal Akhir',
        ],
        'actions' => [
            'create_role' => [
                'heading' => 'Buat Peran',
                'button'  => 'Buat',
            ],
        ],
        'tabs' => [
            'all'             => 'Semua Pengguna',
            'never_logged_in' => 'Belum Pernah Masuk',
            'is_active'       => 'User Aktif',
        ],
        'view' => [
            'sections' => [
                'profile'   => 'Profil',
                'status'    => 'Status Akun',
                'system'    => 'Informasi Sistem',
            ],
            'fields' => [
                'full_name'     => 'Nama Lengkap',
                'username'      => 'Nama Pengguna',
                'email'         => 'Email',
                'is_active'     => 'Aktif',
                'last_login_ip' => 'IP Login Terakhir',
                'last_login_at' => 'Login Terakhir',
                'timezone'      => 'Zona Waktu',
                'created_at'    => 'Dibuat Pada',
                'updated_at'    => 'Diperbarui Pada',
                'created_by'    => 'Dibuat Oleh',
                'updated_by'    => 'Diperbarui Oleh',
            ],
            'placeholders' => [
                'never_logged_in' => 'Belum Pernah Masuk',
                'empty'           => '-',
            ],
        ],
        'widgets' => [
            'recent_login_activity' => [
                'heading' => 'Aktivitas Login Terbaru',
                'columns' => [
                    'username'   => 'Nama Pengguna',
                    'email'      => 'Email',
                    'role'       => 'Peran',
                    'last_login' => 'Login Terakhir',
                    'activity'   => 'Aktivitas',
                ],
                'placeholders' => [
                    'never_logged_in' => 'Belum Pernah Masuk',
                ],
            ],
            'recent_users' => [
                'heading' => 'Pengguna Terbaru',
                'columns' => [
                    'username'   => 'Nama Pengguna',
                    'email'      => 'Email',
                    'full_name'  => 'Nama Lengkap',
                    'role'       => 'Peran',
                    'is_active'  => 'Aktif',
                    'created_at' => 'Terdaftar',
                ],
                'actions' => [
                    'view' => 'Lihat',
                ],
            ],
            'user_activity_metrics' => [
                'metrics' => [
                    'today_logins' => [
                        'title' => 'Login Hari Ini',
                        'description' => 'Pengguna yang login hari ini',
                    ],
                    'weekly_logins' => [
                        'title' => 'Login Minggu Ini',
                        'description' => 'Pengguna aktif minggu ini',
                    ],
                    'monthly_logins' => [
                        'title' => 'Login Bulan Ini',
                        'description' => 'Pengguna aktif bulan ini',
                    ],
                    'new_users_today' => [
                        'title' => 'Pengguna Baru Hari Ini',
                        'description' => 'Terdaftar hari ini',
                    ],
                    'new_users_week' => [
                        'title' => 'Pengguna Baru Minggu Ini',
                        'description' => 'Terdaftar minggu ini',
                    ],
                    'new_users_month' => [
                        'title' => 'Pengguna Baru Bulan Ini',
                        'description' => 'Terdaftar bulan ini',
                    ],
                ],
            ],
            'user_registration_trend' => [
                'heading' => 'Tren Registrasi Pengguna',
                'filters' => [
                    '7d'  => '7 Hari Terakhir',
                    '30d' => '30 Hari Terakhir',
                    '90d' => '90 Hari Terakhir',
                    'custom' => 'Rentang Kustom',
                ],
                'dataset' => [
                    'label' => 'Registrasi Baru',
                ],
            ],
            'user_role_chart' => [
                'heading' => 'Pengguna Berdasarkan Peran',
                'dataset' => [
                    'label' => 'Pengguna Berdasarkan Peran',
                ],
            ],
            'user_stats_overview' => [
                'stats' => [
                    'total_users' => [
                        'label' => 'Total Pengguna',
                        'description' => 'Semua pengguna terdaftar',
                    ],
                    'active_users' => [
                        'label' => 'Pengguna Aktif',
                        'description' => 'Akun yang aktif',
                    ],
                    'inactive_users' => [
                        'label' => 'Pengguna Tidak Aktif',
                        'description' => 'Akun yang nonaktif',
                    ],
                    'admin_users' => [
                        'label' => 'Pengguna Admin',
                        'description' => 'Akun administrator',
                    ],
                    'never_logged_in' => [
                        'label' => 'Belum Pernah Login',
                        'description' => 'Akun yang belum pernah login',
                    ],
                ],
            ],
        ],
    ],

    'settings' => [
        'navigation' => [
            'group' => 'Sistem',
            'label' => 'Pengaturan',
        ],

        'general' => [
            'navigation' => [
                'group' => 'Konfigurasi Situs',
                'label' => 'Umum',
            ],
            'title' => 'Kelola Pengaturan Umum',
            'sections' => [
                'general' => [
                    'label' => 'Pengaturan Umum',
                    'description' => 'Atur informasi dasar situs dan pengaturan global',
                ],
                'datetime' => [
                    'label' => 'Pengaturan Tanggal & Waktu',
                    'description' => 'Atur bagaimana tanggal dan waktu ditampilkan di seluruh situs',
                ],
            ],
            'fields' => [
                'site_name' => 'Nama Situs',
                'admin_email' => 'Email Admin',
                'site_url' => 'URL Situs',
                'admin_url' => 'URL Admin',
                'date_format' => 'Format Tanggal',
                'time_format' => 'Format Waktu',
                'timezone' => 'Zona Waktu',
                'datetime_preview' => 'Pratinjau Saat Ini',
            ],
            'placeholders' => [
                'admin_url' => 'URL untuk akses dashboard admin',
            ],
            'options' => [
                'date_format' => [
                    'd F Y' => ':format (d F Y)',
                    'd/m/Y' => ':format (d/m/Y)',
                    'm-d-Y' => ':format (m-d-Y)',
                    'Y-m-d' => ':format (Y-m-d)',
                ],
                'time_format' => [
                    'H:i' => ':format (24 jam)',
                    'h:i A' => ':format (12 jam AM/PM)',
                ],
            ],
        ],

        'language' => [
            'navigation' => [
                'group' => 'Konfigurasi Situs',
                'label' => 'Bahasa',
            ],
            'title' => 'Kelola Pengaturan Bahasa',
            'sections' => [
                'language' => [
                    'label' => 'Pengaturan Bahasa',
                    'description' => 'Atur preferensi bahasa untuk aplikasi Anda',
                ],
            ],
            'fields' => [
                'default_language' => 'Bahasa Default',
                'supported_languages' => 'Bahasa yang Didukung',
                'auto_detect_language' => 'Deteksi Bahasa Otomatis',
            ],
            'helpers' => [
                'auto_detect_language' => 'Jika aktif, bahasa pengguna akan dipilih berdasarkan browser/locale mereka',
            ],
            'options' => [
                'languages' => [
                    'id' => 'Bahasa Indonesia',
                    'en' => 'Bahasa Inggris',
                ],
            ],
        ],

        'user' => [
            'navigation' => [
                'group' => 'Manajemen Pengguna',
                'label' => 'Pengguna',
            ],
            'title' => 'Kelola Pengaturan Pengguna',
            'sections' => [
                'registration' => [
                    'label' => 'Registrasi Pengguna',
                    'description' => 'Atur bagaimana pengguna baru dapat mendaftar ke sistem',
                ],
                'roles' => [
                    'label' => 'Peran Default',
                    'description' => 'Atur peran default untuk pengguna baru',
                ],
            ],
            'fields' => [
                'user_registration' => 'Izinkan Registrasi Pengguna',
                'email_verification' => 'Wajib Verifikasi Email',
                'default_user_role' => 'Peran Default untuk Pengguna Baru',
            ],
            'helpers' => [
                'user_registration' => 'Jika diaktifkan, pengguna baru dapat mendaftar sendiri',
                'email_verification' => 'Jika diaktifkan, pengguna harus memverifikasi email setelah mendaftar',
            ],
        ],

        'content' => [
            'navigation' => [
                'group' => 'Manajemen Konten',
                'label' => 'Konten',
            ],
            'title' => 'Kelola Pengaturan Konten',
            'sections' => [
                'front_page' => [
                    'label' => 'Pengaturan Halaman Depan',
                    'description' => 'Atur bagaimana halaman depan ditampilkan kepada pengunjung',
                ],
                'content' => [
                    'label' => 'Pengaturan Konten',
                    'description' => 'Atur bagaimana konten ditampilkan dan dikelola',
                ],
                'comments' => [
                    'label' => 'Pengaturan Komentar',
                    'description' => 'Kelola bagaimana komentar bekerja di situs Anda',
                ],
                'permalinks' => [
                    'label' => 'Pengaturan Permalink',
                    'description' => 'Sesuaikan bagaimana URL konten Anda disusun',
                ],
            ],
            'fields' => [
                'front_page_type' => 'Tampilan Halaman Depan',
                'front_page_id' => 'Halaman Depan',
                'posts_per_page' => 'Jumlah Postingan per Halaman',
                'comment_status' => 'Izinkan Komentar',
                'comment_moderation' => 'Wajib Moderasi Komentar',
                'permalink_structure' => 'Struktur Permalink',
                'permalink_preview' => 'Pratinjau',
            ],
            'helpers' => [
                'front_page_id' => 'Pilih halaman yang digunakan sebagai halaman depan (jika menggunakan halaman statis)',
                'posts_per_page' => 'Jumlah posting yang ditampilkan di halaman arsip',
                'comment_moderation' => 'Semua komentar harus disetujui admin sebelum diterbitkan',
                'permalink_preview' => 'Ini adalah bagaimana URL posting Anda akan muncul',
            ],
            'options' => [
                'front_page_type' => [
                    'latest_posts' => 'Postingan Terbaru',
                    'static_page' => 'Halaman Statis',
                ],
                'permalink_structure' => [
                    'postname' => 'Nama Postingan (/contoh-post/)',
                    'post_id' => 'ID Postingan (/123/)',
                    'date_postname' => 'Tanggal dan Nama (/2025/08/contoh-post/)',
                ],
            ],
            'placeholders' => [
                'posts_per_page' => 'postingan',
            ],
        ],

        'appearance' => [
            'navigation' => [
                'group' => 'Konfigurasi Situs',
                'label' => 'Tampilan',
            ],
            'title' => 'Kelola Pengaturan Tampilan',
            'tabs' => [
                'theme' => [
                    'label' => 'Pemilihan Tema',
                    'icon' => 'heroicon-o-swatch',
                ],
                'custom_code' => [
                    'label' => 'Kode Kustom',
                    'icon' => 'heroicon-o-code-bracket',
                ],
                'preview' => [
                    'label' => 'Pratinjau',
                    'icon' => 'heroicon-o-eye',
                ],
            ],
            'sections' => [
                'theme' => [
                    'label' => 'Konfigurasi Tema',
                    'description' => 'Pilih dan sesuaikan tema visual untuk aplikasi Anda',
                ],
                'custom_code' => [
                    'label' => 'Kustomisasi Styling',
                    'description' => 'Tambahkan CSS dan JavaScript sendiri untuk menyesuaikan tampilan',
                ],
                'preview' => [
                    'label' => 'Pratinjau Tema',
                    'description' => 'Lihat bagaimana tema akan terlihat dengan pengaturan saat ini',
                ],
            ],
            'fields' => [
                'active_theme' => 'Tema Aktif',
                'primary_color' => 'Warna Utama',
                'secondary_color' => 'Warna Sekunder',
                'custom_css' => 'CSS Kustom',
                'custom_js' => 'JavaScript Kustom',
            ],
            'helpers' => [
                'active_theme' => 'Pilih tema utama yang akan digunakan di seluruh aplikasi',
                'primary_color' => 'Warna merek utama digunakan untuk tombol dan tautan',
                'secondary_color' => 'Warna sekunder digunakan untuk aksen dan sorotan',
                'custom_css' => 'Tambahkan CSS kustom yang akan dimuat di seluruh aplikasi',
                'custom_js' => 'Tambahkan JavaScript kustom yang akan dijalankan di seluruh aplikasi',
            ],
            'options' => [
                'themes' => [
                    'default' => 'Tema Default',
                    'dark' => 'Tema Gelap',
                    'light' => 'Tema Terang',
                    'custom' => 'Tema Kustom',
                ],
            ],
            'placeholders' => [
                'custom_css' => '/* Tambahkan gaya kustom Anda di sini */',
                'custom_js' => '// Tambahkan skrip kustom Anda di sini',
            ],
        ],

        'developer' => [
            'navigation' => [
                'group' => 'Pengaturan Lanjutan',
                'label' => 'Pengembang',
            ],
            'title' => 'Kelola Pengaturan Pengembang',
            'sections' => [
                'system' => [
                    'label' => 'Pengaturan Sistem',
                    'description' => 'Atur perilaku sistem dan penanganan error',
                ],
                'debug_info' => [
                    'label' => 'Informasi Debug',
                    'description' => 'Status sistem saat ini dan rekomendasi',
                ],
            ],
            'fields' => [
                'debug_mode' => 'Aktifkan Mode Debug',
                'maintenance_mode' => 'Mode Pemeliharaan',
                'error_logging' => 'Aktifkan Pencatatan Error',
                'debug_status' => 'Status Mode Debug',
                'maintenance_status' => 'Status Mode Pemeliharaan',
            ],
            'helpers' => [
                'debug_mode' => 'Jika diaktifkan, aplikasi akan menampilkan pesan error detail (disarankan untuk pengembangan saja)',
                'maintenance_mode' => 'Jika diaktifkan, semua pengguna akan dialihkan ke halaman pemeliharaan',
                'error_logging' => 'Jika diaktifkan, semua error akan dicatat di file log Laravel',
            ],
            'status_messages' => [
                'debug_enabled' => '⚠️ Mode Debug AKTIF - Tidak disarankan untuk produksi',
                'debug_disabled' => '✅ Mode Debug nonaktif - Disarankan untuk produksi',
                'maintenance_enabled' => '🔧 Mode Pemeliharaan AKTIF - Pengguna akan melihat halaman pemeliharaan',
                'maintenance_disabled' => '✅ Mode Pemeliharaan nonaktif - Situs dapat diakses semua pengguna',
            ],
        ],

        'analytics' => [
            'navigation' => [
                'group' => 'Analitik & Pelacakan',
                'label' => 'Analitik',
            ],
            'title' => 'Kelola Pengaturan Analitik',
            'sections' => [
                'general_tracking' => [
                    'label' => 'Pelacakan Umum',
                    'description' => 'Atur kode pelacakan Google Analytics, Tag Manager, dan Facebook Pixel',
                ],
                'social_login' => [
                    'label' => 'Login Sosial',
                    'description' => 'Aktifkan atau nonaktifkan opsi login media sosial',
                ],
                'google_api' => [
                    'label' => 'Konfigurasi API Google Analytics',
                    'description' => 'Pengaturan integrasi API Google Analytics v4',
                ],
            ],
            'fields' => [
                'key' => 'Key',
                'value' => 'Value',
                'add_credential' => 'Tambahkan Kredensial',
                'minutes' => 'Menit',
                'google_analytics_id' => 'ID Google Analytics',
                'google_tag_manager_id' => 'ID Google Tag Manager',
                'facebook_pixel_id' => 'ID Facebook Pixel',
                'social_login_enabled' => 'Aktifkan Login Sosial',
                'google_analytics_property_id' => 'ID Properti Google Analytics',
                'google_analytics_credentials_path' => 'Path JSON Kredensial',
                'google_analytics_credentials' => 'Kredensial Google Analytics (JSON)',
                'google_analytics_cache_duration' => 'Durasi Cache (menit)',
            ],
            'helpers' => [
                'social_login_enabled' => 'Jika diaktifkan, pengguna dapat login menggunakan akun media sosial mereka',
                'google_analytics_credentials_path' => 'Path file kredensial JSON di server',
                'google_analytics_credentials' => 'Masukkan kredensial secara manual jika tidak menggunakan file kredensial',
                'google_analytics_cache_duration' => 'Berapa lama data analitik disimpan sebelum diperbarui',
            ],
            'placeholders' => [
                'google_analytics_id' => 'UA-XXXXXXXXX-X',
                'google_tag_manager_id' => 'GTM-XXXXXXX',
                'facebook_pixel_id' => '123456789012345',
                'google_analytics_property_id' => 'properties/123456789',
                'google_analytics_credentials_path' => 'storage/app/analytics/credentials.json',
            ],
        ],

        'media' => [
            'navigation' => [
                'group' => 'Manajemen Konten',
                'label' => 'Media',
            ],
            'title' => 'Kelola Pengaturan Media',
            'sections' => [
                'upload_organization' => [
                    'label' => 'Organisasi Upload',
                    'description' => 'Atur bagaimana file media diorganisir di penyimpanan',
                ],
                'image_sizes' => [
                    'label' => 'Ukuran Gambar',
                    'description' => 'Dimensi default untuk thumbnail, medium, dan gambar besar',
                ],
                'upload_limit' => [
                    'label' => 'Batas Upload',
                    'description' => 'Atur ukuran maksimum untuk file media yang diunggah',
                ],
            ],
            'fields' => [
                'upload_organization' => 'Organisasi Upload',
                'storage_disk' => 'Disk Penyimpanan',
                'thumbnail_size' => 'Ukuran Thumbnail',
                'medium_size' => 'Ukuran Medium',
                'large_size' => 'Ukuran Besar',
                'max_upload_size' => 'Ukuran Maksimum Upload',
                'width' => 'Lebar (px)',
                'height' => 'Tinggi (px)',
            ],
            'helpers' => [
                'upload_organization' => 'Struktur folder untuk menyimpan file yang diunggah',
                'storage_disk' => 'Pilih disk filesystem untuk menyimpan file yang diunggah',
                'max_upload_size' => 'Ukuran file maksimum yang diizinkan untuk diunggah (dalam kilobyte)',
            ],
            'options' => [
                'upload_organization' => [
                    'flat' => 'Semua file di satu folder',
                    'year' => 'Folder per tahun',
                    'year_month' => 'Folder per tahun/bulan',
                ],
            ],
            'placeholders' => [
                'max_upload_size' => 'KB',
            ],
        ],

        'seo' => [
            'navigation' => [
                'group' => 'Konfigurasi Situs',
                'label' => 'Optimasi Mesin Pencari',
            ],
            'title' => 'Kelola Pengaturan SEO',
            'tabs' => [
                'meta' => [
                    'label' => 'Meta',
                    'icon' => 'heroicon-o-document-text',
                ],
                'identity' => [
                    'label' => 'Identitas Situs',
                    'icon' => 'heroicon-o-photo',
                ],
                'robots' => [
                    'label' => 'Robots',
                    'icon' => 'heroicon-o-cpu-chip',
                ],
            ],
            'sections' => [
                'meta' => [
                    'label' => 'Informasi Meta',
                    'description' => 'Tag meta SEO umum untuk website Anda',
                ],
                'branding' => [
                    'label' => 'Branding',
                    'description' => 'Logo dan favicon untuk SEO dan sharing',
                ],
                'robots_config' => [
                    'label' => 'Konfigurasi Robots.txt',
                    'description' => 'Kontrol bagaimana mesin pencari merayapi situs Anda',
                ],
            ],
            'fields' => [
                'meta_title' => 'Judul Meta',
                'meta_description' => 'Deskripsi Meta',
                'meta_keywords' => 'Kata Kunci Meta',
                'site_logo' => 'Logo Situs',
                'site_favicon' => 'Favicon',
                'robots_txt' => 'robots.txt',
            ],
            'helpers' => [
                'meta_title' => 'Panjang optimal: 50–60 karakter',
                'meta_description' => 'Panjang optimal: 150–160 karakter',
                'site_logo' => 'Disarankan: SVG atau PNG, background transparan',
                'site_favicon' => 'Disarankan: ICO/PNG 32x32 atau 64x64',
                'robots_txt' => 'Tentukan aturan untuk crawler mesin pencari',
            ],
            'placeholders' => [
                'meta_keywords' => 'cms, manajemen konten, website',
            ],
        ],
    ],

];
