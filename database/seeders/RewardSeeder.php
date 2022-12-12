<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reward;
use Illuminate\Support\Facades\Storage;
class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
       $rewards = json_decode(Storage::get('rewards.json'));
        foreach ($rewards as $reward) {
            Reward::updateOrCreate([
                "title" => $reward->title,
                "subtitle" => $reward->subtitle,
                "points" =>$reward->points,
                "rank" =>$reward->rank,
                "detail" => $reward->detail,
                "photo" => $reward->photo,
                "badge" => $reward->badge,
            ]);
        }

    }
}
