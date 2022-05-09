<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Request_Payout extends Mailable
{
    use Queueable, SerializesModels;
    public $payout;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payout)
    {
        //
        $this -> payout  = $payout;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.request_payout');
    }
}
