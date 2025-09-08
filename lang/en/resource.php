<?php

return [
    'user' => [
        'navigation' => [
            'group' => 'User Management',
            'label' => 'Users',
        ],
        'sections' => [
            'avatar' => 'Avatar',
            'basic' => 'Basic Information',
            'security' => 'Security & Preferences',
        ],
        'fields' => [
            'avatar' => [
                'label' => 'Profile Picture',
                'helper' => 'Upload a profile picture (max 2MB)',
            ],
            'username' => 'Username',
            'email' => 'Email Address',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'password' => 'Password',
            'roles' => 'Roles',
            'role_name' => 'Role Name',
            'timezone' => 'Timezone',
            'is_active' => [
                'label' => 'Active',
                'helper' => 'User account is enabled',
            ],
        ],
        'table' => [
            'avatar' => 'Avatar',
            'username' => 'Username',
            'email' => 'Email',
            'full_name' => 'Full Name',
            'is_active' => 'Active',
            'last_login' => 'Last Login',
            'created' => 'Created',
            'updated' => 'Updated',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ],
        'filters' => [
            'role' => 'Filter by Role',
            'active_users' => 'Active Users',
            'never_logged_in' => 'Never Logged In',
        ],
        'actions' => [
            'create_role' => [
                'heading' => 'Create Role',
                'button' => 'Create',
            ],
        ],
        'tabs' => [
            'all'             => 'All Users',
            'never_logged_in' => 'Never Logged In',
        ],
        'view' => [
            'sections' => [
                'profile'   => 'Profile',
                'status'    => 'Account Status',
                'system'    => 'System Information',
            ],
            'fields' => [
                'full_name'     => 'Full Name',
                'username'      => 'Username',
                'email'         => 'Email',
                'is_active'     => 'Active',
                'last_login_ip' => 'Last Login IP',
                'last_login_at' => 'Last Login',
                'timezone'      => 'Timezone',
                'created_at'    => 'Created At',
                'updated_at'    => 'Updated At',
                'created_by'    => 'Created By',
                'updated_by'    => 'Updated By',
            ],
            'placeholders' => [
                'never_logged_in' => 'Never logged in',
                'empty'           => '-',
            ],
        ],
        'widgets' => [
            'recent_login_activity' => [
                'heading' => 'Recent Login Activity',
                'columns' => [
                    'username'   => 'Username',
                    'email'      => 'Email',
                    'role'       => 'Role',
                    'last_login' => 'Last Login',
                    'activity'   => 'Activity',
                ],
                'placeholders' => [
                    'never_logged_in' => 'Never logged in',
                ],
            ],
            'recent_users' => [
                'heading' => 'Recent Users',
                'columns' => [
                    'username'   => 'Username',
                    'email'      => 'Email',
                    'full_name'  => 'Full Name',
                    'role'       => 'Role',
                    'is_active'  => 'Active',
                    'created_at' => 'Registered',
                ],
                'actions' => [
                    'view' => 'View',
                ],
            ],
            'user_activity_metrics' => [
                'metrics' => [
                    'today_logins' => [
                        'title' => 'Today Logins',
                        'description' => 'Users logged in today',
                    ],
                    'weekly_logins' => [
                        'title' => 'Weekly Logins',
                        'description' => 'Active users this week',
                    ],
                    'monthly_logins' => [
                        'title' => 'Monthly Logins',
                        'description' => 'Active users this month',
                    ],
                    'new_users_today' => [
                        'title' => 'New Users Today',
                        'description' => 'Registered today',
                    ],
                    'new_users_week' => [
                        'title' => 'New Users This Week',
                        'description' => 'Registered this week',
                    ],
                    'new_users_month' => [
                        'title' => 'New Users This Month',
                        'description' => 'Registered this month',
                    ],
                ],
            ],
            'user_registration_trend' => [
                'heading' => 'User Registration Trend',
                'filters' => [
                    '7d'  => 'Last 7 Days',
                    '30d' => 'Last 30 Days',
                    '90d' => 'Last 90 Days',
                ],
                'dataset' => [
                    'label' => 'New Registrations',
                ],
            ],
            'user_role_chart' => [
                'heading' => 'Users by Role',
                'dataset' => [
                    'label' => 'Users by Role',
                ],
            ],
            'user_stats_overview' => [
                'stats' => [
                    'total_users' => [
                        'label' => 'Total Users',
                        'description' => 'All registered users',
                    ],
                    'active_users' => [
                        'label' => 'Active Users',
                        'description' => 'Enabled accounts',
                    ],
                    'inactive_users' => [
                        'label' => 'Inactive Users',
                        'description' => 'Disabled accounts',
                    ],
                    'admin_users' => [
                        'label' => 'Admin Users',
                        'description' => 'Administrator accounts',
                    ],
                    'never_logged_in' => [
                        'label' => 'Never Logged In',
                        'description' => 'Accounts that never logged in',
                    ],
                ],
            ],
        ],
    ],
];
