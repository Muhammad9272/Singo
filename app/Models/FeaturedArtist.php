<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedArtist extends Model
{
    use HasFactory;

    protected $table = 'featured_artists';

    protected $guarded = [];

    public function fartist()
    {
        return $this->belongsTo(Song::class, 'id', 'song_id');
    }
}
