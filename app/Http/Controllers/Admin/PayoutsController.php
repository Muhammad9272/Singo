<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Support\Facades\Mail;
use App\Mail\Payout_Status_Change;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayoutsController extends Controller
{
    public function __construct() {
        return $this->middleware('auth');
    }

    public function payouts() {
        $pending = Payout::with('user')->where('status', '=', '0')->get();
        $declined = Payout::with('user')->where('status', '=', '-1')->get();
        $accepted = Payout::with('user')->where('status', '=', '1')->get();
        //echo '<pre>'; print_r($accepted); die();
        return view('admin.payouts', [
            'pending' => $pending,
            'declined' => $declined,
            'accepted' => $accepted
        ]);
    }

    public function acceptPayout(Payout $payout) {
        $payout->status = 1;
        $payout->save();
        //Payout status Change Mail to Client on Accept
        $user = Payout::with('user')->findOrFail($payout->id)   ;     
        Mail::to($user->user->email)->send(new Payout_Status_Change($user));
        
        return redirect()->route('admin.payouts');
    }

    public function declinePayout(Payout $payout) {
        $payout->status = -1;
        $payout->save();

        $payout->user->balance = $payout->user->balance + $payout->amount;
        $payout->user->save();
        //Payout status Change Mail to Client on Decline
        $user = Payout::with('user')->findOrFail($payout->id)   ;     
        Mail::to($user->user->email)->send(new Payout_Status_Change($user));

        return redirect()->route('admin.payouts');
    }

    public function payouts_transaction(Request $request){
        if (!empty($request->transactionID)) {
            $payout = Payout::findOrFail($request->payout_id); 
            $payout->transactionId = $request->transactionID;
            $payout->status        = 1;
            $payout->updated_at    = Carbon::now();
            $payout->save();

            //$payout->status = 1;
            //$payout->save();
        //Payout status Change Mail to Client on Accept
        $user = Payout::with('user')->findOrFail($request->payout_id)   ;     
        Mail::to($user->user->email)->send(new Payout_Status_Change($user));

            return redirect()->route('admin.payouts')->with('success','Transaction Id added successfully!');
        } else {
            return redirect()->back();
        }
        

    }

    public function get_ajax_payouts(Request $request){
        $payout_id = $request->payoutID;

        $accepted = Payout::with('user')->where('id', '=', $payout_id)->first();

        if ($accepted->payoutMethod=='Paypal') {
            $payoutInfo = (!is_null($accepted->user->paypalEmail)) ? $accepted->user->paypalEmail: "";
        } elseif ($accepted->payoutMethod=='Bitcoin') {
            $payoutInfo = (!is_null($accepted->user->btcAddress)) ? $accepted->user->btcAddress: "";
        } elseif ($accepted->payoutMethod=='Litecoin') {
            $payoutInfo = (!is_null($accepted->user->ltcAddress)) ? $accepted->user->ltcAddress: "";
        } elseif ($accepted->payoutMethod=='Ethereum') {
            $payoutInfo = (!is_null($accepted->user->ethAddress)) ? $accepted->user->ethAddress: "";
        } else{
            $payoutInfo="";
        }

        if ($request->type=='accept') {
            $data     = [
                         "artistName"   => (!is_null($accepted->user->name)) ? $accepted->user->name : "",
                         "payoutMethod" => (!is_null($accepted->payoutMethod)) ? $accepted->payoutMethod : "",
                         "payout_info"  => $payoutInfo,
                         "date"         => Carbon::now()->format('m.d.Y H:i:s'),
                         "amount"       => $accepted->currency.$accepted->amount
                       ];
        } else if($request->type=='view') {
            //$accepted = Payout::with('user')->where('id', '=', $payout_id)->first();
            $data     = [
                         "payout_id"    => (!is_null($accepted->id)) ? $accepted->id : "",
                         "artistName"   => (!is_null($accepted->user->name)) ? $accepted->user->name : "",
                         "payoutMethod" => (!is_null($accepted->payoutMethod)) ? $accepted->payoutMethod : "",
                         "payout_info"  =>  $payoutInfo,
                         "date"         =>  Carbon::now()->format('m.d.Y H:i:s'),
                         "transactionId"         =>  (!is_null($accepted->transactionId)) ? $accepted->transactionId : "",
                         "amount"       => $accepted->currency.$accepted->amount
                       ];
        }
        

        return response()->json($data);
    }


}
