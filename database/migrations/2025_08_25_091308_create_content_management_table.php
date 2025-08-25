<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ================== Content Types ==================
        Schema::create('content_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->json('slug');
            $table->json('fields')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('created_at');
        });

        // ================== Categories ==================
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->json('slug');
            $table->uuid('parent_id')->nullable();
            $table->unsignedInteger('lft')->nullable()->index();
            $table->unsignedInteger('rgt')->nullable()->index();
            $table->unsignedInteger('depth')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index(['parent_id', 'is_active']);
            $table->index(['lft', 'rgt']);
        });

        // ================== Tags ==================
        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->json('slug');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('created_at');
        });

        // ================== Contents ==================
        Schema::create('contents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('content_type_id');
            $table->json('title');
            $table->json('slug');
            $table->json('excerpt')->nullable();
            $table->json('body')->nullable();
            $table->json('metadata')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->uuid('author_id')->nullable();
            $table->uuid('editor_id')->nullable();
            $table->unsignedInteger('current_version')->default(1);
            $table->unsignedInteger('comment_count')->default(0);
            $table->boolean('featured')->default(false);
            $table->boolean('commentable')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('content_type_id')->references('id')->on('content_types')->onDelete('restrict');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('set null');

            $table->index('content_type_id');
            $table->index('status');
            $table->index('published_at');
            $table->index('current_version');
            $table->index('featured');
            $table->index('commentable');
            $table->index(['status', 'published_at']);
            $table->index(['author_id', 'status']);
        });

        // ================== Content Categories Pivot ==================
        Schema::create('content_categories', function (Blueprint $table) {
            $table->uuid('content_id');
            $table->uuid('category_id');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->primary(['content_id', 'category_id']);
            $table->index(['category_id', 'content_id']);
            $table->index('is_primary');
            $table->index('sort_order');
        });

        // ================== Content Tags Pivot ==================
        Schema::create('content_tags', function (Blueprint $table) {
            $table->uuid('content_id');
            $table->uuid('tag_id');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

            $table->primary(['content_id', 'tag_id']);
            $table->index(['tag_id', 'content_id']);
            $table->index('sort_order');
        });

        // ================== Content Revisions ==================
        Schema::create('content_revisions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('content_id');
            $table->uuid('author_id')->nullable();
            $table->unsignedInteger('version');
            $table->json('title');
            $table->json('body')->nullable();
            $table->json('metadata')->nullable();
            $table->string('change_type')->default('update');
            $table->text('change_description')->nullable();
            $table->boolean('is_autosave')->default(false);
            $table->json('diff_summary')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');

            $table->unique(['content_id', 'version']);
            $table->index(['content_id', 'created_at']);
            $table->index('version');
            $table->index('change_type');
            $table->index('is_autosave');
            $table->index(['author_id', 'created_at']);
        });

        // ================== Content Metrics ==================
        Schema::create('content_metrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('content_id');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('unique_views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('shares_count')->default(0);
            $table->unsignedBigInteger('comments_count')->default(0);
            $table->unsignedBigInteger('downloads_count')->default(0);
            $table->unsignedBigInteger('reading_time_seconds')->default(0);
            $table->float('engagement_rate')->default(0);
            $table->date('metric_date')->nullable();
            $table->timestamps();

            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->unique(['content_id', 'metric_date']);

            $table->index('views_count');
            $table->index('likes_count');
            $table->index('shares_count');
            $table->index('metric_date');
        });

        // ================== Content Likes ==================
        Schema::create('content_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('content_id');
            $table->uuid('user_id');
            $table->string('reaction_type')->default('like');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['content_id', 'user_id']);
            $table->index(['user_id', 'content_id']);
            $table->index('reaction_type');
            $table->index('created_at');
        });

        // ================== Content Comments ==================
        Schema::create('content_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('content_id');
            $table->uuid('user_id')->nullable();
            $table->uuid('parent_id')->nullable();
            $table->text('comment');
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending');
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('replies_count')->default(0);
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('content_comments')->onDelete('cascade');

            $table->index('content_id');
            $table->index('parent_id');
            $table->index('status');
            $table->index('created_at');
            $table->index(['content_id', 'status']);
            $table->index(['user_id', 'status']);
        });

        // ================== Comment Likes ==================
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('comment_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('comment_id')->references('id')->on('content_comments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['comment_id', 'user_id']);
            $table->index(['user_id', 'comment_id']);
            $table->index(['comment_id', 'created_at']);
        });

        // ================== FAQ Categories ==================
        Schema::create('faq_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->json('slug');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('sort_order');
            $table->index('is_active');
        });

        // ================== FAQs ==================
        Schema::create('faqs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('question');
            $table->json('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->uuid('faq_category_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->unsignedInteger('not_helpful_count')->default(0);
            $table->float('helpfulness_ratio')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('faq_category_id')->references('id')->on('faq_categories')->onDelete('set null');

            $table->index('sort_order');
            $table->index('is_active');
            $table->index('faq_category_id');
            $table->index('helpfulness_ratio');
        });

        // ================== Tickets ==================
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ticket_number')->unique();
            $table->uuid('user_id')->nullable();
            $table->uuid('assigned_to')->nullable();
            $table->string('subject');
            $table->text('description');
            $table->enum('status', ['open', 'in_progress', 'on_hold', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('type', ['bug', 'feature_request', 'support', 'billing', 'other'])->default('support');
            $table->enum('source', ['web', 'email', 'phone', 'chat'])->default('web');
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->unsignedInteger('response_count')->default(0);
            $table->unsignedInteger('reopen_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');

            $table->index('ticket_number');
            $table->index('status');
            $table->index('priority');
            $table->index('type');
            $table->index('source');
            $table->index('assigned_to');
            $table->index('created_at');
            $table->index(['status', 'priority']);
            $table->index(['user_id', 'status']);
        });

        // ================== Ticket Status History ==================
        Schema::create('ticket_status_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_id');
            $table->uuid('changed_by')->nullable();
            $table->enum('old_status', ['open', 'in_progress', 'on_hold', 'resolved', 'closed'])->nullable();
            $table->enum('new_status', ['open', 'in_progress', 'on_hold', 'resolved', 'closed']);
            $table->text('change_reason')->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['ticket_id', 'created_at']);
            $table->index('new_status');
            $table->index('changed_by');
        });

        // ================== Ticket Messages ==================
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_id');
            $table->uuid('user_id')->nullable();
            $table->text('message');
            $table->enum('message_type', ['user', 'agent', 'system'])->default('user');
            $table->boolean('is_internal')->default(false);
            $table->boolean('is_first_response')->default(false);
            $table->json('attachments')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->index(['ticket_id', 'created_at']);
            $table->index('message_type');
            $table->index('is_internal');
            $table->index('is_first_response');
            $table->index(['user_id', 'ticket_id']);
        });

        // ================== Ticket Attachments ==================
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_id');
            $table->uuid('message_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedInteger('size');
            $table->string('disk')->default('local');
            $table->string('path');
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('message_id')->references('id')->on('ticket_messages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->index('ticket_id');
            $table->index('message_id');
            $table->index('mime_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('ticket_status_history');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories');
        Schema::dropIfExists('comment_likes');
        Schema::dropIfExists('content_comments');
        Schema::dropIfExists('content_likes');
        Schema::dropIfExists('content_metrics');
        Schema::dropIfExists('content_revisions');
        Schema::dropIfExists('content_tags');
        Schema::dropIfExists('content_categories');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('content_types');
    }
};
