<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Job;
use App\Models\Plan;

use App\Mail\IndividualPresonMail;
use App\Mail\IndividualUserMail;

use App\Jobs\AllDynamicUser;
use App\Jobs\AllUserQueue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class MailController extends Controller
{
    public function mail()
    {
        if (auth()->user()->type == 0) {
            return redirect()->route('home');
        }

        $users = User::latest()->get();
        $job = Job::latest()->get();

        $plans = Plan::orderBy('amount', 'ASC')->get();

        return view('mail.index', ['users' => $users, 'job' => $job, 'plans' => $plans]);
    }

    public function mail_individual(Request $request)
    {
        return redirect()->route('mail.individual.show', $request->user);
    }

    public function mail_individual_show($id)
    {
        $user_details = User::findOrFail($id);

        return view('mail.individual_user', ['user_details' => $user_details]);
    }

    public function mail_individual_user(Request $request)
    {
        $msg = $request->message;
        $mail = $request->receivers_mail;
        $title = $request->title;

        $user_details = User::findOrFail($request->user_id);

        Mail::to($mail)->queue(new IndividualUserMail($user_details, $msg, $title));

        Session::flash('success', "Mail sent successfully to $mail.");
        return redirect()->route('mail');
    }

    public function mail_all_user(Request $request)
    {
        $msg = $request->message;
        $title = $request->title;

        dispatch(function () use ($msg, $title) {
            foreach (User::all() as $user) {
                AllUserQueue::dispatch($user, $msg, $title)
                    ->delay(random_int(10, 15));
            }
        });

        return redirect()
            ->route('mail')
            ->with('success', "Mail dispatched for all users.");
    }

    public function mail_dynamic_plan(Request $request)
    {
        $msg = $request->message;
        $title = $request->title;
        $plan_id = $request->plan_id;

        dispatch(function () use ($msg, $title, $plan_id) {
            $allUsers = User::where('plan', $plan_id)->get();

            foreach ($allUsers as $user) {
                AllDynamicUser::dispatch($user, $msg, $title)
                    ->delay(random_int(10, 15));
            }
        });

        Session::flash('success', "Jobs queued successfully dynamic plan users.");

        return redirect()->route('mail');
    }

    public function mail_individual_person(Request $request)
    {
        $msg = $request->message;
        $mail = $request->receivers_mail;
        $title = $request->title;
        Mail::to($mail)->queue(new IndividualPresonMail ($msg, $title));

        Session::flash('success', " Mail sent successfully to $mail.");
        return redirect()->route('mail');
    }

    public function clear_queue()
    {
        Job::whereNotNull('id')->delete();
        Session::flash('success', "Queue Table Cleared Successfully");
        return redirect()->route('mail');
    }
}
