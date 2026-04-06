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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_image')->nullable();
            $table->string('gender')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->date('dob')->nullable();
            $table->string('birth_time')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('raashi')->nullable();
            $table->string('caste')->nullable();
            $table->string('nakshtra')->nullable();
            $table->string('naadi')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('physically_handicap')->nullable();
            $table->string('diet')->nullable();
            $table->text('languages_known')->nullable();
            $table->string('highest_education')->nullable();
            $table->string('education_details')->nullable();
            $table->string('employed_in')->nullable();
            $table->string('occupation')->nullable();
            $table->string('annual_income')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('mobile_number')->nullable()->unique();
            $table->string('google_id')->nullable()->unique();
            $table->enum('role', ['user', 'admin'])->default('user');
            
            // Foreign keys if needed by relations (kept for potential future use)
            $table->foreignId('highest_education_id')->nullable()->constrained('highest_qualification_master')->onDelete('set null');
            $table->foreignId('education_id')->nullable()->constrained('education_master')->onDelete('set null');
            $table->foreignId('occupation_id')->nullable()->constrained('occupation_master')->onDelete('set null');
            $table->foreignId('country_id')->nullable()->constrained('country_manage')->onDelete('set null');
            $table->foreignId('state_id')->nullable()->constrained('state_master')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained('city_master')->onDelete('set null');
            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
