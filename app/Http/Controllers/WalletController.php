<?php

namespace App\Http\Controllers;
use App\Models\Report;
use App\Models\User;

use App\Notifications\Notifications;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Request_Payout;

class WalletController extends Controller
{
    public function __construct() {
        return $this->middleware('auth');
    }

    public function index() {
        $reports= Report::with('store')->where('user_id',auth()->user()->id)->latest()->get();
        return view('wallet', ['user' => auth()->user()],['reports' => $reports]);
    }

    public function updatePayoutSettings(Request $request) {
        
        $request->validate([
            'btcAddress' => 'string|max:255|nullable',
            'ltcAddress' => 'string|max:255|nullable',
            'ethAddress' => 'string|max:255|nullable',
            'paypalEmail' => 'email|max:255|nullable',
            'iban' => 'string|max:255|nullable'
        ]);
        auth()->user()->btcAddress = $request->btcAddress;
        auth()->user()->ltcAddress = $request->ltcAddress;
        auth()->user()->ethAddress = $request->ethAddress;
        auth()->user()->paypalEmail = $request->paypalEmail;
        auth()->user()->iban = $request->iban;
        auth()->user()->save();

        $reports= Report::where('user_id',auth()->user()->id)->latest()->get();
        $this->message('success', 'Your Payout Method Updated Successfully');       
        return redirect()->back();
    }

    public function createPayoutRequest(Request $request) {
        
        if(auth()->user()->balance <= 0) {
            return redirect()->route('wallet')->with('error', 'Your don\'t have enough balance to create a payout');
        }

        $payout = auth()->user()->payouts()->create([
            'currency' => '$',
            'amount' => $request->amount,
            'payoutMethod' => $request->payoutMethod
        ]);

        auth()->user()->balance = auth()->user()->balance - $request->amount;
        auth()->user()->save();

            //send notification
                $route = "wallet";
                $type = "payout request";
                $route_id = "";
                $name = "";
                 
                //notification for users
                $message = "We have received your payout request of ".$request->amount. " €";
                $user = auth()->user();
                $user->notify(new Notifications($name, $route, $type, $message, $route_id));
            
                //notification for superadmin
                $route = "admin.payouts";
                $message = auth()->user()->name." requested a payout of: " .$request->amount. " €" ;
                foreach (User::where('type', 3 )->get() as $admin) {
                $admin->notify(new Notifications($name, $route, $type, $message, $route_id));
                }
    
    

        //Payout Request Mail to Client
        
        Mail::to(auth()->user()->email)->send(new Request_Payout($payout));

        return redirect()->route('wallet')->with('success', 'You payout request was successfully created');
    }
}
