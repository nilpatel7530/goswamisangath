<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RaashiMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $raashis = [
            'Mesha (Aries)',
            'Vrishabha (Taurus)',
            'Mithuna (Gemini)',
            'Karka (Cancer)',
            'Simha (Leo)',
            'Kanya (Virgo)',
            'Tula (Libra)',
            'Vrishchika (Scorpio)',
            'Dhanu (Sagittarius)',
            'Makara (Capricorn)',
            'Kumbha (Aquarius)',
            'Meena (Pisces)',
        ];

        foreach ($raashis as $raashi) {
            DB::table('raashi_master')->insert([
                'name' => $raashi,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
