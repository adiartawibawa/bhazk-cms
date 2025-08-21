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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //Add Status For Tickets
            $table->string('status')->nullable()->default('pending');
            //Link User To Ticket With Morph
            $table->uuid('user_id');
            $table->string('user_type');
            //Add User For Tickets
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            //Add User For Tickets
            $table->string('subject');
            $table->string('code')->unique();
            $table->longText('message')->nullable();
            //Get Last update time
            $table->timestamp('last_update')->nullable();
            //Is closed
            $table->boolean('is_closed')->default(false)->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //Ref
            $table->uuid('ticket_id');
            //Link User To Ticket With Morph
            $table->uuid('user_id');
            $table->string('user_type');
            //Add User For Tickets
            $table->longText('response');
            $table->timestamps();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ticket_comments');
        Schema::dropIfExists('tickets');
        Schema::enableForeignKeyConstraints();
    }
};
