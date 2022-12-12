<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::truncate();

        $isrc_codes = collect(explode(PHP_EOL, Storage::get('temp_isrc_codes.csv')))
            ->filter(function ($code) {
                return strlen($code) === 15;
            });

        Setting::factory()->createOne([
            'name' => 'temp_isrc_codes',
            'value' => $isrc_codes->toJson(),
        ]);

        Setting::factory()->createOne([
            'name' => 'last_used_isrc',
            'value' => 'DE1CV2100001',
        ]);
    }
}
