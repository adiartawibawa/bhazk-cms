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
                        'name' => 'title',
                        'type' => 'text',
                        'label' => 'Title',
                        'rules' => 'required|min:3|max:255',
                        'placeholder' => 'Enter article title',
                    ],
                    [
                        'name' => 'content',
                        'type' => 'richtext',
                        'label' => 'Content',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'excerpt',
                        'type' => 'textarea',
                        'label' => 'Excerpt',
                        'rules' => 'max:500',
                        'placeholder' => 'Brief summary of the article',
                    ],
                    [
                        'name' => 'featured_image',
                        'type' => 'image',
                        'label' => 'Featured Image',
                        'rules' => 'image|max:2048',
                        'directory' => 'articles/images',
                    ],
                    [
                        'name' => 'categories',
                        'type' => 'select',
                        'label' => 'Categories',
                        'multiple' => true,
                        'options' => [
                            'news' => 'News',
                            'technology' => 'Technology',
                            'lifestyle' => 'Lifestyle',
                        ],
                    ],
                    [
                        'name' => 'tags',
                        'type' => 'tags',
                        'label' => 'Tags',
                        'placeholder' => 'Add tags...',
                    ],
                    [
                        'name' => 'published_at',
                        'type' => 'datetime',
                        'label' => 'Publish Date',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'is_featured',
                        'type' => 'boolean',
                        'label' => 'Featured Article',
                        'default' => false,
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
                        'name' => 'title',
                        'type' => 'text',
                        'label' => 'Title',
                        'rules' => 'required|min:3|max:255',
                    ],
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
                    [
                        'name' => 'meta_description',
                        'type' => 'textarea',
                        'label' => 'Meta Description',
                        'rules' => 'max:160',
                    ],
                ],
            ],
            [
                'name' => [
                    'en' => 'Event',
                    'id' => 'Acara',
                ],
                'slug' => [
                    'en' => 'events',
                    'id' => 'acara',
                ],
                'icon' => 'heroicon-o-calendar',
                'is_active' => true,
                'fields' => [
                    [
                        'name' => 'title',
                        'type' => 'text',
                        'label' => 'Event Title',
                        'rules' => 'required|min:3|max:255',
                    ],
                    [
                        'name' => 'description',
                        'type' => 'richtext',
                        'label' => 'Description',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'start_date',
                        'type' => 'datetime',
                        'label' => 'Start Date',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'end_date',
                        'type' => 'datetime',
                        'label' => 'End Date',
                        'rules' => 'required|after:start_date',
                    ],
                    [
                        'name' => 'location',
                        'type' => 'text',
                        'label' => 'Location',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'venue',
                        'type' => 'text',
                        'label' => 'Venue',
                    ],
                    [
                        'name' => 'organizer',
                        'type' => 'text',
                        'label' => 'Organizer',
                    ],
                    [
                        'name' => 'registration_link',
                        'type' => 'url',
                        'label' => 'Registration Link',
                        'rules' => 'nullable|url',
                    ],
                    [
                        'name' => 'featured_image',
                        'type' => 'image',
                        'label' => 'Event Image',
                        'rules' => 'image|max:2048',
                        'directory' => 'events/images',
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
                            '1' =>  '1 Star',
                            '2' =>  '2 Star',
                            '3' =>  '3 Star',
                            '4' =>  '4 Star',
                            '5' =>  '5 Star',
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
            [
                'name' => [
                    'en' => 'Team Member',
                    'id' => 'Anggota Tim',
                ],
                'slug' => [
                    'en' => 'team-members',
                    'id' => 'anggota-tim',
                ],
                'icon' => 'heroicon-o-user-group',
                'is_active' => true,
                'fields' => [
                    [
                        'name' => 'name',
                        'type' => 'text',
                        'label' => 'Full Name',
                        'rules' => 'required|min:3|max:255',
                    ],
                    [
                        'name' => 'position',
                        'type' => 'text',
                        'label' => 'Position',
                        'rules' => 'required|max:255',
                    ],
                    [
                        'name' => 'department',
                        'type' => 'select',
                        'label' => 'Department',
                        'options' => [
                            'management' => 'Management',
                            'development' => 'Development',
                            'design' => 'Design',
                            'marketing' => 'Marketing',
                            'support' => 'Support',
                        ],
                    ],
                    [
                        'name' => 'bio',
                        'type' => 'textarea',
                        'label' => 'Biography',
                        'rules' => 'max:500',
                    ],
                    [
                        'name' => 'photo',
                        'type' => 'image',
                        'label' => 'Photo',
                        'rules' => 'required|image|max:1024',
                        'directory' => 'team/photos',
                    ],
                    [
                        'name' => 'email',
                        'type' => 'email',
                        'label' => 'Email',
                        'rules' => 'nullable|email',
                    ],
                    [
                        'name' => 'phone',
                        'type' => 'text',
                        'label' => 'Phone',
                        'rules' => 'max:20',
                    ],
                    [
                        'name' => 'social_links',
                        'type' => 'repeater',
                        'label' => 'Social Links',
                        'fields' => [
                            [
                                'name' => 'platform',
                                'type' => 'select',
                                'label' => 'Platform',
                                'options' => [
                                    'linkedin' => 'LinkedIn',
                                    'twitter' => 'Twitter',
                                    'facebook' => 'Facebook',
                                    'instagram' => 'Instagram',
                                    'youtube' => 'YouTube',
                                ],
                            ],
                            [
                                'name' => 'url',
                                'type' => 'url',
                                'label' => 'URL',
                                'rules' => 'required|url',
                            ],
                        ],
                    ],
                    [
                        'name' => 'is_active',
                        'type' => 'boolean',
                        'label' => 'URL',
                        'default' => true,
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
                        'name' => 'question',
                        'type' => 'text',
                        'label' => 'Question',
                        'rules' => 'required|min:3|max:255',
                    ],
                    [
                        'name' => 'answer',
                        'type' => 'richtext',
                        'label' =>  'Answer',
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'category',
                        'type' => 'select',
                        'label' => 'Category',
                        'options' => [
                            'general' => 'General',
                            'technical' => 'Technical',
                            'billing' => 'Billing',
                            'support' => 'Support',
                        ],
                    ],
                    [
                        'name' => 'order',
                        'type' => 'number',
                        'label' => 'Order',
                        'rules' => 'numeric|min:0',
                        'default' => 0,
                    ],
                    [
                        'name' => 'is_published',
                        'type' => 'boolean',
                        'label' =>  'Published',
                        'default' => true,
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
