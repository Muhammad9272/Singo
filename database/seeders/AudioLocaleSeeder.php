<?php

namespace Database\Seeders;

use App\Models\AudioLocale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AudioLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = json_decode(Storage::get('audio_locales.json'));
        foreach ($genres as $genre) {
            AudioLocale::updateOrCreate([
                'name' => $genre->name
            ],[
                'slug' => $genre->id
            ]);
        }
    }
}
