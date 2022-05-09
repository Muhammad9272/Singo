<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Plan;
use App\Models\Genre;
use App\Models\User;
use App\Models\Song;
use App\Models\FeaturedArtist;
use App\Models\UserRequest;
use App\Models\Store;
use App\Models\CouponUser;
use App\Models\Coupon;

use App\Mail\AlbumSubmitMail;
use App\Mail\AlbumStatus;

use App\Notifications\AlbumStatusChanged;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{

    public function __construct() {
        return $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $plans = Plan::where('status', 1)->latest()->get();
        $coupons = Coupon::latest()->get();
        return view('coupon.index', ['plans' => $plans, 'coupons' => $coupons]);
    }

    public function plan_price($id){
        $plan = Plan::findOrFail($id);
        return $plan->total_amount;
    }

    public function coupon_create(Request $request){
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'coupon_code' => 'required|string|max:50',
            'plan_price' => 'required|numeric|min:1',
            'discount_percent' => 'nullable|numeric|min:1',
            'discount_amount' => 'nullable|numeric|min:1',
            'discounted_price' => 'required|numeric|min:1',
            'status' => 'required'
        ]);

        $coupon = new Coupon;
        $coupon->code = $request->coupon_code;
        $coupon->plan_id = $request->plan_id;
        $coupon->plan_price = $request->plan_price;
        $coupon->discount_amount = $request->discount_amount;
        $coupon->discount_percent = $request->discount_percent;
        $coupon->discounted_price = $request->discounted_price;
        $coupon->start_date = Carbon::now();
        $coupon->status = $request->status;
        $coupon->created_by = auth()->user()->id;
		$coupon->stripe_coupon_id = $request->stripe_coupon_id;
        $coupon->save();


        Session::flash('success', "Coupon code: $request->coupon_code added successfully");
        return redirect()->route('coupon');
    }

    public function coupon_end($id){

        $coupon = Coupon::findOrFail($id);
        $coupon->status = 0;
        $coupon->end_date = Carbon::now();
        $coupon->updated_at = Carbon::now();
        $coupon->updated_by = auth()->user()->id;
        $coupon->save();

        Session::flash('success', "Coupon code: $coupon->code ended successfully");
        return redirect()->route('coupon');
    }

    public function coupon_check(Request $request){
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'coupon_code' => 'required|string|max:50'
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        $coupon = Coupon::where('code', $request->coupon_code)->where('plan_id', $request->plan_id)->first();
        if($coupon != null){

            if($coupon->plan_id == $request->plan_id){

                if($coupon->status == 1){

                    $coupone_user = new CouponUser;
                    $coupone_user->coupon_id = $coupon->id;
                    $coupone_user->plan_id = $plan->id;
                    $coupone_user->user_id = auth()->user()->id;
                    $coupone_user->status = 2;
                    $coupone_user->created_by = auth()->user()->id;
                    $coupone_user->save();

                    return redirect()->route('purchases', ['id'=> $plan->id, 'coupon'=> $coupone_user->id]);

                }else{
                    Session::flash('error', "Coupon check failed: This coupon is  not activated anymore");
                }

            }else{
                Session::flash('error', "Coupon check failed: This coupon is for $plan->title plan");
            }

        }else{
            Session::flash('error', "Coupon check failed: Coupon does not exists");
        }
        return redirect()->route('purchases', ['id'=>$plan->id, 'coupon'=>0]);

    }
}
