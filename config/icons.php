<?php

return [
    'custom_icons' => [
        'heroicons' => [
            'id' => 'heroicons',
            'prefix' => 'heroicon',
            'icons' => [
                'heroicon-o-academic-cap',
                'heroicon-o-adjustments',
                'heroicon-o-annotation',
                'heroicon-o-archive',
                'heroicon-o-arrow-circle-down',
                // Tambahkan lebih banyak icon di sini
            ],
            'template' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 { ICON }"><path fill-rule="evenodd" d="..."/></svg>',
            'template_class' => 'text-blue-500',
            'picker_class' => 'w-6 h-6',
            'replace' => ['heroicon-o-', 'heroicon-'],
        ],
        'fontawesome' => [
            'id' => 'fontawesome',
            'prefix' => 'fa',
            'icons' => [
                'fa-user',
                'fa-cog',
                'fa-home',
                'fa-envelope',
                'fa-bell',
            ],
            'template' => '<i class="fas { ICON }"></i>',
            'template_class' => 'text-blue-600',
            'picker_class' => 'fa-lg',
            'replace' => ['fa-'],
        ],
        // Tambahkan set icon custom lainnya di sini
    ],
];
