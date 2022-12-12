<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(SettingSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(AudioLocaleSeeder::class);
        $this->call(GenreSeeder::class);
        $this->call(RewardSeeder::class);
    }
}
