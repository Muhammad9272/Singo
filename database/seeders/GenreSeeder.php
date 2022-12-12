<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = json_decode(Storage::get('genres.json'));
        foreach ($genres as $genre) {
            Genre::updateOrCreate([
                'name' => $genre->name
            ],[
                'slug' => $genre->id
            ]);
        }
    }
}
