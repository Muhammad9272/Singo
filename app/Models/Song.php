<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function fartist()
    {
        return $this->hasMany(FeaturedArtist::class);
    }

    public function audioLocale()
    {
        return $this->belongsTo(AudioLocale::class);
    }
}
