<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency',
        'amount',
        'payoutMethod',
        'transactionId',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getStatusText() {
        switch ($this->status) {
            case -1:
                return 'Declined';
            case 0:
                return 'Pending';
            case 1:
                return 'Processed';
        }
    }

    public function getStatusColor() {
        switch ($this->status) {
            case -1:
                return 'danger';
            case 0:
                return 'warning';
            case 1:
                return 'success';
        }
    }
}
