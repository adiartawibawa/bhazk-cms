<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentRevisionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil konten yang sudah ada (contoh: Hello World)
        $content = DB::table('contents')->where('slug', 'hello-world')->first();

        if ($content) {
            // Revision 1 (awal publish)
            DB::table('content_revisions')->insert([
                'id' => (string) Str::uuid(),
                'content_id' => $content->id,
                'user_id' => $content->user_id,
                'title' => $content->title,
                'body' => $content->body,
                'data' => $content->data,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]);

            // Revision 2 (update body)
            DB::table('content_revisions')->insert([
                'id' => (string) Str::uuid(),
                'content_id' => $content->id,
                'user_id' => $content->user_id,
                'title' => $content->title,
                'body' => "Ini adalah revisi kedua, menambahkan detail tambahan pada artikel Hello World.",
                'data' => $content->data,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ]);
        }
    }
}
