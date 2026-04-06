<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_name' => 'GoswamiSangath',
            'site_name_type' => 'text',
            'site_name_image' => null,
            'footer_info' => 'Connecting hearts across the Goswami community.',
            'demo_mode' => 'off',
            'demo_bonus_interests_limit' => '5',
            'hide_contact_if_not_accepted' => 'on',
            'hide_address_if_not_accepted' => 'on',
        ];
 
        foreach ($settings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
