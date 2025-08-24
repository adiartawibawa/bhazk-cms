<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FaqTicketSeeder extends Seeder
{
    public function run(): void
    {
        // ================= FAQs =================
        DB::table('faqs')->insert([
            [
                'id' => (string) Str::uuid(),
                'question' => 'Bagaimana cara reset password?',
                'answer' => 'Klik tombol "Lupa Password" pada halaman login, lalu ikuti instruksi yang dikirim via email.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'question' => 'Apakah saya bisa menghapus akun?',
                'answer' => 'Ya, silakan hubungi admin untuk melakukan penghapusan akun permanen.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ================= Tickets =================
        $user = DB::table('users')->first();

        $ticketId = (string) Str::uuid();
        DB::table('tickets')->insert([
            'id' => $ticketId,
            'user_id' => $user?->id,
            'subject' => 'Tidak bisa login ke akun',
            'status' => 'open',
            'description' => 'Saya mencoba login tetapi selalu gagal meskipun password sudah benar.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ticket_messages')->insert([
            [
                'id' => (string) Str::uuid(),
                'ticket_id' => $ticketId,
                'user_id' => $user?->id,
                'message' => 'Saya butuh bantuan segera.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'ticket_id' => $ticketId,
                'user_id' => null, // admin
                'message' => 'Kami sedang memeriksa masalah Anda.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
