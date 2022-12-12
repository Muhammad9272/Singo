<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Genre;
use App\Models\User;
use App\Models\Song;
use App\Models\FeaturedArtist;
use App\Models\UserRequest;
use App\Models\Store;
use App\Models\User_Store;
use App\Models\UserSetting;

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

class SubscriptionController extends Controller
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
        $plans = Plan::orderBy('amount', 'ASC')->get();
        return view('subscription.index', ['plans' => $plans]);
    }

    public function add_plan(Request $request){
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'button' => 'required',
        ]);

        $plan = new Plan;
        $plan->title = $request->title;

        $plan->amount = $request->price;
        $plan->total_amount = $request->total_price;
        $plan->discount_percent = $request->discount_percent;
        $plan->discount_amount = $request->discount_amount;

        $plan->content_1 = $request->content_1;
        $plan->content_2 = $request->content_2;
        $plan->content_3 = $request->content_3;
        $plan->content_4 = $request->content_4;
        $plan->content_5 = $request->content_5;
        $plan->button = $request->button;
        $plan->show_button = $request->show_button;
        $plan->status = $request->status;
		$plan->stripe_plan_id = $request->stripe_plan_id;
        $plan->save();

        Session::flash('success', "Plan $request->title added successfully");
        return redirect()->route('subscription');
    }

    public function status_change($id){
        $plan = Plan::findOrFail($id);

        if($plan->status == 1)
        {
            $plan->status = 0;
        }
        else
        {
            $plan->status = 1;
        }
        $plan->save();
        return $plan->id;
    }

    public function get_plan($id){
        $plan = Plan::findOrFail($id);
        return $plan;

    }
    public function edit_plan_store(Request $request)
    {

        $request->validate([
            'edit_title' => 'required',
            'edit_price' => 'required',
            'edit_button' => 'required',
        ]);

        $plan = Plan::findOrFail($request->id);
        $plan->title = $request->edit_title;
        $plan->amount = $request->edit_price;
        $plan->total_amount = $request->edit_total_price;
        $plan->discount_percent = $request->edit_discount_percent;
        $plan->discount_amount = $request->edit_discount_amount;

        $plan->content_1 = $request->edit_content_1;
        $plan->content_2 = $request->edit_content_2;
        $plan->content_3 = $request->edit_content_3;
        $plan->content_4 = $request->edit_content_4;
        $plan->content_5 = $request->edit_content_5;
        $plan->button = $request->edit_button;
        $plan->show_button = $request->edit_show_button;
        $plan->status = $request->edit_status;
		$plan->stripe_plan_id = $request->edit_stripe_plan_id;	
        $plan->save();

        Session::flash('success', "Plan $request->title updated successfully");
        return redirect()->route('subscription');
    }
    public function delete_plan($id)
    {
        $users = User::where('plan', $id)->count();
        if ($users > 0)
        {
            Session::flash('error', "$users users using this plan you can't delete this plan.");
            return redirect()->route('subscription');
        }
        else
        {
            $plan = Plan::findOrFail($id);
            $plan->delete();
        }

        Session::flash('error', "Plan $plan->title deleted unsuccessfull");
        return redirect()->route('subscription');

    }


}
