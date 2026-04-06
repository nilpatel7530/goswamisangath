<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OccupationMasterSeeder extends Seeder
{
    public function run(): void
    {
        $occupations = [
            ['name' => 'Software Engineer', 'status' => 1, 'is_visible' => 1],
            ['name' => 'Doctor', 'status' => 1, 'is_visible' => 1],
            ['name' => 'Teacher', 'status' => 1, 'is_visible' => 1],
            ['name' => 'Business Owner', 'status' => 1, 'is_visible' => 1],
            ['name' => 'Government Employee', 'status' => 1, 'is_visible' => 1]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('occupation_master')->truncate();
        DB::table('occupation_master')->insert($occupations);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
