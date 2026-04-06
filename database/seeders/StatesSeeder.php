<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create India country
        $india = DB::table('country_manage')->where('name', 'India')->first();
        
        if (!$india) {
            $indiaId = DB::table('country_manage')->insertGetId([
                'name' => 'India',
                'sortname' => 'IN',
                'phone_code' => '+91',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $indiaId = $india->id;
        }

        // All Indian States (28 states + 8 Union Territories = 36 total)
        $states = [
            // States
            ['name' => 'Andhra Pradesh', 'country_id' => $indiaId],
            ['name' => 'Arunachal Pradesh', 'country_id' => $indiaId],
            ['name' => 'Assam', 'country_id' => $indiaId],
            ['name' => 'Bihar', 'country_id' => $indiaId],
            ['name' => 'Chhattisgarh', 'country_id' => $indiaId],
            ['name' => 'Goa', 'country_id' => $indiaId],
            ['name' => 'Gujarat', 'country_id' => $indiaId],
            ['name' => 'Haryana', 'country_id' => $indiaId],
            ['name' => 'Himachal Pradesh', 'country_id' => $indiaId],
            ['name' => 'Jharkhand', 'country_id' => $indiaId],
            ['name' => 'Karnataka', 'country_id' => $indiaId],
            ['name' => 'Kerala', 'country_id' => $indiaId],
            ['name' => 'Madhya Pradesh', 'country_id' => $indiaId],
            ['name' => 'Maharashtra', 'country_id' => $indiaId],
            ['name' => 'Manipur', 'country_id' => $indiaId],
            ['name' => 'Meghalaya', 'country_id' => $indiaId],
            ['name' => 'Mizoram', 'country_id' => $indiaId],
            ['name' => 'Nagaland', 'country_id' => $indiaId],
            ['name' => 'Odisha', 'country_id' => $indiaId],
            ['name' => 'Punjab', 'country_id' => $indiaId],
            ['name' => 'Rajasthan', 'country_id' => $indiaId],
            ['name' => 'Sikkim', 'country_id' => $indiaId],
            ['name' => 'Tamil Nadu', 'country_id' => $indiaId],
            ['name' => 'Telangana', 'country_id' => $indiaId],
            ['name' => 'Tripura', 'country_id' => $indiaId],
            ['name' => 'Uttar Pradesh', 'country_id' => $indiaId],
            ['name' => 'Uttarakhand', 'country_id' => $indiaId],
            ['name' => 'West Bengal', 'country_id' => $indiaId],
            
            // Union Territories
            ['name' => 'Andaman and Nicobar Islands', 'country_id' => $indiaId],
            ['name' => 'Chandigarh', 'country_id' => $indiaId],
            ['name' => 'Dadra and Nagar Haveli and Daman and Diu', 'country_id' => $indiaId],
            ['name' => 'Delhi', 'country_id' => $indiaId],
            ['name' => 'Jammu and Kashmir', 'country_id' => $indiaId],
            ['name' => 'Ladakh', 'country_id' => $indiaId],
            ['name' => 'Lakshadweep', 'country_id' => $indiaId],
            ['name' => 'Puducherry', 'country_id' => $indiaId],
        ];

        // Insert states if they don't exist
        foreach ($states as $state) {
            $existing = DB::table('state_master')
                ->where('name', $state['name'])
                ->where('country_id', $indiaId)
                ->first();

            if (!$existing) {
                DB::table('state_master')->insert([
                    'name' => $state['name'],
                    'country_id' => $state['country_id'],
                    'status' => 'active',
                    'is_visible' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Indian states seeded successfully!');
    }
}
