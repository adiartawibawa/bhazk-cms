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
            'start_date'      => 'Start Date',
            'end_date'        => 'End Date',
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
            'is_active'       => 'Active Users',
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
                    'custom' => 'Custom Range',
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

    'content' => [
        'navigation' => [
            'group' => 'Content Management',
            'label' => 'Content',
        ],
        'title' => 'Manage Content Settings',

        'sections' => [
            'information' => [
                'label' => 'Content Information',
                'description' => 'Basic details about your content',
            ],
            'body' => [
                'label' => 'Content Body',
                'description' => 'Main content fields',
            ],
            'summary' => [
                'label' => 'Summary & Metadata',
                'description' => 'Excerpt and metadata fields',
            ],
            'settings' => [
                'label' => 'Content Settings',
                'description' => 'Configure extra options',
            ],
            'categorization' => [
                'label' => 'Categorization',
                'description' => 'Organize content with categories and tags',
            ],
        ],

        'fields' => [
            'content_type_id'   => 'Content Type',
            'status'            => 'Status',
            'title'             => 'Title',
            'slug'              => 'Slug',
            'published_at'      => 'Publish Date',
            'author'            => 'Author',
            'editor'            => 'Editor',
            'excerpt'           => 'Excerpt',
            'metadata'          => 'Metadata',
            'featured'          => 'Featured Content',
            'commentable'       => 'Allow Comments',
            'categories'        => 'Categories',
            'tags'              => 'Tags',
        ],

        'options' => [
            'status' => [
                'draft'     => 'Draft',
                'published' => 'Published',
                'archived'  => 'Archived',
            ],
        ],

        'filters' => [
            'start_date'  => 'Start Date',
            'end_date'    => 'End Date',
            'status'      => 'Status',
            'author'      => 'Author',
            'featured'    => 'Featured Content',
            'published'   => 'Published Content',
            'needs_review' => 'Needs Review',
            'content_type' => 'Content Type',
        ],

        'actions' => [
            'view'      => 'View',
            'edit'      => 'Edit',
            'delete'    => 'Delete',
            'force_delete' => 'Force Delete',
            'restore'   => 'Restore',
            'revisions' => 'Revisions',
            'publish'   => 'Publish Selected',
            'create'    => 'Create Content',
        ],

        'columns' => [
            'title'       => 'Title',
            'type'        => 'Type',
            'status'      => 'Status',
            'author'      => 'Author',
            'featured'    => 'Featured',
            'published'   => 'Published',
            'comments'    => 'Comments',
            'commentable' => 'Commentable',
            'revisions'   => 'Revisions',
            'created'     => 'Created',
            'updated'     => 'Updated',
        ],

        'tabs' => [
            'all' => 'All',
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
            'featured' => 'Featured',
            'commentable' => 'Commentable',
            'needs_review' => 'Needs review',
        ],

        'view' => [
            'actions' => [
                'revisions' => 'Revisions',
            ],
        ],

        'edit' => [
            'actions' => [
                'revisions' => 'Revisions',
            ],
        ],

        'revisions' => [
            'actions' => [
                'back' => 'Back to Content',
                'view' => 'View',
                'restore' => 'Restore',
            ],
            'title' => 'Revisions for: :title',
            'breadcrumb' => 'Revisions',
            'columns' => [
                'version' => 'Version',
                'author' => 'Author',
                'change_type' => 'Change Type',
                'description' => 'Description',
                'date' => 'Date',
                'autosave' => 'Autosave',
            ],
            'modal' => [
                'view_heading' => 'Revision v:version',
                'restore_heading' => 'Restore Revision',
                'restore_description' => 'Are you sure you want to restore this revision? The current content will be replaced with this version.',
            ],
            'empty' => [
                'heading' => 'No revisions yet',
                'description' => 'Revisions will appear here when you make changes to the content.',
            ],
            'restore_message' => 'Restored from version :version',
            'notifications' => [
                'success' => 'Content restored from version :version',
                'error' => 'Failed to restore revision: :message',
            ],
        ],
    ],

    'settings' => [
        'navigation' => [
            'group' => 'System',
            'label' => 'Settings',
        ],

        'general' => [
            'navigation' => [
                'group' => 'Site Configuration',
                'label' => 'General',
            ],
            'title' => 'Manage General Settings',
            'sections' => [
                'general' => [
                    'label' => 'General Settings',
                    'description' => 'Configure basic site information and global settings',
                ],
                'datetime' => [
                    'label' => 'Date & Time Settings',
                    'description' => 'Configure how dates and times are displayed throughout the site',
                ],
            ],
            'fields' => [
                'site_name' => 'Site Name',
                'admin_email' => 'Admin Email',
                'site_url' => 'Site URL',
                'admin_url' => 'Admin URL',
                'date_format' => 'Date Format',
                'time_format' => 'Time Format',
                'timezone' => 'Timezone',
                'datetime_preview' => 'Current Preview',
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
                    'H:i' => ':format (24h)',
                    'h:i A' => ':format (12h AM/PM)',
                ],
            ],
        ],

        'language' => [
            'navigation' => [
                'group' => 'Site Configuration',
                'label' => 'Language',
            ],
            'title' => 'Manage Language Settings',
            'sections' => [
                'language' => [
                    'label' => 'Language Settings',
                    'description' => 'Configure the language preferences for your application',
                ],
            ],
            'fields' => [
                'default_language' => 'Default Language',
                'supported_languages' => 'Supported Languages',
                'auto_detect_language' => 'Auto Detect Language',
            ],
            'helpers' => [
                'auto_detect_language' => 'Jika aktif, bahasa user akan dipilih berdasarkan browser/locale mereka',
            ],
            'options' => [
                'languages' => [
                    'id' => 'Indonesian',
                    'en' => 'English',
                ],
            ],
        ],

        'user' => [
            'navigation' => [
                'group' => 'User Management',
                'label' => 'User',
            ],
            'title' => 'Manage User Settings',
            'sections' => [
                'registration' => [
                    'label' => 'Users Registration',
                    'description' => 'Set how new users can register to the system',
                ],
                'roles' => [
                    'label' => 'Default Roles',
                    'description' => 'Set default roles for new users',
                ],
            ],
            'fields' => [
                'user_registration' => 'Allow User Registration',
                'email_verification' => 'Require Email Verification',
                'default_user_role' => 'Default Role for New Users',
            ],
            'helpers' => [
                'user_registration' => 'If enabled, new users can register themselves',
                'email_verification' => 'If enabled, users must verify their email after registering',
            ],
        ],

        'content' => [
            'navigation' => [
                'group' => 'Content Management',
                'label' => 'Content',
            ],
            'title' => 'Manage Content Settings',
            'sections' => [
                'front_page' => [
                    'label' => 'Front Page Settings',
                    'description' => 'Configure how your front page is displayed to visitors',
                ],
                'content' => [
                    'label' => 'Content Settings',
                    'description' => 'Configure how your content is displayed and managed',
                ],
                'comments' => [
                    'label' => 'Comment Settings',
                    'description' => 'Manage how comments work on your site',
                ],
                'permalinks' => [
                    'label' => 'Permalink Settings',
                    'description' => 'Customize how your content URLs are structured',
                ],
            ],
            'fields' => [
                'front_page_type' => 'Front Page Display',
                'front_page_id' => 'Front Page',
                'posts_per_page' => 'Posts Per Page',
                'comment_status' => 'Allow Comments',
                'comment_moderation' => 'Require Comment Moderation',
                'permalink_structure' => 'Permalink Structure',
                'permalink_preview' => 'Preview',
            ],
            'helpers' => [
                'front_page_id' => 'Select the page to use as your front page (if using static page)',
                'posts_per_page' => 'Number of posts to show on archive pages',
                'comment_moderation' => 'All comments must be approved by an administrator before being published',
                'permalink_preview' => 'This is how your post URLs will appear',
            ],
            'options' => [
                'front_page_type' => [
                    'latest_posts' => 'Your latest posts',
                    'static_page' => 'A static page',
                ],
                'permalink_structure' => [
                    'postname' => 'Post Name (/sample-post/)',
                    'post_id' => 'Post ID (/123/)',
                    'date_postname' => 'Date and Name (/2025/08/sample-post/)',
                ],
            ],
            'placeholders' => [
                'posts_per_page' => 'posts',
            ],
        ],

        'appearance' => [
            'navigation' => [
                'group' => 'Site Configuration',
                'label' => 'Appearance',
            ],
            'title' => 'Manage Appearance Settings',
            'tabs' => [
                'theme' => [
                    'label' => 'Theme Selection',
                    'icon' => 'heroicon-o-swatch',
                ],
                'custom_code' => [
                    'label' => 'Custom Code',
                    'icon' => 'heroicon-o-code-bracket',
                ],
                'preview' => [
                    'label' => 'Preview',
                    'icon' => 'heroicon-o-eye',
                ],
            ],
            'sections' => [
                'theme' => [
                    'label' => 'Theme Configuration',
                    'description' => 'Select and customize the visual theme for your application',
                ],
                'custom_code' => [
                    'label' => 'Custom Styling',
                    'description' => 'Add your own CSS and JavaScript to customize the appearance',
                ],
                'preview' => [
                    'label' => 'Theme Preview',
                    'description' => 'See how your theme will look with the current settings',
                ],
            ],
            'fields' => [
                'active_theme' => 'Active Theme',
                'primary_color' => 'Primary Color',
                'secondary_color' => 'Secondary Color',
                'custom_css' => 'Custom CSS',
                'custom_js' => 'Custom JavaScript',
            ],
            'helpers' => [
                'active_theme' => 'Choose the main theme that will be used throughout the application',
                'primary_color' => 'Main brand color used for buttons and links',
                'secondary_color' => 'Secondary color used for accents and highlights',
                'custom_css' => 'Add custom CSS that will be loaded throughout the application',
                'custom_js' => 'Add custom JavaScript that will run throughout the application',
            ],
            'options' => [
                'themes' => [
                    'default' => 'Default Theme',
                    'dark' => 'Dark Theme',
                    'light' => 'Light Theme',
                    'custom' => 'Custom Theme',
                ],
            ],
            'placeholders' => [
                'custom_css' => '/* Add your custom styles here */',
                'custom_js' => '// Add your custom scripts here',
            ],
        ],

        'developer' => [
            'navigation' => [
                'group' => 'Advanced Settings',
                'label' => 'Developer',
            ],
            'title' => 'Manage Developer Settings',
            'sections' => [
                'system' => [
                    'label' => 'System Settings',
                    'description' => 'Configure system behavior and error handling',
                ],
                'debug_info' => [
                    'label' => 'Debug Information',
                    'description' => 'Current system status and recommendations',
                ],
            ],
            'fields' => [
                'debug_mode' => 'Enable Debug Mode',
                'maintenance_mode' => 'Maintenance Mode',
                'error_logging' => 'Enable Error Logging',
                'debug_status' => 'Debug Mode Status',
                'maintenance_status' => 'Maintenance Mode Status',
            ],
            'helpers' => [
                'debug_mode' => 'When enabled, the application will display detailed error messages (recommended for development only)',
                'maintenance_mode' => 'When enabled, all users will be redirected to the maintenance page',
                'error_logging' => 'When enabled, all errors will be logged to the Laravel log files',
            ],
            'status_messages' => [
                'debug_enabled' => '⚠️ Debug mode is ENABLED - Not recommended for production',
                'debug_disabled' => '✅ Debug mode is disabled - Recommended for production',
                'maintenance_enabled' => '🔧 Maintenance mode is ENABLED - Users will see maintenance page',
                'maintenance_disabled' => '✅ Maintenance mode is disabled - Site is accessible to all users',
            ],
        ],

        'analytics' => [
            'navigation' => [
                'group' => 'Analytics & Tracking',
                'label' => 'Analytics',
            ],
            'title' => 'Manage Analytics Settings',
            'sections' => [
                'general_tracking' => [
                    'label' => 'General Tracking',
                    'description' => 'Configure Google Analytics, Tag Manager, and Facebook Pixel tracking codes',
                ],
                'social_login' => [
                    'label' => 'Social Login',
                    'description' => 'Enable or disable social media authentication options',
                ],
                'google_api' => [
                    'label' => 'Google Analytics API Configuration',
                    'description' => 'Settings for Google Analytics API v4 integration',
                ],
            ],
            'fields' => [
                'key' => 'Key',
                'value' => 'Value',
                'add_credential' => 'Add Credential',
                'minutes' => 'Minutes',
                'google_analytics_id' => 'Google Analytics ID',
                'google_tag_manager_id' => 'Google Tag Manager ID',
                'facebook_pixel_id' => 'Facebook Pixel ID',
                'social_login_enabled' => 'Enable Social Login',
                'google_analytics_property_id' => 'Google Analytics Property ID',
                'google_analytics_credentials_path' => 'Credentials JSON Path',
                'google_analytics_credentials' => 'Google Analytics Credentials (JSON)',
                'google_analytics_cache_duration' => 'Cache Duration (minutes)',
            ],
            'helpers' => [
                'social_login_enabled' => 'When enabled, users can log in using their social media accounts',
                'google_analytics_credentials_path' => 'Path to the JSON credentials file on the server',
                'google_analytics_credentials' => 'Enter credentials manually if not using a credentials file',
                'google_analytics_cache_duration' => 'How long to cache analytics data before refreshing',
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
                'group' => 'Content Management',
                'label' => 'Media',
            ],
            'title' => 'Manage Media Settings',
            'sections' => [
                'upload_organization' => [
                    'label' => 'Upload Organization',
                    'description' => 'Configure how media files are organized in storage',
                ],
                'image_sizes' => [
                    'label' => 'Image Sizes',
                    'description' => 'Default dimensions for thumbnail, medium, and large images',
                ],
                'upload_limit' => [
                    'label' => 'Upload Limit',
                    'description' => 'Configure maximum upload size for media files',
                ],
            ],
            'fields' => [
                'upload_organization' => 'Upload Organization',
                'storage_disk' => 'Storage Disk',
                'thumbnail_size' => 'Thumbnail Size',
                'medium_size' => 'Medium Size',
                'large_size' => 'Large Size',
                'max_upload_size' => 'Maximum Upload Size',
                'width' => 'Width (px)',
                'height' => 'Height (px)',
            ],
            'helpers' => [
                'upload_organization' => 'Folder structure for storing uploaded files',
                'storage_disk' => 'Select which filesystem disk to use for storing uploaded files',
                'max_upload_size' => 'Maximum file size allowed for upload (in kilobytes)',
            ],
            'options' => [
                'upload_organization' => [
                    'flat' => 'All files in single folder',
                    'year' => 'Folder per year',
                    'year_month' => 'Folder per year/month',
                ],
            ],
            'placeholders' => [
                'max_upload_size' => 'KB',
            ],
        ],

        'seo' => [
            'navigation' => [
                'group' => 'Site Configuration',
                'label' => 'Search Optimization',
            ],
            'title' => 'Manage SEO Settings',
            'tabs' => [
                'meta' => [
                    'label' => 'Meta',
                    'icon' => 'heroicon-o-document-text',
                ],
                'identity' => [
                    'label' => 'Site Identity',
                    'icon' => 'heroicon-o-photo',
                ],
                'robots' => [
                    'label' => 'Robots',
                    'icon' => 'heroicon-o-cpu-chip',
                ],
            ],
            'sections' => [
                'meta' => [
                    'label' => 'Meta Information',
                    'description' => 'General SEO meta tags for your website',
                ],
                'branding' => [
                    'label' => 'Branding',
                    'description' => 'Logo and favicon for SEO and sharing',
                ],
                'robots_config' => [
                    'label' => 'Robots.txt Configuration',
                    'description' => 'Control how search engines crawl your site',
                ],
            ],
            'fields' => [
                'meta_title' => 'Meta Title',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'site_logo' => 'Site Logo',
                'site_favicon' => 'Favicon',
                'robots_txt' => 'robots.txt',
            ],
            'helpers' => [
                'meta_title' => 'Optimal length: 50–60 characters',
                'meta_description' => 'Optimal length: 150–160 characters',
                'site_logo' => 'Recommended: SVG or PNG, transparent background',
                'site_favicon' => 'Recommended: 32x32 or 64x64 ICO/PNG',
                'robots_txt' => 'Define rules for search engine crawlers',
            ],
            'placeholders' => [
                'meta_keywords' => 'cms, content management, website',
            ],
        ],
    ],

];
