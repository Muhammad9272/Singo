<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket_details, $reply, $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_details, $reply, $title)
    {
        //
        $this->ticket_details = $user_details;
        $this->reply = $reply;
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
        return $this->view('emails.ticket')->subject($title);
    }
}
