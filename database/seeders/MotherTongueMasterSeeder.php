<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MotherTongueMaster;

class MotherTongueMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $motherTongues = [
            ['title' => 'English'],
            ['title' => 'Gujarati'],
            ['title' => 'Hindi'],
            ['title' => 'Marathi'],
            ['title' => 'Tamil'],
            ['title' => 'Telugu'],
        ];

        foreach ($motherTongues as $tongue) {
            MotherTongueMaster::create($tongue);
        }
    }
}