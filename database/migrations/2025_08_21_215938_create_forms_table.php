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
        Schema::create('forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //Set Type from page/modal/slideover
            $table->string('type')->default('page')->nullable();
            //Set Name And Key
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->string('key')->unique()->index();
            //Set Form Action
            $table->string('endpoint')->default('/')->nullable();
            $table->string('method')->default('POST')->nullable();
            //Form Control
            $table->boolean('is_active')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('form_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('form_id');
            $table->uuid('sub_form')->nullable();
            //Set type of field like text, email, number, select, checkbox, radio, textarea, file, date, time, datetime, password
            $table->string('type')->default('text')->nullable();
            //Set Name and Key for this field
            $table->json('label')->nullable();
            $table->json('placeholder')->nullable();
            $table->json('hint')->nullable();
            $table->string('name')->index();
            $table->string('group')->nullable();
            //Set Default value for this field
            $table->json('default')->nullable();
            $table->integer('order')->default(0)->nullable();
            //Is Field Required?
            $table->boolean('is_required')->default(0)->nullable();
            $table->boolean('is_multi')->default(0)->nullable();
            $table->json('required_message')->nullable();
            //Is Field Reactive?
            $table->boolean('is_reactive')->default(0)->nullable();
            $table->string('reactive_field')->nullable();
            $table->string('reactive_where')->nullable();
            //Is Table Select?
            $table->boolean('is_relation')->default(0)->nullable();
            $table->string('relation_name')->nullable();
            $table->string('relation_column')->nullable();
            //Check if Field has options like Select
            $table->boolean('has_options')->default(0)->nullable();
            $table->json('options')->nullable();
            //Validations
            $table->boolean('has_validation')->default(0)->nullable();
            $table->json('validation')->nullable();
            //For Meta Injection
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->foreign('sub_form')->references('id')->on('forms');
        });

        Schema::create('form_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //Morph
            $table->string('model_type')->nullable();
            $table->uuid('model_id')->nullable();
            //Morph Service
            $table->string('service_type')->nullable();
            $table->uuid('service_id')->nullable();
            $table->uuid('form_id');
            $table->string('status')->default('pending')->nullable();
            $table->json('payload')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('forms');
        });

        Schema::create('form_request_metas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('model_id')->nullable();
            $table->string('model_type')->nullable();
            $table->uuid('form_request_id');
            $table->string('key')->index();
            $table->json('value')->nullable();
            $table->timestamps();

            $table->foreign('form_request_id')->references('id')->on('form_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_request_metas');
        Schema::dropIfExists('form_requests');
        Schema::dropIfExists('form_options');
        Schema::dropIfExists('forms');
    }
};
