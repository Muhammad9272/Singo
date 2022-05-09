<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Import extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    const IMPORT_TYPE_USERS_PAYMENT_REPORT = 1;
    const IMPORT_TYPE_USERS_PAYMENT_REPORT_FUGA = 2;

    const IMPORT_STATUS_PENDING = 1;
    const IMPORT_STATUS_QUEUED = 2;
    const IMPORT_STATUS_PROCESSED = 3;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($import) {
            if (Storage::exists($import->filepath)) {
                Storage::delete($import->filepath);
            }

            if (Storage::exists($import->log_filepath)) {
                Storage::delete($import->log_filepath);
            }
        });
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return [
            IMPORT_TYPE_USERS_PAYMENT_REPORT => "User's payment report - Orchard",
            IMPORT_TYPE_USERS_PAYMENT_REPORT_FUGA => "User's payment report - Fuga"
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
