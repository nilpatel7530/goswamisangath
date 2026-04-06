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
        // Drop the nakshtra column from users table if it still exists
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nakshtra')) {
                $table->dropColumn('nakshtra');
            }
        });

        // Drop the nakshatra_master table if it exists
        Schema::dropIfExists('nakshatra_master');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('nakshatra_master', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nakshtra')->nullable()->after('raashi');
        });
    }
};
