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
                        'label' => [
                            'en' => 'Title',
                            'id' => 'Judul'
                        ],
                        'rules' => 'required|min:3|max:255',
                        'placeholder' => [
                            'en' => 'Enter article title',
                            'id' => 'Masukkan judul artikel'
                        ],
                    ],
                    [
                        'name' => 'content',
                        'type' => 'richtext',
                        'label' => [
                            'en' => 'Content',
                            'id' => 'Konten'
                        ],
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'excerpt',
                        'type' => 'textarea',
                        'label' => [
                            'en' => 'Excerpt',
                            'id' => 'Ringkasan'
                        ],
                        'rules' => 'max:500',
                        'placeholder' => [
                            'en' => 'Brief summary of the article',
                            'id' => 'Ringkasan singkat artikel'
                        ],
                    ],
                    [
                        'name' => 'featured_image',
                        'type' => 'image',
                        'label' => [
                            'en' => 'Featured Image',
                            'id' => 'Gambar Utama'
                        ],
                        'rules' => 'image|max:2048',
                        'directory' => 'articles/images',
                    ],
                    [
                        'name' => 'categories',
                        'type' => 'select',
                        'label' => [
                            'en' => 'Categories',
                            'id' => 'Kategori'
                        ],
                        'multiple' => true,
                        'options' => [
                            'news' => ['en' => 'News', 'id' => 'Berita'],
                            'technology' => ['en' => 'Technology', 'id' => 'Teknologi'],
                            'lifestyle' => ['en' => 'Lifestyle', 'id' => 'Gaya Hidup'],
                        ],
                    ],
                    [
                        'name' => 'tags',
                        'type' => 'tags',
                        'label' => [
                            'en' => 'Tags',
                            'id' => 'Tag'
                        ],
                        'placeholder' => [
                            'en' => 'Add tags...',
                            'id' => 'Tambahkan tag...'
                        ],
                    ],
                    [
                        'name' => 'published_at',
                        'type' => 'datetime',
                        'label' => [
                            'en' => 'Publish Date',
                            'id' => 'Tanggal Publikasi'
                        ],
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'is_featured',
                        'type' => 'boolean',
                        'label' => [
                            'en' => 'Featured Article',
                            'id' => 'Artikel Unggulan'
                        ],
                        'default' => false,
                    ],
                    [
                        'name' => 'meta_description',
                        'type' => 'textarea',
                        'label' => [
                            'en' => 'Meta Description',
                            'id' => 'Deskripsi Meta'
                        ],
                        'rules' => 'max:160',
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
                        'label' => [
                            'en' => 'Title',
                            'id' => 'Judul'
                        ],
                        'rules' => 'required|min:3|max:255',
                    ],
                    [
                        'name' => 'content',
                        'type' => 'richtext',
                        'label' => [
                            'en' => 'Content',
                            'id' => 'Konten'
                        ],
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'page_template',
                        'type' => 'select',
                        'label' => [
                            'en' => 'Template',
                            'id' => 'Template'
                        ],
                        'options' => [
                            'default' => ['en' => 'Default', 'id' => 'Default'],
                            'fullwidth' => ['en' => 'Full Width', 'id' => 'Lebar Penuh'],
                            'sidebar' => ['en' => 'With Sidebar', 'id' => 'Dengan Sidebar'],
                        ],
                    ],
                    [
                        'name' => 'show_in_navigation',
                        'type' => 'boolean',
                        'label' => [
                            'en' => 'Show in Navigation',
                            'id' => 'Tampilkan di Navigasi'
                        ],
                        'default' => true,
                    ],
                    [
                        'name' => 'meta_description',
                        'type' => 'textarea',
                        'label' => [
                            'en' => 'Meta Description',
                            'id' => 'Deskripsi Meta'
                        ],
                        'rules' => 'max:160',
                    ],
                    [
                        'name' => 'is_published',
                        'type' => 'boolean',
                        'label' => [
                            'en' => 'Published',
                            'id' => 'Diterbitkan'
                        ],
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
                        'name' => 'title',
                        'type' => 'text',
                        'label' => [
                            'en' => 'Title',
                            'id' => 'Judul'
                        ],
                        'rules' => 'required|min:3|max:255',
                    ],
                    [
                        'name' => 'content',
                        'type' => 'richtext',
                        'label' => [
                            'en' => 'Content',
                            'id' => 'Konten'
                        ],
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'excerpt',
                        'type' => 'textarea',
                        'label' => [
                            'en' => 'Excerpt',
                            'id' => 'Ringkasan'
                        ],
                        'rules' => 'max:200',
                    ],
                    [
                        'name' => 'featured_image',
                        'type' => 'image',
                        'label' => [
                            'en' => 'Featured Image',
                            'id' => 'Gambar Utama'
                        ],
                        'rules' => 'required|image|max:2048',
                        'directory' => 'news/images',
                    ],
                    [
                        'name' => 'news_category',
                        'type' => 'select',
                        'label' => [
                            'en' => 'Category',
                            'id' => 'Kategori'
                        ],
                        'options' => [
                            'local' => ['en' => 'Local', 'id' => 'Lokal'],
                            'national' => ['en' => 'National', 'id' => 'Nasional'],
                            'international' => ['en' => 'International', 'id' => 'Internasional'],
                            'sports' => ['en' => 'Sports', 'id' => 'Olahraga'],
                            'business' => ['en' => 'Business', 'id' => 'Bisnis'],
                        ],
                    ],
                    [
                        'name' => 'published_at',
                        'type' => 'datetime',
                        'label' => [
                            'en' => 'Publish Date',
                            'id' => 'Tanggal Publikasi'
                        ],
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'is_breaking_news',
                        'type' => 'boolean',
                        'label' => [
                            'en' => 'Breaking News',
                            'id' => 'Berita Terkini'
                        ],
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
                        'name' => 'title',
                        'type' => 'text',
                        'label' => [
                            'en' => 'Title',
                            'id' => 'Judul'
                        ],
                        'rules' => 'required|min:3|max:255',
                    ],
                    [
                        'name' => 'content',
                        'type' => 'richtext',
                        'label' => [
                            'en' => 'Content',
                            'id' => 'Konten'
                        ],
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'excerpt',
                        'type' => 'textarea',
                        'label' => [
                            'en' => 'Excerpt',
                            'id' => 'Ringkasan'
                        ],
                        'rules' => 'max:300',
                    ],
                    [
                        'name' => 'featured_image',
                        'type' => 'image',
                        'label' => [
                            'en' => 'Featured Image',
                            'id' => 'Gambar Utama'
                        ],
                        'rules' => 'image|max:2048',
                        'directory' => 'blog/images',
                    ],
                    [
                        'name' => 'author',
                        'type' => 'select',
                        'label' => [
                            'en' => 'Author',
                            'id' => 'Penulis'
                        ],
                        'options' => [], // Will be populated dynamically from users
                    ],
                    [
                        'name' => 'blog_category',
                        'type' => 'select',
                        'label' => [
                            'en' => 'Category',
                            'id' => 'Kategori'
                        ],
                        'multiple' => true,
                        'options' => [
                            'tutorial' => ['en' => 'Tutorial', 'id' => 'Tutorial'],
                            'tips' => ['en' => 'Tips & Tricks', 'id' => 'Tips & Trik'],
                            'news' => ['en' => 'Industry News', 'id' => 'Berita Industri'],
                            'review' => ['en' => 'Product Review', 'id' => 'Ulasan Produk'],
                        ],
                    ],
                    [
                        'name' => 'tags',
                        'type' => 'tags',
                        'label' => [
                            'en' => 'Tags',
                            'id' => 'Tag'
                        ],
                    ],
                    [
                        'name' => 'published_at',
                        'type' => 'datetime',
                        'label' => [
                            'en' => 'Publish Date',
                            'id' => 'Tanggal Publikasi'
                        ],
                    ],
                    [
                        'name' => 'is_published',
                        'type' => 'boolean',
                        'label' => [
                            'en' => 'Published',
                            'id' => 'Diterbitkan'
                        ],
                        'default' => false,
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
                        'label' => [
                            'en' => 'Question',
                            'id' => 'Pertanyaan'
                        ],
                        'rules' => 'required|min:3|max:255',
                    ],
                    [
                        'name' => 'answer',
                        'type' => 'richtext',
                        'label' => [
                            'en' => 'Answer',
                            'id' => 'Jawaban'
                        ],
                        'rules' => 'required',
                    ],
                    [
                        'name' => 'category',
                        'type' => 'select',
                        'label' => [
                            'en' => 'Category',
                            'id' => 'Kategori'
                        ],
                        'options' => [
                            'general' => ['en' => 'General', 'id' => 'Umum'],
                            'technical' => ['en' => 'Technical', 'id' => 'Teknis'],
                            'billing' => ['en' => 'Billing', 'id' => 'Pembayaran'],
                            'support' => ['en' => 'Support', 'id' => 'Dukungan'],
                        ],
                    ],
                    [
                        'name' => 'order',
                        'type' => 'number',
                        'label' => [
                            'en' => 'Order',
                            'id' => 'Urutan'
                        ],
                        'rules' => 'numeric|min:0',
                        'default' => 0,
                    ],
                    [
                        'name' => 'is_published',
                        'type' => 'boolean',
                        'label' => [
                            'en' => 'Published',
                            'id' => 'Diterbitkan'
                        ],
                        'default' => true,
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
                        'label' => [
                            'en' => 'Client Name',
                            'id' => 'Nama Klien'
                        ],
                        'rules' => 'required|min:3|max:255',
                    ],
                    [
                        'name' => 'company',
                        'type' => 'text',
                        'label' => [
                            'en' => 'Company',
                            'id' => 'Perusahaan'
                        ],
                        'rules' => 'max:255',
                    ],
                    [
                        'name' => 'position',
                        'type' => 'text',
                        'label' => [
                            'en' => 'Position',
                            'id' => 'Posisi'
                        ],
                        'rules' => 'max:255',
                    ],
                    [
                        'name' => 'testimonial',
                        'type' => 'textarea',
                        'label' => [
                            'en' => 'Testimonial',
                            'id' => 'Testimoni'
                        ],
                        'rules' => 'required|max:1000',
                    ],
                    [
                        'name' => 'rating',
                        'type' => 'select',
                        'label' => [
                            'en' => 'Rating',
                            'id' => 'Penilaian'
                        ],
                        'options' => [
                            '1' => ['en' => '1 Star', 'id' => '1 Bintang'],
                            '2' => ['en' => '2 Stars', 'id' => '2 Bintang'],
                            '3' => ['en' => '3 Stars', 'id' => '3 Bintang'],
                            '4' => ['en' => '4 Stars', 'id' => '4 Bintang'],
                            '5' => ['en' => '5 Stars', 'id' => '5 Bintang'],
                        ],
                    ],
                    [
                        'name' => 'client_photo',
                        'type' => 'image',
                        'label' => [
                            'en' => 'Client Photo',
                            'id' => 'Foto Klien'
                        ],
                        'rules' => 'image|max:1024',
                        'directory' => 'testimonials/photos',
                    ],
                    [
                        'name' => 'is_featured',
                        'type' => 'boolean',
                        'label' => [
                            'en' => 'Featured Testimonial',
                            'id' => 'Testimoni Unggulan'
                        ],
                        'default' => false,
                    ],
                    [
                        'name' => 'is_published',
                        'type' => 'boolean',
                        'label' => [
                            'en' => 'Published',
                            'id' => 'Diterbitkan'
                        ],
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
