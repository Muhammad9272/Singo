<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Store extends Model
{
    use HasFactory;

    protected $table = 'user_store_bkdn';

    protected $fillable = ['album_id', 'store_id'];

    public function album_name()
    {
        return $this->belongsTo(Album::class, 'album_id', 'id');
    }

    public function store_name()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

}
