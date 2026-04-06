<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HighestQualificationMasterSeeder extends Seeder
{
    public function run(): void
    {
        $qualifications = [
            ['name' => 'Bachelors', 'status' => 1, 'is_visible' => 1],
            ['name' => 'Masters', 'status' => 1, 'is_visible' => 1],
            ['name' => 'Doctorate', 'status' => 1, 'is_visible' => 1],
            ['name' => 'Diploma', 'status' => 1, 'is_visible' => 1],
            ['name' => 'High School', 'status' => 1, 'is_visible' => 1]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('highest_qualification_master')->truncate();
        DB::table('highest_qualification_master')->insert($qualifications);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
