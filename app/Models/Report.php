<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users_4()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function users_5()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
