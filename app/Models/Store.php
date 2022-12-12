<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function users_2()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function users_3()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function store()
    {
        return $this->hasMany(Report::class);
    }

}
