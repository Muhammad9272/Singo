<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AllUserMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user_details, $msg, $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_details, $msg, $title)
    {
        //
        $this->user_details = $user_details;
        $this->msg = $msg;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = $this->title;
        return $this->view('emails.all_user')->subject($title);
    }
}

