<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ================== Content Types ==================
        Schema::create('content_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name'); // ex: Blog Post, Product
            $table->json('slug');
            $table->json('fields')->nullable(); // definisi field dinamis
            $table->timestamps();
        });

        // ================== Contents ==================
        Schema::create('contents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('content_type_id');
            $table->json('title');
            $table->json('slug');
            $table->json('body')->nullable();
            $table->json('data')->nullable(); // data field kustom
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->uuid('user_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('content_type_id')->references('id')->on('content_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
        Schema::dropIfExists('content_types');
    }
};
