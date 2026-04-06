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
        Schema::table('users', function (Blueprint $table) {
            // Remove Nakshatra if it exists
            if (Schema::hasColumn('users', 'nakshtra')) {
                $table->dropColumn('nakshtra');
            }
            
            // Add new fields if they don't exist
            if (!Schema::hasColumn('users', 'residential_address')) {
                $table->text('residential_address')->nullable()->after('city');
            }
            if (!Schema::hasColumn('users', 'working_address')) {
                $table->text('working_address')->nullable()->after('residential_address');
            }
            if (!Schema::hasColumn('users', 'additional_info')) {
                $table->text('additional_info')->nullable()->after('languages_known');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nakshtra')) {
                $table->string('nakshtra')->nullable()->after('caste');
            }
            $table->dropColumn(['residential_address', 'working_address', 'additional_info']);
        });
    }
};
