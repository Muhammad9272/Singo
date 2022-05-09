<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRequestStatusMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user,$request;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$request)
    {
        //
        $this -> user  = $user;
        $this -> request  = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user_request');
    }
}
