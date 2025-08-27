<?php

namespace Database\Seeders;

use App\Models\ContentType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contentTypes = [
            [
                'name' => [
                    'en' => 'Article',
                    'id' => 'Artikel',
                ],
                'slug' => [
                    'en' => 'articles',
                    'id' => 'artikel',
                ],
                'icon' => 'heroicon-o-document-text',
                'is_active' => true,
                'fields' => [
                    [
                        'name' => 'content',
                        'type' => 'richtext',
                        'label' => 'Content',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'featured_image',
                        'type' => 'image',
                        'label' => 'Featured Image',
                        'rules' => 'image|max:2048',
                        'directory' => 'articles/images',
                    ],
                ],
            ],
            [
                'name' => [
                    'en' => 'Page',
                    'id' => 'Halaman',
                ],
                'slug' => [
                    'en' => 'pages',
                    'id' => 'halaman',
                ],
                'icon' => 'heroicon-o-document',
                'is_active' => true,
                'fields' => [
                    [
                        'name' => 'content',
                        'type' => 'richtext',
                        'label' => 'Content',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'page_template',
                        'type' => 'select',
                        'label' => 'Template',
                        'options' => [
                            'default' => 'Default',
                            'fullwidth' => 'Full Width',
                            'sidebar' => 'With Sidebar',
                        ],
                    ],
                    [
                        'name' => 'show_in_navigation',
                        'type' => 'boolean',
                        'label' => 'Show in Navigation',
                        'default' => true,
                    ],
                ],
            ],
            [
                'name' => [
                    'en' => 'News',
                    'id' => 'Berita',
                ],
                'slug' => [
                    'en' => 'news',
                    'id' => 'berita',
                ],
                'icon' => 'heroicon-o-newspaper',
                'is_active' => true,
                'fields' => [
                    [
                        'name' => 'content',
                        'type' => 'richtext',
                        'label' => 'Content',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'featured_image',
                        'type' => 'image',
                        'label' => 'Featured Image',
                        'rules' => 'required|image|max:2048',
                        'directory' => 'news/images',
                    ],
                    [
                        'name' => 'is_breaking_news',
                        'type' => 'boolean',
                        'label' => 'Breaking News',
                        'default' => false,
                    ],
                ],
            ],
            [
                'name' => [
                    'en' => 'Blog Post',
                    'id' => 'Postingan Blog',
                ],
                'slug' => [
                    'en' => 'blog-posts',
                    'id' => 'postingan-blog',
                ],
                'icon' => 'heroicon-o-pencil',
                'is_active' => true,
                'fields' => [
                    [
                        'name' => 'content',
                        'type' => 'richtext',
                        'label' => 'Content',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'featured_image',
                        'type' => 'image',
                        'label' => 'Featured Image',
                        'rules' => 'image|max:2048',
                        'directory' => 'blog/images',
                    ],
                ],
            ],
            [
                'name' => [
                    'en' => 'FAQ',
                    'id' => 'FAQ',
                ],
                'slug' => [
                    'en' => 'faqs',
                    'id' => 'faq',
                ],
                'icon' => 'heroicon-o-question-mark-circle',
                'is_active' => true,
                'fields' => [
                    [
                        'name' => 'answer',
                        'type' => 'richtext',
                        'label' => 'Answer',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'order',
                        'type' => 'number',
                        'label' => 'Order',
                        'rules' => 'numeric|min:0',
                        'default' => 0,
                    ],
                ],
            ],
            [
                'name' => [
                    'en' => 'Testimonial',
                    'id' => 'Testimoni',
                ],
                'slug' => [
                    'en' => 'testimonials',
                    'id' => 'testimoni',
                ],
                'icon' => 'heroicon-o-chat-bubble-left-ellipsis',
                'is_active' => true,
                'fields' => [
                    [
                        'name' => 'client_name',
                        'type' => 'text',
                        'label' => 'Client Name',
                        'rules' => 'required|min:3|max:255',
                    ],
                    [
                        'name' => 'company',
                        'type' => 'text',
                        'label' => 'Company',
                        'rules' => 'max:255',
                    ],
                    [
                        'name' => 'position',
                        'type' => 'text',
                        'label' => 'Position',
                        'rules' => 'max:255',
                    ],
                    [
                        'name' => 'testimonial',
                        'type' => 'textarea',
                        'label' => 'Testimonial',
                        'rules' => 'required|max:1000',
                    ],
                    [
                        'name' => 'rating',
                        'type' => 'select',
                        'label' => 'Rating',
                        'options' => [
                            '1' => '1 Star',
                            '2' => '2 Stars',
                            '3' => '3 Stars',
                            '4' => '4 Stars',
                            '5' => '5 Stars',
                        ],
                    ],
                    [
                        'name' => 'client_photo',
                        'type' => 'image',
                        'label' => 'Client Photo',
                        'rules' => 'image|max:1024',
                        'directory' => 'testimonials/photos',
                    ],
                    [
                        'name' => 'is_featured',
                        'type' => 'boolean',
                        'label' => 'Featured Testimonial',
                        'default' => false,
                    ],
                ],
            ],
        ];

        foreach ($contentTypes as $contentTypeData) {
            ContentType::firstOrCreate(
                ['slug->en' => $contentTypeData['slug']['en']],
                $contentTypeData
            );
        }

        $this->command->info('Content types seeded successfully!');
        $this->command->info('Total content types: ' . count($contentTypes));
    }
}
