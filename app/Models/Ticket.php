<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;


    public function getTicketPriority() {
        switch ($this->priority) {
            case 1:
                return 'High';
            case 2:
                return 'Mid';
            case 3:
                return 'Low';
        }
    }

    public function getTicketPriorityColor() {
        switch ($this->priority) {
            case 1:
                return 'danger';
            case 2:
                return 'warning';
            case 3:
                return 'primary';
        }
    }


    public function getTicketStatus() {
        switch ($this->status) {
            case 0:
                return 'Closed';
            case 1:
                return 'User Reply';
            case 2:
                return 'Answered';
            case 3:
                return 'Open';
        }
    }


    public function getTicketStatusColor() {
        switch ($this->status) {
            case 0:
                return 'primary';
            case 1:
                return 'warning';
            case 2:
                return 'info';
            case 3:
                return 'danger';
        }
    }

    public function getTicketType(){
        switch ($this->type){
            case 1:
                return 'Technical';
            case 2:
                return 'General';
        }
    }

    public function getTicketTypeColor(){
        switch ($this->type){
            case 1:
                return 'warning';
            case 2:
                return 'primary';
        }
    }

    public function openCount(){
        $count = Ticket::where('status', 3)->get()->count();
        return $count;
    }
    public function answeredCount(){
        $count = Ticket::where('status', 2)->get()->count();
        return $count;
    }
    public function csReplyCount(){
        $count = Ticket::where('status', 1)->get()->count();
        return $count;
    }
    public function closeCount(){
        $count = Ticket::where('status', 0)->get()->count();
        return $count;
    }

    public function messages(){
        return $this->hasMany(TicketMessage::class);
    }

    public function open(){
        return $this->belongsTo(User::class, 'open', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
