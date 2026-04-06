<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if settings already exist
        $existing = DB::table('payment_settings')->first();
        
        if (!$existing) {
            DB::table('payment_settings')->insert([
                'key_id' => 'rzp_test_S9CwEPw5v224vT',
                'key_secret' => '8Ch3QhkMzMLQoQYL12EkCxdp',
                'mode' => 'test',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Payment settings seeded successfully!');
        } else {
            $this->command->info('Payment settings already exist. Skipping seed.');
        }
    }
}
