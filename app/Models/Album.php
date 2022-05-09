<?php

namespace App\Models;

use App\Jobs\PublishAlbumToFuga;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Album extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_DECLINED = -1;
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_DELIVERED = 2;
    const STATUS_NEED_EDIT = 3;

    const STATUES = [
        self::STATUS_DECLINED => 'Declined',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_DELIVERED => 'Delivered',
        self::STATUS_NEED_EDIT => 'Need Edit',
    ];

    const PUBLISHER_FUGA = 1;

    const PUBLISHERS = [
        self::PUBLISHER_FUGA => 'Fuga'
    ];

    protected $dates = [
        'release'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updating(function ($album) {
            $changes = $album->getDirty();

            if (array_key_exists('status', $changes)) {
                if ($changes['status'] == self::STATUS_APPROVED) {
                    PublishAlbumToFuga::dispatch($album);
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function getStatusColor()
    {
        switch ($this->status) {
            case 0:
                return 'warning';
            case 1:
                return 'info';
            case 2:
                return 'success';
            case 3:
                return 'secondary';
            case -1:
                return 'danger';
        }
    }

    public function getStatusText()
    {
        switch ($this->status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Approved';
            case 2:
                return 'Delivered';
            case 3:
                return 'Need Edit';
            case -1:
                return 'Declined';
        }
    }

    public function request()
    {
        return $this->belongsTo(UserRequest::class, 'id', 'album_id');
    }

    public function isExplicit()
    {
        return $this->songs()->where('isExplicit', 1)->exists();
    }

    public function getFugaApiSubmissionData()
    {
        return [
            "name" => $this->title,
            "label" => "4786205414",
            "parental_advisory" => $this->isExplicit() ? "YES" : "NO",
            "consumer_release_time" => "00:00:00.000Z",
            "consumer_release_date" => $this->release->format('Y-m-d'),
            "original_release_date" => $this->release->format('Y-m-d'),
            "release_format_type" => $this->getReleaseFormatType(),
            "catalog_tier" => "FRONT",
            "genre" => strtoupper($this->genre->slug),
            "compilation" => "false",
            "recording_year" => date('Y'),
            "c_line_year" => date('Y'),
            "c_line_text" => "Singo.io",
            "p_line_year" => date('Y'),
            "p_line_text" => "Singo.io",
            "language" => $this->language ? $this->language->slug : 'EN',
        ];
    }

    public function getReleaseFormatType()
    {
        $totalSongs = $this->songs()->count();

        if ($totalSongs >= 1 && $totalSongs <= 3) {
            return 'SINGLE';
        }

        if ($totalSongs >= 4 && $totalSongs <= 6) {
            return 'EP';
        }

        if ($totalSongs > 7) {
            return 'ALBUM';
        }
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function submissions()
    {
        return $this->hasMany(AlbumSubmission::class);
    }

    public function deliverableStores()
    {
        return $this->hasManyThrough(
            Store::class,
            User_Store::class,
            'album_id',
            'id',
            'id',
            'store_id',
        );
    }

    public function getCoverRouteAttribute()
    {
        return route('album.cover', $this->id);
    }
}
