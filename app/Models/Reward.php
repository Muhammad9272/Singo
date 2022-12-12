<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;
    protected $fillable = ['title','subtitle', 'detail', 'points', 'rank','is_physical', 'status'];

    public function rewardrequests()
    {
        return $this->hasMany('App\Models\RewardRequest','reward_id');
    }

    // public function users()
    // {
    //     return $this->hasMany('App\Models\RewardRequest','user_id');
    // }

    // return $this->hasMany('App\Models\Blog','category_id');

}
