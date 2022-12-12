<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user_details, $msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_details, $msg)
    {
        //
        $this->user_details = $user_details;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = 'Verify Email Address';
        return $this->view('emails.otp')->subject($title);
    }
}
