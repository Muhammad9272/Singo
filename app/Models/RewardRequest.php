<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardRequest extends Model
{
    use HasFactory;

    public function reward()
    {
        return $this->belongsTo('App\Models\Reward','reward_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
