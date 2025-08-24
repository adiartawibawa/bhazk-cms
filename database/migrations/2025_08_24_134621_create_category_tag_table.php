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
        // ================ Categories ==================
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->json('slug');
            $table->uuid('parent_id')->nullable(); // untuk nested kategori
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // ================ Tags ==================
        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->json('slug');
            $table->timestamps();
        });

        // ================ Pivot: Content ↔ Category ==================
        Schema::create('content_category', function (Blueprint $table) {
            $table->uuid('content_id');
            $table->uuid('category_id');
            $table->primary(['content_id', 'category_id']);

            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // ================ Pivot: Content ↔ Tag ==================
        Schema::create('content_tag', function (Blueprint $table) {
            $table->uuid('content_id');
            $table->uuid('tag_id');
            $table->primary(['content_id', 'tag_id']);

            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_tag');
        Schema::dropIfExists('content_category');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
    }
};
