<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AlbumSubmission extends Model
{
    use HasFactory;

    protected $guarded = [];

    const PUBLISH_STATUS_IN_PROGRESS = 1;
    const PUBLISH_STATUS_PENDING = 2;
    const PUBLISH_STATUS_DELIVERED = 3;
    const PUBLISH_STATUS_DENIED = 4;
    const PUBLISH_STATUS_FAILED = 5;

    const PUBLISH_STATUSES = [
        self::PUBLISH_STATUS_IN_PROGRESS => "In Progress",
        self::PUBLISH_STATUS_PENDING => "Pending",
        self::PUBLISH_STATUS_DELIVERED => "Submitted",
        self::PUBLISH_STATUS_DENIED => "Denied",
        self::PUBLISH_STATUS_FAILED => "Failed",
    ];

    protected $casts = [
        'logs' => 'json'
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
