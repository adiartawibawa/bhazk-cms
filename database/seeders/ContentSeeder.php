<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ================== Insert Content Types ==================
        $blogTypeId = (string) Str::uuid();
        $productTypeId = (string) Str::uuid();

        DB::table('content_types')->insert([
            [
                'id' => $blogTypeId,
                'name' => json_encode(['en' => 'Blog Post', 'id' => 'Postingan Blog']),
                'slug' => json_encode(['en' => 'blog-post', 'id' => 'postingan-blog']),
                'fields' => json_encode([
                    ['name' => 'excerpt', 'type' => 'string', 'validation' => 'required|max:255'],
                    ['name' => 'featured_image', 'type' => 'media', 'validation' => 'nullable|image'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $productTypeId,
                'name' => json_encode(['en' => 'Product', 'id' => 'Produk']),
                'slug' => json_encode(['en' => 'product', 'id' => 'produk']),
                'fields' => json_encode([
                    ['name' => 'price', 'type' => 'decimal', 'validation' => 'required|numeric'],
                    ['name' => 'sku', 'type' => 'string', 'validation' => 'required|unique:contents,data->sku'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ================== Insert Contents ==================
        DB::table('contents')->insert([
            [
                'id' => (string) Str::uuid(),
                'content_type_id' => $blogTypeId,
                'title' => json_encode(['en' => 'Hello World', 'id' => 'Halo Dunia']),
                'slug' => json_encode(['en' => 'hello-world', 'id' => 'halo-dunia']),
                'body' => json_encode([
                    'en' => 'This is the first blog post.',
                    'id' => 'Ini adalah postingan blog pertama.',
                ]),
                'data' => json_encode([
                    'excerpt' => 'Ringkasan dari Hello World',
                    'featured_image' => null,
                ]),
                'status' => 'published',
                'published_at' => now(),
                'user_id' => null, // bisa diisi user uuid
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'content_type_id' => $productTypeId,
                'title' => json_encode(['en' => 'First Product', 'id' => 'Produk Pertama']),
                'slug' => json_encode(['en' => 'first-product', 'id' => 'produk-pertama']),
                'body' => json_encode([
                    'en' => 'Details about the first product.',
                    'id' => 'Detail tentang produk pertama.',
                ]),
                'data' => json_encode([
                    'price' => 199000,
                    'sku' => 'SKU-001',
                ]),
                'status' => 'published',
                'published_at' => now(),
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
