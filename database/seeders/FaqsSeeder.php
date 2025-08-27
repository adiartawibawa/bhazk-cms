<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data existing jika ada
        Faq::query()->delete();
        FaqCategory::query()->delete();

        // Buat kategori FAQ untuk CMS
        $categories = [
            [
                'name' => ['en' => 'General', 'id' => 'Umum'],
                'slug' => ['en' => 'general', 'id' => 'umum'],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Content Management', 'id' => 'Manajemen Konten'],
                'slug' => ['en' => 'content-management', 'id' => 'manajemen-konten'],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'User Management', 'id' => 'Manajemen Pengguna'],
                'slug' => ['en' => 'user-management', 'id' => 'manajemen-pengguna'],
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Permissions & Roles', 'id' => 'Izin & Peran'],
                'slug' => ['en' => 'permissions-roles', 'id' => 'izin-peran'],
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Troubleshooting', 'id' => 'Pemecahan Masalah'],
                'slug' => ['en' => 'troubleshooting', 'id' => 'pemecahan-masalah'],
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            FaqCategory::create($categoryData);
        }

        // Ambil kategori untuk relasi
        $generalCategory = FaqCategory::where('slug->en', 'general')->first();
        $contentCategory = FaqCategory::where('slug->en', 'content-management')->first();
        $userCategory = FaqCategory::where('slug->en', 'user-management')->first();
        $permissionsCategory = FaqCategory::where('slug->en', 'permissions-roles')->first();
        $troubleshootingCategory = FaqCategory::where('slug->en', 'troubleshooting')->first();

        // Buat FAQ items untuk CMS
        $faqs = [
            // General FAQs
            [
                'question' => [
                    'en' => 'What is this CMS system?',
                    'id' => 'Apa itu sistem CMS ini?'
                ],
                'answer' => [
                    'en' => 'This is a comprehensive Content Management System designed to help you manage your website content, users, permissions, and digital assets efficiently.',
                    'id' => 'Ini adalah Sistem Manajemen Konten yang komprehensif yang dirancang untuk membantu Anda mengelola konten website, pengguna, izin, dan aset digital dengan efisien.'
                ],
                'sort_order' => 1,
                'faq_category_id' => $generalCategory->id,
                'is_active' => true,
                'views_count' => 150,
                'helpful_count' => 120,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.8,
            ],
            [
                'question' => [
                    'en' => 'How do I get support for the CMS?',
                    'id' => 'Bagaimana cara mendapatkan dukungan untuk CMS?'
                ],
                'answer' => [
                    'en' => 'You can contact our support team through the ticketing system in the admin panel or email us at cms-support@company.com. Response time is typically within 24 hours.',
                    'id' => 'Anda dapat menghubungi tim dukungan kami melalui sistem tiket di panel admin atau email ke cms-support@company.com. Waktu respons biasanya dalam 24 jam.'
                ],
                'sort_order' => 2,
                'faq_category_id' => $generalCategory->id,
                'is_active' => true,
                'views_count' => 230,
                'helpful_count' => 200,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.87,
            ],

            // Content Management FAQs
            [
                'question' => [
                    'en' => 'How do I create a new post?',
                    'id' => 'Bagaimana cara membuat postingan baru?'
                ],
                'answer' => [
                    'en' => 'Go to Content → Posts → Create New. Fill in the title, content, and metadata. You can save as draft or publish immediately based on your permissions.',
                    'id' => 'Pergi ke Konten → Postingan → Buat Baru. Isi judul, konten, dan metadata. Anda dapat menyimpan sebagai draf atau publikasi segera berdasarkan izin Anda.'
                ],
                'sort_order' => 1,
                'faq_category_id' => $contentCategory->id,
                'is_active' => true,
                'views_count' => 320,
                'helpful_count' => 290,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.91,
            ],
            [
                'question' => [
                    'en' => 'How can I schedule content for future publication?',
                    'id' => 'Bagaimana cara menjadwalkan konten untuk publikasi mendatang?'
                ],
                'answer' => [
                    'en' => 'When creating or editing content, use the "Publish At" date picker to set a future date and time. The content will automatically publish at the scheduled time.',
                    'id' => 'Saat membuat atau mengedit konten, gunakan pemilih tanggal "Publikasi Pada" untuk mengatur tanggal dan waktu mendatang. Konten akan otomatis terbit pada waktu yang dijadwalkan.'
                ],
                'sort_order' => 2,
                'faq_category_id' => $contentCategory->id,
                'is_active' => true,
                'views_count' => 180,
                'helpful_count' => 160,
                'not_helpful_count' => 20,
                'helpfulness_ratio' => 0.89,
            ],
            [
                'question' => [
                    'en' => 'What file types can I upload to the media library?',
                    'id' => 'Jenis file apa yang bisa saya unggah ke perpustakaan media?'
                ],
                'answer' => [
                    'en' => 'You can upload images (JPG, PNG, GIF, WEBP), documents (PDF, DOC, DOCX), and media files (MP4, MP3). Maximum file size is 10MB for images and 50MB for other files.',
                    'id' => 'Anda dapat mengunggah gambar (JPG, PNG, GIF, WEBP), dokumen (PDF, DOC, DOCX), dan file media (MP4, MP3). Ukuran file maksimum 10MB untuk gambar dan 50MB untuk file lainnya.'
                ],
                'sort_order' => 3,
                'faq_category_id' => $contentCategory->id,
                'is_active' => true,
                'views_count' => 275,
                'helpful_count' => 250,
                'not_helpful_count' => 25,
                'helpfulness_ratio' => 0.91,
            ],

            // User Management FAQs
            [
                'question' => [
                    'en' => 'How do I add a new user to the system?',
                    'id' => 'Bagaimana cara menambah pengguna baru ke sistem?'
                ],
                'answer' => [
                    'en' => 'Go to Users → Add New. Fill in the required information and assign appropriate roles. The user will receive an email invitation to set their password.',
                    'id' => 'Pergi ke Pengguna → Tambah Baru. Isi informasi yang diperlukan dan tetapkan peran yang sesuai. Pengguna akan menerima undangan email untuk mengatur kata sandi mereka.'
                ],
                'sort_order' => 1,
                'faq_category_id' => $userCategory->id,
                'is_active' => true,
                'views_count' => 195,
                'helpful_count' => 170,
                'not_helpful_count' => 25,
                'helpfulness_ratio' => 0.87,
            ],
            [
                'question' => [
                    'en' => 'What is the difference between Author, Editor, and Contributor?',
                    'id' => 'Apa perbedaan antara Penulis, Editor, dan Kontributor?'
                ],
                'answer' => [
                    'en' => 'Authors can create and publish their own content. Editors can create, edit, and publish any content. Contributors can create content but require approval from Editors or Admins to publish.',
                    'id' => 'Penulis dapat membuat dan mempublikasikan konten mereka sendiri. Editor dapat membuat, mengedit, dan mempublikasikan konten apa pun. Kontributor dapat membuat konten tetapi memerlukan persetujuan dari Editor atau Admin untuk mempublikasikan.'
                ],
                'sort_order' => 2,
                'faq_category_id' => $userCategory->id,
                'is_active' => true,
                'views_count' => 310,
                'helpful_count' => 280,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.90,
            ],
            [
                'question' => [
                    'en' => 'How can I reset a user\'s password?',
                    'id' => 'Bagaimana cara mereset kata sandi pengguna?'
                ],
                'answer' => [
                    'en' => 'Admins can reset passwords from Users → Edit User → Security tab. The user will receive a password reset link via email.',
                    'id' => 'Admin dapat mereset kata sandi dari Pengguna → Edit Pengguna → tab Keamanan. Pengguna akan menerima tautan reset kata sandi melalui email.'
                ],
                'sort_order' => 3,
                'faq_category_id' => $userCategory->id,
                'is_active' => true,
                'views_count' => 125,
                'helpful_count' => 110,
                'not_helpful_count' => 15,
                'helpfulness_ratio' => 0.88,
            ],

            // Permissions & Roles FAQs
            [
                'question' => [
                    'en' => 'What permissions does each role have?',
                    'id' => 'Izin apa yang dimiliki setiap peran?'
                ],
                'answer' => [
                    'en' => 'Super Admin has full system access. Admin manages users and content. Editor approves and publishes content. Author creates content. Contributor submits content for review. Subscriber has read-only access.',
                    'id' => 'Super Admin memiliki akses sistem penuh. Admin mengelola pengguna dan konten. Editor menyetujui dan mempublikasikan konten. Penulis membuat konten. Kontributor mengirimkan konten untuk ditinjau. Pelanggan memiliki akses hanya-baca.'
                ],
                'sort_order' => 1,
                'faq_category_id' => $permissionsCategory->id,
                'is_active' => true,
                'views_count' => 420,
                'helpful_count' => 380,
                'not_helpful_count' => 40,
                'helpfulness_ratio' => 0.90,
            ],
            [
                'question' => [
                    'en' => 'How do I customize role permissions?',
                    'id' => 'Bagaimana cara menyesuaikan izin peran?'
                ],
                'answer' => [
                    'en' => 'Only Super Admins can modify role permissions. Go to Settings → Roles & Permissions → Edit Role. Be cautious when modifying permissions as it affects system security.',
                    'id' => 'Hanya Super Admin yang dapat mengubah izin peran. Pergi ke Pengaturan → Peran & Izin → Edit Peran. Hati-hati saat mengubah izin karena mempengaruhi keamanan sistem.'
                ],
                'sort_order' => 2,
                'faq_category_id' => $permissionsCategory->id,
                'is_active' => true,
                'views_count' => 95,
                'helpful_count' => 85,
                'not_helpful_count' => 10,
                'helpfulness_ratio' => 0.89,
            ],
            [
                'question' => [
                    'en' => 'Can I create custom roles?',
                    'id' => 'Bisakah saya membuat peran kustom?'
                ],
                'answer' => [
                    'en' => 'Yes, Super Admins can create custom roles with specific permissions. Go to Settings → Roles & Permissions → Create New Role and assign the desired permissions.',
                    'id' => 'Ya, Super Admin dapat membuat peran kustom dengan izin spesifik. Pergi ke Pengaturan → Peran & Izin → Buat Peran Baru dan tetapkan izin yang diinginkan.'
                ],
                'sort_order' => 3,
                'faq_category_id' => $permissionsCategory->id,
                'is_active' => true,
                'views_count' => 140,
                'helpful_count' => 120,
                'not_helpful_count' => 20,
                'helpfulness_ratio' => 0.86,
            ],

            // Troubleshooting FAQs
            [
                'question' => [
                    'en' => 'I can\'t access certain features. What should I do?',
                    'id' => 'Saya tidak dapat mengakses fitur tertentu. Apa yang harus saya lakukan?'
                ],
                'answer' => [
                    'en' => 'This is usually a permissions issue. Contact your administrator to verify your role permissions. Ensure you have the necessary permissions for the features you\'re trying to access.',
                    'id' => 'Ini biasanya masalah izin. Hubungi administrator Anda untuk memverifikasi izin peran Anda. Pastikan Anda memiliki izin yang diperlukan untuk fitur yang ingin Anda akses.'
                ],
                'sort_order' => 1,
                'faq_category_id' => $troubleshootingCategory->id,
                'is_active' => true,
                'views_count' => 280,
                'helpful_count' => 250,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.89,
            ],
            [
                'question' => [
                    'en' => 'Why are my images not displaying properly?',
                    'id' => 'Mengapa gambar saya tidak ditampilkan dengan benar?'
                ],
                'answer' => [
                    'en' => 'Check the file format and size. Ensure images are in supported formats (JPG, PNG, GIF, WEBP) and under 10MB. Clear your browser cache and check the image URL.',
                    'id' => 'Periksa format dan ukuran file. Pastikan gambar dalam format yang didukung (JPG, PNG, GIF, WEBP) dan di bawah 10MB. Bersihkan cache browser dan periksa URL gambar.'
                ],
                'sort_order' => 2,
                'faq_category_id' => $troubleshootingCategory->id,
                'is_active' => true,
                'views_count' => 175,
                'helpful_count' => 160,
                'not_helpful_count' => 15,
                'helpfulness_ratio' => 0.91,
            ],
            [
                'question' => [
                    'en' => 'How do I recover deleted content?',
                    'id' => 'Bagaimana cara memulihkan konten yang terhapus?'
                ],
                'answer' => [
                    'en' => 'Check the trash/recycle bin in the content section. Deleted items are kept for 30 days before permanent deletion. Only users with appropriate permissions can restore content.',
                    'id' => 'Periksa tempat sampah/daur ulang di bagian konten. Item yang dihapus disimpan selama 30 hari sebelum penghapusan permanen. Hanya pengguna dengan izin yang sesuai yang dapat memulihkan konten.'
                ],
                'sort_order' => 3,
                'faq_category_id' => $troubleshootingCategory->id,
                'is_active' => true,
                'views_count' => 210,
                'helpful_count' => 190,
                'not_helpful_count' => 20,
                'helpfulness_ratio' => 0.90,
            ],
            [
                'question' => [
                    'en' => 'The system is running slow. What can I do?',
                    'id' => 'Sistem berjalan lambat. Apa yang bisa saya lakukan?'
                ],
                'answer' => [
                    'en' => 'Clear your browser cache, try a different browser, or check your internet connection. If the issue persists, contact support as it might be a server-side issue.',
                    'id' => 'Bersihkan cache browser, coba browser berbeda, atau periksa koneksi internet Anda. Jika masalah berlanjut, hubungi dukungan karena mungkin masalah di sisi server.'
                ],
                'sort_order' => 4,
                'faq_category_id' => $troubleshootingCategory->id,
                'is_active' => true,
                'views_count' => 95,
                'helpful_count' => 80,
                'not_helpful_count' => 15,
                'helpfulness_ratio' => 0.84,
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::create($faqData);
        }

        $this->command->info('FAQ categories and FAQs seeded successfully!');
        $this->command->info('Created ' . count($categories) . ' FAQ categories and ' . count($faqs) . ' FAQs.');
    }
}
