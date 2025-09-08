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
];
