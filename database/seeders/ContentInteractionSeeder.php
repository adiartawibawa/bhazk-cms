<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentInteractionSeeder extends Seeder
{
    public function run(): void
    {
        $content = DB::table('contents')->where('slug', 'hello-world')->first();
        $user = DB::table('users')->first(); // ambil user pertama (asumsi ada user seeded sebelumnya)

        if ($content) {
            // Buat metrics awal
            DB::table('content_metrics')->insert([
                'id' => (string) Str::uuid(),
                'content_id' => $content->id,
                'views_count' => 123,
                'likes_count' => 2,
                'shares_count' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simpan detail likes (misal user pertama sudah like)
            if ($user) {
                DB::table('content_likes')->insert([
                    'id' => (string) Str::uuid(),
                    'content_id' => $content->id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
