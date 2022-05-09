<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\Plan;
use App\Models\WelcomeAlert;

use Carbon\Carbon;

use App\Mail\OtpMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     */
    public function index()
    {
        $i = "0";
        $user_id = auth()->user()->id;
        $cheak = UserSetting::where('user_id', $user_id)->get();

        foreach ($cheak as $ck) {
            $id = $ck->id;
            $i = $i + 1;
        }

        if ($i == "0") {
            $save = new UserSetting;
            $save->user_id = $user_id;
            $save->dark_mode = 1;
            $save->save();
        }


        //welcome alert
        $welcome = WelcomeAlert::where('status', 1)->first();
        $welcomeAlert = 0;
        if ($welcome) {
            if ($welcome->id != auth()->user()->welcome_alert) {
                $alert = 1;
                $welcomeAlert = $welcome;
            } else {
                $alert = 0;
            }
        } else {
            $alert = 0;
        }

        $order = array('Free', 'Premium', 'Basic');

        $plans = Plan::where('status', 1)->get();

        $plans = $plans->sort(function ($a, $b) use ($order) {
            $pos_a = array_search($a->title, $order);
            $pos_b = array_search($b->title, $order);
            return $pos_a - $pos_b;
        });

        return view('home', [
            'plans' => $plans,
            'alert' => $alert,
            'welcomeAlert' => $welcomeAlert
        ]);
    }

    public function otp_check()
    {
        $id = auth()->user()->id;
        $user_info = User::findOrFail($id);

        if ($user_info->email_verified_at != null) {
            return redirect()->route('home');
        }

        return view('auth.otp-verify');
    }
    public function otp_mail()
    {
        $now = Carbon::now();
        $id = auth()->user()->id;
        $user_info = User::findOrFail($id);
        $time = Carbon::parse($user_info->otp_created_at);
        $check = $time->addMinute();

        if ($check > $now) {
            Session::flash('error', "Please wait and try again.");
            return redirect()->route('verification.notice');
        }

        $otp = rand(1000, 9999);
        $user_info->otp = $otp;
        $user_info->otp_created_at = Carbon::now();
        $user_info->save();
        $msg = $otp;
        Mail::to($user_info->email)->send(new OtpMail($user_info, $msg));

        Session::flash('success', "A brand new OTP sent to your mail: $user_info->email");
        return redirect()->route('verification.notice');

    }
    public function otp_validate(Request $request)
    {
        $response = $request->otp_1.$request->otp_2.$request->otp_3.$request->otp_4;
        $now = Carbon::now();
        $id = auth()->user()->id;
        $user_info = User::findOrFail($id);
        if($user_info->otp == $response )
        {
            $user_info->email_verified_at = $now;
            $user_info->save();

            Session::flash('success', "Mail verified successfully");
            return redirect()->route('home');
        }
        else
        {
            Session::flash('error', "You entered a wrong OTP. Please try again");
            return redirect()->back();
        }

    }


}
