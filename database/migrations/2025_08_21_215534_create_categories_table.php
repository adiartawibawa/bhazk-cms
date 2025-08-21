<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_id')->nullable();
            $table->string('for')->default('posts')->nullable();
            $table->string('type')->default('category')->nullable();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(1)->nullable();
            $table->boolean('show_in_menu')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('categories_metas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('model_id')->nullable();
            $table->string('model_type')->nullable();
            $table->uuid('category_id');
            $table->string('key')->index();
            $table->json('value')->nullable();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // Add foreign key constraint for parent_id after categories table is created
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_metas');
        Schema::dropIfExists('categories');
    }
};
