<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationMasterSeeder extends Seeder
{
    public function run(): void
    {
        $educations = [
            // Assuming Bachelors has ID 1
            ['name' => 'B.Tech', 'highest_qualification_id' => 1, 'status' => 1, 'is_visible' => 1],
            ['name' => 'B.Sc', 'highest_qualification_id' => 1, 'status' => 1, 'is_visible' => 1],
            ['name' => 'BBA', 'highest_qualification_id' => 1, 'status' => 1, 'is_visible' => 1],
            // Assuming Masters has ID 2
            ['name' => 'M.Tech', 'highest_qualification_id' => 2, 'status' => 1, 'is_visible' => 1],
            ['name' => 'MBA', 'highest_qualification_id' => 2, 'status' => 1, 'is_visible' => 1],
            // Assuming Doctorate has ID 3
            ['name' => 'Ph.D in Science', 'highest_qualification_id' => 3, 'status' => 1, 'is_visible' => 1],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('education_master')->truncate();
        DB::table('education_master')->insert($educations);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
