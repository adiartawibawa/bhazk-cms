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

        // Buat kategori FAQ
        $categories = [
            [
                'name' => ['en' => 'General', 'id' => 'Umum'],
                'slug' => ['en' => 'general', 'id' => 'umum'],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Payments', 'id' => 'Pembayaran'],
                'slug' => ['en' => 'payments', 'id' => 'pembayaran'],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Shipping', 'id' => 'Pengiriman'],
                'slug' => ['en' => 'shipping', 'id' => 'pengiriman'],
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Account', 'id' => 'Akun'],
                'slug' => ['en' => 'account', 'id' => 'akun'],
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Returns & Refunds', 'id' => 'Pengembalian & Refund'],
                'slug' => ['en' => 'returns-refunds', 'id' => 'pengembalian-refund'],
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            FaqCategory::create($categoryData);
        }

        // Ambil kategori untuk relasi
        $generalCategory = FaqCategory::where('slug->en', 'general')->first();
        $paymentsCategory = FaqCategory::where('slug->en', 'payments')->first();
        $shippingCategory = FaqCategory::where('slug->en', 'shipping')->first();
        $accountCategory = FaqCategory::where('slug->en', 'account')->first();
        $returnsCategory = FaqCategory::where('slug->en', 'returns-refunds')->first();

        // Buat FAQ items
        $faqs = [
            // General FAQs
            [
                'question' => [
                    'en' => 'What is your company about?',
                    'id' => 'Apa yang dilakukan perusahaan Anda?'
                ],
                'answer' => [
                    'en' => 'Our company provides high-quality products and services to customers worldwide with a focus on customer satisfaction.',
                    'id' => 'Perusahaan kami menyediakan produk dan layanan berkualitas tinggi kepada pelanggan di seluruh dunia dengan fokus pada kepuasan pelanggan.'
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
                    'en' => 'How can I contact customer service?',
                    'id' => 'Bagaimana saya bisa menghubungi layanan pelanggan?'
                ],
                'answer' => [
                    'en' => 'You can contact our customer service team via email at support@company.com or by phone at +1-800-123-4567 during business hours (9 AM - 5 PM, Monday to Friday).',
                    'id' => 'Anda dapat menghubungi tim layanan pelanggan kami melalui email di support@company.com atau melalui telepon di +1-800-123-4567 selama jam kerja (9 pagi - 5 sore, Senin hingga Jumat).'
                ],
                'sort_order' => 2,
                'faq_category_id' => $generalCategory->id,
                'is_active' => true,
                'views_count' => 230,
                'helpful_count' => 200,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.87,
            ],
            [
                'question' => [
                    'en' => 'Where are you located?',
                    'id' => 'Dimana lokasi Anda?'
                ],
                'answer' => [
                    'en' => 'Our main office is located at 123 Business Street, Commerce City, CC 12345. We also have several retail locations throughout the country.',
                    'id' => 'Kantor utama kami berada di Jalan Bisnis 123, Kota Commerce, CC 12345. Kami juga memiliki beberapa lokasi ritel di seluruh negeri.'
                ],
                'sort_order' => 3,
                'faq_category_id' => $generalCategory->id,
                'is_active' => true,
                'views_count' => 95,
                'helpful_count' => 80,
                'not_helpful_count' => 15,
                'helpfulness_ratio' => 0.84,
            ],

            // Payments FAQs
            [
                'question' => [
                    'en' => 'What payment methods do you accept?',
                    'id' => 'Metode pembayaran apa yang Anda terima?'
                ],
                'answer' => [
                    'en' => 'We accept all major credit cards (Visa, MasterCard, American Express), PayPal, bank transfers, and Apple Pay.',
                    'id' => 'Kami menerima semua kartu kredit utama (Visa, MasterCard, American Express), PayPal, transfer bank, dan Apple Pay.'
                ],
                'sort_order' => 1,
                'faq_category_id' => $paymentsCategory->id,
                'is_active' => true,
                'views_count' => 320,
                'helpful_count' => 290,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.91,
            ],
            [
                'question' => [
                    'en' => 'Is my payment information secure?',
                    'id' => 'Apakah informasi pembayaran saya aman?'
                ],
                'answer' => [
                    'en' => 'Yes, we use industry-standard SSL encryption to protect your payment information. We do not store your credit card details on our servers.',
                    'id' => 'Ya, kami menggunakan enkripsi SSL standar industri untuk melindungi informasi pembayaran Anda. Kami tidak menyimpan detail kartu kredit Anda di server kami.'
                ],
                'sort_order' => 2,
                'faq_category_id' => $paymentsCategory->id,
                'is_active' => true,
                'views_count' => 275,
                'helpful_count' => 250,
                'not_helpful_count' => 25,
                'helpfulness_ratio' => 0.91,
            ],
            [
                'question' => [
                    'en' => 'Do you offer payment plans?',
                    'id' => 'Apakah Anda menawarkan rencana pembayaran?'
                ],
                'answer' => [
                    'en' => 'Yes, we offer payment plans for orders over $500. You can choose to pay in 3, 6, or 12 monthly installments at checkout.',
                    'id' => 'Ya, kami menawarkan rencana pembayaran untuk pesanan di atas $500. Anda dapat memilih untuk membayar dalam 3, 6, atau 12 angsuran bulanan saat checkout.'
                ],
                'sort_order' => 3,
                'faq_category_id' => $paymentsCategory->id,
                'is_active' => true,
                'views_count' => 180,
                'helpful_count' => 150,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.83,
            ],

            // Shipping FAQs
            [
                'question' => [
                    'en' => 'How long does shipping take?',
                    'id' => 'Berapa lama pengiriman dilakukan?'
                ],
                'answer' => [
                    'en' => 'Standard shipping takes 3-5 business days. Express shipping is available for an additional fee and delivers within 1-2 business days.',
                    'id' => 'Pengiriman standar memakan waktu 3-5 hari kerja. Pengiriman ekspres tersedia dengan biaya tambahan dan dikirim dalam 1-2 hari kerja.'
                ],
                'sort_order' => 1,
                'faq_category_id' => $shippingCategory->id,
                'is_active' => true,
                'views_count' => 420,
                'helpful_count' => 380,
                'not_helpful_count' => 40,
                'helpfulness_ratio' => 0.90,
            ],
            [
                'question' => [
                    'en' => 'Do you ship internationally?',
                    'id' => 'Apakah Anda melakukan pengiriman internasional?'
                ],
                'answer' => [
                    'en' => 'Yes, we ship to over 50 countries worldwide. International shipping times vary by destination, typically taking 7-14 business days.',
                    'id' => 'Ya, kami mengirim ke lebih dari 50 negara di seluruh dunia. Waktu pengiriman internasional bervariasi berdasarkan tujuan, biasanya memakan waktu 7-14 hari kerja.'
                ],
                'sort_order' => 2,
                'faq_category_id' => $shippingCategory->id,
                'is_active' => true,
                'views_count' => 195,
                'helpful_count' => 170,
                'not_helpful_count' => 25,
                'helpfulness_ratio' => 0.87,
            ],
            [
                'question' => [
                    'en' => 'How can I track my order?',
                    'id' => 'Bagaimana saya bisa melacak pesanan saya?'
                ],
                'answer' => [
                    'en' => 'Once your order ships, you will receive a confirmation email with a tracking number. You can use this number to track your order on our website or the carrier\'s website.',
                    'id' => 'Setelah pesanan Anda dikirim, Anda akan menerima email konfirmasi dengan nomor pelacakan. Anda dapat menggunakan nomor ini untuk melacak pesanan Anda di situs web kami atau situs web operator.'
                ],
                'sort_order' => 3,
                'faq_category_id' => $shippingCategory->id,
                'is_active' => true,
                'views_count' => 310,
                'helpful_count' => 280,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.90,
            ],

            // Account FAQs
            [
                'question' => [
                    'en' => 'How do I create an account?',
                    'id' => 'Bagaimana cara membuat akun?'
                ],
                'answer' => [
                    'en' => 'Click on the "Sign Up" button at the top right of our website. Fill in your details and follow the instructions to verify your email address.',
                    'id' => 'Klik tombol "Daftar" di kanan atas situs web kami. Isi detail Anda dan ikuti instruksi untuk memverifikasi alamat email Anda.'
                ],
                'sort_order' => 1,
                'faq_category_id' => $accountCategory->id,
                'is_active' => true,
                'views_count' => 125,
                'helpful_count' => 110,
                'not_helpful_count' => 15,
                'helpfulness_ratio' => 0.88,
            ],
            [
                'question' => [
                    'en' => 'I forgot my password. How can I reset it?',
                    'id' => 'Saya lupa kata sandi. Bagaimana cara meresetnya?'
                ],
                'answer' => [
                    'en' => 'Click on "Forgot Password" on the login page. Enter your email address, and we will send you a link to reset your password.',
                    'id' => 'Klik "Lupa Kata Sandi" di halaman login. Masukkan alamat email Anda, dan kami akan mengirimi Anda tautan untuk mereset kata sandi Anda.'
                ],
                'sort_order' => 2,
                'faq_category_id' => $accountCategory->id,
                'is_active' => true,
                'views_count' => 210,
                'helpful_count' => 190,
                'not_helpful_count' => 20,
                'helpfulness_ratio' => 0.90,
            ],
            [
                'question' => [
                    'en' => 'How do I update my account information?',
                    'id' => 'Bagaimana cara memperbarui informasi akun saya?'
                ],
                'answer' => [
                    'en' => 'Log into your account and go to "Account Settings". From there, you can update your personal information, password, and communication preferences.',
                    'id' => 'Masuk ke akun Anda dan pergi ke "Pengaturan Akun". Dari sana, Anda dapat memperbarui informasi pribadi, kata sandi, dan preferensi komunikasi Anda.'
                ],
                'sort_order' => 3,
                'faq_category_id' => $accountCategory->id,
                'is_active' => true,
                'views_count' => 95,
                'helpful_count' => 85,
                'not_helpful_count' => 10,
                'helpfulness_ratio' => 0.89,
            ],

            // Returns & Refunds FAQs
            [
                'question' => [
                    'en' => 'What is your return policy?',
                    'id' => 'Apa kebijakan pengembalian Anda?'
                ],
                'answer' => [
                    'en' => 'We offer a 30-day return policy for most items. Items must be unused and in their original packaging with all tags attached.',
                    'id' => 'Kami menawarkan kebijakan pengembalian 30 hari untuk sebagian besar item. Barang harus tidak digunakan dan dalam kemasan aslinya dengan semua tag terpasang.'
                ],
                'sort_order' => 1,
                'faq_category_id' => $returnsCategory->id,
                'is_active' => true,
                'views_count' => 280,
                'helpful_count' => 250,
                'not_helpful_count' => 30,
                'helpfulness_ratio' => 0.89,
            ],
            [
                'question' => [
                    'en' => 'How long does it take to process a refund?',
                    'id' => 'Berapa lama proses pengembalian dana?'
                ],
                'answer' => [
                    'en' => 'Once we receive your returned item, refunds are processed within 5-7 business days. The time it takes for the refund to appear in your account depends on your payment method and financial institution.',
                    'id' => 'Setelah kami menerima barang yang dikembalikan, pengembalian dana diproses dalam 5-7 hari kerja. Waktu yang dibutuhkan untuk pengembalian dana muncul di akun Anda tergantung pada metode pembayaran dan institusi keuangan Anda.'
                ],
                'sort_order' => 2,
                'faq_category_id' => $returnsCategory->id,
                'is_active' => true,
                'views_count' => 175,
                'helpful_count' => 160,
                'not_helpful_count' => 15,
                'helpfulness_ratio' => 0.91,
            ],
            [
                'question' => [
                    'en' => 'Do you offer exchanges?',
                    'id' => 'Apakah Anda menawarkan penukaran?'
                ],
                'answer' => [
                    'en' => 'Yes, we offer exchanges for items in different sizes or colors, subject to availability. You can request an exchange by contacting our customer service team.',
                    'id' => 'Ya, kami menawarkan penukaran untuk item dalam ukuran atau warna berbeda, tergantung ketersediaan. Anda dapat meminta penukaran dengan menghubungi tim layanan pelanggan kami.'
                ],
                'sort_order' => 3,
                'faq_category_id' => $returnsCategory->id,
                'is_active' => true,
                'views_count' => 140,
                'helpful_count' => 120,
                'not_helpful_count' => 20,
                'helpfulness_ratio' => 0.86,
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::create($faqData);
        }

        $this->command->info('FAQ categories and FAQs seeded successfully!');
        $this->command->info('Created ' . count($categories) . ' FAQ categories and ' . count($faqs) . ' FAQs.');
    }
}
