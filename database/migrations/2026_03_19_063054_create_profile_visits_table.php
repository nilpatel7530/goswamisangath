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
        Schema::create('profile_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('visited_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('visited_at');
            $table->timestamps();
            
            // Optional: prevent duplicate visits if required (e.g., unique visitor + visited)
            // But PageController updates visited_at for every visit so duplicates might be fine or maybe not.
            // Since it uses insert() without checking existing except for first time, a unique constraint might fail if they visit again? Wait, the check was:
            // $hasVisited = ...->exists(); if (!$hasVisited) { ... insert ... }
            // So unique is safe.
            $table->unique(['visitor_id', 'visited_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_visits');
    }
};
