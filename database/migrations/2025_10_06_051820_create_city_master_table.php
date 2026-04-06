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
        Schema::dropIfExists('city_master');
        Schema::create('city_master', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('city_master')->nullable();
            $table->foreignId('state_id')->constrained('state_master')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->tinyInteger('is_visible')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_master');
    }
};