<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryManageSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'India', 'sortname' => 'IN', 'phone_code' => 91, 'status' => 1],
            ['name' => 'United States', 'sortname' => 'US', 'phone_code' => 1, 'status' => 1],
            ['name' => 'United Kingdom', 'sortname' => 'UK', 'phone_code' => 44, 'status' => 1],
            ['name' => 'Canada', 'sortname' => 'CA', 'phone_code' => 1, 'status' => 1],
            ['name' => 'Australia', 'sortname' => 'AU', 'phone_code' => 61, 'status' => 1]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('country_manage')->truncate();
        DB::table('country_manage')->insert($countries);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
