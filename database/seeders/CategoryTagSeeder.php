<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryTagSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil salah satu content_type (Blog Post) dan content contoh
        $blogContent = DB::table('contents')->where('slug', 'hello-world')->first();

        // ================= Categories =================
        $techCategoryId = (string) Str::uuid();
        $lifeCategoryId = (string) Str::uuid();

        DB::table('categories')->insert([
            [
                'id' => $techCategoryId,
                'name' => json_encode(['en' => 'Technology', 'id' => 'Teknologi']),
                'slug' => json_encode(['en' => 'technology', 'id' => 'teknologi']),
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $lifeCategoryId,
                'name' => json_encode(['en' => 'Lifestyle', 'id' => 'Gaya Hidup']),
                'slug' => json_encode(['en' => 'lifestyle', 'id' => 'gaya-hidup']),
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Hubungkan content ke kategori
        if ($blogContent) {
            DB::table('content_category')->insert([
                'content_id' => $blogContent->id,
                'category_id' => $techCategoryId,
            ]);
        }

        // ================= Tags =================
        $laravelTagId = (string) Str::uuid();
        $cmsTagId = (string) Str::uuid();

        DB::table('tags')->insert([
            [
                'id' => $laravelTagId,
                'name' => json_encode(['en' => 'Laravel', 'id' => 'Laravel']),
                'slug' => json_encode(['en' => 'laravel', 'id' => 'laravel']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $cmsTagId,
                'name' => json_encode(['en' => 'CMS', 'id' => 'CMS']),
                'slug' => json_encode(['en' => 'cms', 'id' => 'cms']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Hubungkan content ke tags
        if ($blogContent) {
            DB::table('content_tag')->insert([
                ['content_id' => $blogContent->id, 'tag_id' => $laravelTagId],
                ['content_id' => $blogContent->id, 'tag_id' => $cmsTagId],
            ]);
        }
    }
}
