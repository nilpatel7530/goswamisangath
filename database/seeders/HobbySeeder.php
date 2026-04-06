<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HobbySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hobbies = [
            'Reading', 'Travel', 'Cooking', 'Music', 'Photography',
            'Fitness', 'Yoga', 'Dancing', 'Movies', 'Gaming',
            'Gardening', 'Art', 'Writing', 'Sports', 'Volunteering',
            'Pets', 'Fashion', 'Technology', 'Nature', 'Socializing'
        ];

        foreach ($hobbies as $hobby) {
            \App\Models\Hobby::firstOrCreate(['name' => $hobby], ['status' => 1]);
        }
    }
}
