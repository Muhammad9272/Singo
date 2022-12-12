<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IndividualPresonMail extends Mailable
{
    use Queueable, SerializesModels;
    public $msg, $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msg, $title)
    {
        //
        
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
        return $this->view('emails.individual_person')->subject($title);
    }
}
