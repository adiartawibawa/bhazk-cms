<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        /**
         * NOTE:
         * - All PKs/FKs use UUIDs.
         * - Avoid cyclic FKs by adding FK pointers after both tables are created.
         * - Translatable columns are stored in JSON.
         * - For multilingual slug uniqueness, use slug_index (string) as the canonical value.
         */

        /**
         * ROLES & USERS
         * One-to-Many Relationship: A single role can be assigned to many users.
         */
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignUuid('role_id')->constrained('roles')->cascadeOnDelete();
            $table->timestamps();
        });

        /**
         * CONTENTS (pointer/metadata) — without FK to revisions yet
         * The foreign keys for revisions will be added after the content_revisions table is created.
         */
        Schema::create('contents', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('type')->default('post')->index(); // post, page, product, media, etc.
            $table->foreignUuid('author_id')->constrained('users')->cascadeOnDelete();

            // Multilingual: JSON slug + slug_index for uniqueness/routing
            $table->json('slug')->nullable();      // {"en": "...", "id": "..."}
            $table->string('slug_index')->index(); // canonical (e.g., default locale), unique per type if needed

            // Pointers to revisions (FK will be added via alter)
            $table->uuid('latest_revision_id')->nullable();
            $table->uuid('published_revision_id')->nullable();

            $table->timestamps();

            // Slug uniqueness per type (optional: remove type if global unique is desired)
            $table->unique(['type', 'slug_index']);
        });

        /**
         * CONTENT REVISIONS — all content body is stored here
         */
        Schema::create('content_revisions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('content_id')->constrained('contents')->cascadeOnDelete();

            $table->unsignedInteger('version'); // 1,2,3,...
            $table->json('title');              // translatable
            $table->json('body')->nullable();   // translatable (use json for easy structured/block content)
            $table->enum('status', ['draft', 'pending', 'published'])->default('draft')->index();

            $table->foreignUuid('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['content_id', 'version']);
            $table->index(['content_id', 'status']);
        });

        /**
         * Add FK pointers after both tables exist (to avoid a circular dependency)
         */
        Schema::table('contents', function (Blueprint $table) {
            $table->foreign('latest_revision_id')
                ->references('id')->on('content_revisions')
                ->nullOnDelete(); // if revision is deleted, the pointer is set to null

            $table->foreign('published_revision_id')
                ->references('id')->on('content_revisions')
                ->nullOnDelete();
        });

        /**
         * CUSTOM FIELDS (definition & value per content)
         */
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');                 // internal/admin key
            $table->string('type');                 // text, number, boolean, media, select, repeater, json, etc.
            $table->json('label')->nullable();      // translatable label for UI
            $table->json('config')->nullable();     // schema/validation options (e.g. {"min":0,"max":10})
            $table->timestamps();
        });

        Schema::create('content_custom_field_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('content_id')->constrained('contents')->cascadeOnDelete();
            $table->foreignUuid('custom_field_id')->constrained('custom_fields')->cascadeOnDelete();

            // Store translatable (or complex) values in JSON
            $table->json('value')->nullable();
            $table->timestamps();

            $table->unique(['content_id', 'custom_field_id']);
            $table->index(['content_id']);
        });

        /**
         * TAXONOMIES & TERMS
         * - name & slug are translatable.
         * - slug_index is a canonical string for uniqueness per taxonomy.
         */
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');                   // {"en":"Category","id":"Kategori"}
            $table->string('type')->unique();       // category, tag, region, format, etc.
            $table->timestamps();
        });

        Schema::create('terms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('taxonomy_id')->constrained('taxonomies')->cascadeOnDelete();

            $table->json('name');                   // translatable
            $table->json('slug')->nullable();       // translatable
            $table->string('slug_index');           // canonical for routing/unique per taxonomy

            // Self-reference hierarchy
            $table->foreignUuid('parent_id')->nullable()->constrained('terms')->cascadeOnDelete();

            $table->timestamps();

            // Unique per taxonomy based on slug_index (NOT JSON)
            $table->unique(['taxonomy_id', 'slug_index']);
            $table->index(['taxonomy_id']);
        });

        Schema::create('content_terms', function (Blueprint $table) {
            $table->foreignUuid('content_id')->constrained('contents')->cascadeOnDelete();
            $table->foreignUuid('term_id')->constrained('terms')->cascadeOnDelete();
            $table->primary(['content_id', 'term_id']);
        });

        /**
         * MENUS + MENU ITEMS (polymorphic target for flexibility)
         */
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');                       // translatable
            $table->string('location')->nullable()->index(); // header, footer, sidebar
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('menu_id')->constrained('menus')->cascadeOnDelete();

            // Self-reference hierarchy
            $table->foreignUuid('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();

            $table->string('type')->default('link');  // link|content|term|custom|external
            $table->json('label');                      // translatable

            // Polymorphic target (e.g., Content UUID, Term UUID, or null if custom URL)
            $table->string('target_type')->nullable(); // App\Models\Content / App\Models\Term / etc
            $table->uuid('target_id')->nullable();

            // Fallback direct URL (for external/custom)
            $table->string('url')->nullable();

            $table->integer('position')->default(0);
            $table->timestamps();

            $table->index(['menu_id', 'parent_id']);
            $table->index(['target_type', 'target_id']);
        });

        /**
         * LEGAL DOCUMENTS (simple version; can be split into pointer + revisions if needed)
         */
        Schema::create('legal_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('type')->unique();       // terms, privacy, disclaimer, cookie, etc
            $table->string('slug')->unique();       // canonical slug
            $table->json('title');                  // translatable
            $table->json('body');                   // translatable (for multilingual support)
            $table->enum('status', ['draft', 'published'])->default('draft')->index();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Rollback the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop order respects FK constraints
        Schema::dropIfExists('legal_documents');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('content_terms');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('taxonomies');
        Schema::dropIfExists('content_custom_field_values');
        Schema::dropIfExists('custom_fields');

        // Drop FK pointers first to be safe (if DB still complains about circular dependency)
        Schema::table('contents', function (Blueprint $table) {
            try {
                $table->dropForeign(['latest_revision_id']);
            } catch (\Throwable $e) {
            }
            try {
                $table->dropForeign(['published_revision_id']);
            } catch (\Throwable $e) {
            }
        });

        Schema::dropIfExists('content_revisions');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
