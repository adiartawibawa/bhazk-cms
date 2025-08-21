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
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //Ref
            $table->uuid('author_id')->nullable();
            $table->string('author_type')->nullable();
            $table->string('type')->default('post')->nullable();
            //Info
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('short_description')->nullable();
            $table->json('keywords')->nullable();
            $table->json('body')->nullable();
            //Options
            $table->boolean('is_published')->default(0);
            $table->boolean('is_trend')->default(0);
            $table->dateTime('published_at')->nullable();
            //Counters
            $table->double('likes')->default(0);
            $table->double('views')->default(0);
            //Meta
            $table->string('meta_url')->nullable();
            $table->json('meta')->nullable();
            $table->text('meta_redirect')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('post_metas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('model_id')->nullable();
            $table->string('model_type')->nullable();
            $table->uuid('post_id');
            $table->string('key')->index();
            $table->json('value')->nullable();
            $table->timestamps();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });

        // Posts Has Category Pivot Table
        Schema::create('posts_has_category', function (Blueprint $table) {
            $table->uuid('post_id');
            $table->uuid('category_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->primary(['post_id', 'category_id']);
        });

        // Posts Has Tags Pivot Table
        Schema::create('posts_has_tags', function (Blueprint $table) {
            $table->uuid('post_id');
            $table->uuid('tag_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('categories')->onDelete('cascade');
            $table->primary(['post_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_has_tags');
        Schema::dropIfExists('posts_has_category');
        Schema::dropIfExists('post_metas');
        Schema::dropIfExists('posts');
    }
};
