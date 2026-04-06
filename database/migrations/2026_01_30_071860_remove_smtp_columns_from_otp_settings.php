<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Remove dynamic SMTP credentials; email OTP uses .env MAIL_* only.
     */
    public function up(): void
    {
        Schema::table('otp_settings', function (Blueprint $table) {
            $table->dropColumn([
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'smtp_encryption',
                'from_address',
                'from_name',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otp_settings', function (Blueprint $table) {
            $table->string('smtp_host')->nullable()->after('otp_method');
            $table->unsignedSmallInteger('smtp_port')->nullable()->after('smtp_host');
            $table->string('smtp_username')->nullable()->after('smtp_port');
            $table->text('smtp_password')->nullable()->after('smtp_username');
            $table->string('smtp_encryption', 10)->nullable()->after('smtp_password');
            $table->string('from_address')->nullable()->after('smtp_encryption');
            $table->string('from_name')->nullable()->after('from_address');
        });
    }
};
