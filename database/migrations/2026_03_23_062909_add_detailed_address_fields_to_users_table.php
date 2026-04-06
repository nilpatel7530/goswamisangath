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
            $table->string('residential_country')->nullable()->after('residential_address');
            $table->string('residential_state')->nullable()->after('residential_country');
            $table->string('residential_city')->nullable()->after('residential_state');
            $table->string('working_country')->nullable()->after('working_address');
            $table->string('working_state')->nullable()->after('working_country');
            $table->string('working_city')->nullable()->after('working_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'residential_country', 
                'residential_state', 
                'residential_city',
                'working_country', 
                'working_state', 
                'working_city'
            ]);
        });
    }
};
