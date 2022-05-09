<?php

namespace App\Models;

use App\Models\Ticket;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements BannableContract, MustVerifyEmail
{

    use HasFactory;
    use Notifiable;
    use Bannable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->type == 1 || $this->type == 3 || $this->type == 2;
    }

    public function getUserType()
    {
        switch ($this->type) {
            case 0:
                return 'User';
            case 1:
                return 'Admin';
            case 2:
                return 'Moderator';
            case 3:
                return 'Superadmin';
        }
    }

    public function getPlan()
    {
        switch ($this->plan) {
            case 0:
                return 'Free';
            case 1:
                return 'Basic';
            case 2:
                return 'Premium';
        }
    }

    public function isPremium()
    {
        return $this->isPremium == 1;
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan');
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function users_1()
    {
        return $this->hasMany(Store::class);
    }

    public function users_2()
    {
        return $this->hasMany(Store::class);
    }

    public function users_3()
    {
        return $this->hasMany(Store::class);
    }

    public function reports()
    {
        return $this->hasOne(Payment::class);
    }

    public function users_4()
    {
        return $this->hasMany(Report::class);
    }

    public function users_5()
    {
        return $this->hasMany(Report::class);
    }

    public function users_6()
    {
        return $this->hasMany(UserRequest::class);
    }

    public function users_7()
    {
        return $this->hasMany(UserRequest::class);
    }

    public function usersetting()
    {
        return $this->belongsTo(UserSetting::class, 'id', 'user_id');
    }

    //client - ticket
    public function openCount()
    {
        $count = Ticket::where('user_id', $this->id)->where('status', 3)->get()->count();
        return $count;
    }

    public function answeredCount()
    {
        $count = Ticket::where('user_id', $this->id)->where('status', 2)->get()->count();
        return $count;
    }

    public function csReplyCount()
    {
        $count = Ticket::where('user_id', $this->id)->where('status', 1)->get()->count();
        return $count;
    }

    public function closeCount()
    {
        $count = Ticket::where('user_id', $this->id)->where('status', 0)->get()->count();
        return $count;
    }

    //admin-ticket

    public function openAdmin()
    {
        $count = Ticket::where('open', $this->id)->where('status', 3)->get()->count();
        return $count;
    }

    public function answeredAdmin()
    {
        $count = Ticket::where('open', $this->id)->where('status', 2)->get()->count();
        return $count;
    }

    public function csReplyAdmin()
    {
        $count = Ticket::where('open', $this->id)->where('status', 1)->get()->count();
        return $count;
    }

    public function closeAdmin()
    {
        $count = Ticket::where('open', $this->id)->where('status', 0)->get()->count();
        return $count;
    }

    public function getProfilePictureRouteAttribute()
    {
        return route('user.profile_picture', $this->id);
    }
}
