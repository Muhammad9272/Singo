<?php

namespace App\Jobs;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

use Illuminate\Support\Facades\Mail;
use App\Mail\AllDynamicUserMail;

class AllDynamicUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_details, $msg, $title;
    public $timeout = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_details, $msg, $title)
    {
        $this->user_details = $user_details;
        $this->msg = $msg;
        $this->title = $title;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_details = $this->user_details;
        $msg = $this->msg;
        $title = $this->title;

        Mail::to($user_details->email)
            ->send(new AllDynamicUserMail($user_details, $msg, $title));
    }
}
