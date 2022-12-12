<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;
    protected $table = 'userrequests';


    public function users_6() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function users_7() {
        return $this->belongsTo(User::class, 'passed_by', 'id');
    }
    public function album(){
        return $this->hasOne(Album::class, 'id', 'album_id');
    }
    public function request() {
        return $this->hasOne(Album::class)->sortByDesc('created_at')->take(1)->toArray();
    }



}
