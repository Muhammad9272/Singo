<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = json_decode(Storage::get('languages.json'));
        foreach ($genres as $genre) {
            Language::updateOrCreate([
                'name' => $genre->name
            ],[
                'slug' => $genre->id
            ]);
        }
    }
}
