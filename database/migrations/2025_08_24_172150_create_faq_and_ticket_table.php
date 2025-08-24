<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ================ FAQs ==================
        Schema::create('faqs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('question');
            $table->json('answer');
            $table->unsignedInteger('sort_order')->default(0); // urutan tampil
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ================ Tickets ==================
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable(); // siapa yang bikin tiket
            $table->string('subject');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // priority
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // (Opsional) jika ingin percakapan tiket lebih rapi
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Relasi ke tiket utama
            $table->foreignUuid('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();
            // siapa yang membalas (bisa user, bisa admin, bisa juga null kalau system)
            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->longText('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('faqs');
    }
};
