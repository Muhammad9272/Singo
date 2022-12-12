<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Mail\UserBanMail;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Store;
use App\Models\Report;
use App\Models\UserRequest;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\Tempfile;
use App\Models\WelcomeAlert;
use App\Models\Ticket;
use App\Models\CustomPaymentInfo;

use App\Mail\Update_Balance;
use App\Mail\UserRequestStatusMail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->searchQuery, function ($query) {
                $query->where(function ($userQuery) {
                    $userQuery->where('name', 'LIKE', '%'.request('searchQuery').'%')
                        ->orWhere('email', 'LIKE', '%'.request('searchQuery').'%')
                        ->orWhere('artistName', 'LIKE', '%'.request('searchQuery').'%');
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', ['users' => $users]);
    }

    public function show(User $user)
    {
        $reports = Report::with('store')->where('user_id', $user->id)->latest()->get();
        $plans = Plan::where('status', 1)->orderBy('amount', 'ASC')->get();
        $tickets = Ticket::where('user_id', $user->id)->latest()->get();
        return view('admin.user', ['user' => $user, 'reports' => $reports, 'plans' => $plans, 'tickets' => $tickets]);
        //return $reports;
    }

    public function cancelStripeSubscription()
    {
        $record = \DB::table('custom_payment_info')->take(1)->first();
        \Stripe\Stripe::setApiKey($record->stripe_secret_key);

        $uid = auth()->user()->id;
        $user_info = User::findOrFail($uid);

        if (in_array($user_info->stripe_subscription_status, [Subscription::SUBSCRIPTION_STATUS_NONE, Subscription::SUBSCRIPTION_STATUS_CANCELED])) {
            return redirect()->back()->with('message', 'Already Cancelled');
        }

        $sub = \Stripe\Subscription::retrieve(auth()->user()->stripe_subscription_id);

        if ($sub->items->data) {
            try {
                $resp = $sub->update(auth()->user()->stripe_subscription_id, [
                    'cancel_at_period_end' => true
                ]);

                if ($resp) {
                    $uid = auth()->user()->id;
                    $user_info2 = User::findOrFail($uid);
                    $user_info2->stripe_subscription_status = Subscription::SUBSCRIPTION_STATUS_CANCELED;
                    $user_info2->plan = 6;
                    $user_info2->save();

                    return redirect()->back()->with('success', 'Subscription cancelled successfully!');
                }

            } catch (\Stripe\Error\Card $e) {

            }
        }

        return redirect()->back()->with('error', 'There is something wrong!');
    }

    public function updateBalance(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'balance' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            $this->message('error', 'Balance could not be updated');
            return redirect()->route('admin.user', $user->id)->withErrors($validator);
        }

        $user->balance = $request->balance;
        $user->save();
        //Update Balance Mail to Client

        Mail::to($user->email)->send(new Update_Balance($user));


        return redirect()->route('admin.user', $user->id)->with('balance', 'The balance of this user was updated');
    }

    public function toggleAdmin(Request $request, User $user)
    {
        // $isAdmin = !($user->isAdmin());
        // $user->isAdmin = $isAdmin;
        // $user->save();

        $user->type = $request->type;
        $user->save();
        $this->message('success', 'User Privilege Updated Successfully');
        return redirect()->route('admin.user', $user->id);

    }

    public function delete(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users', $user->id);
    }

    public function ban(User $user)
    {
        $user->ban();

        Mail::to($user)->queue(new UserBanMail());

        return redirect()->route('admin.users', $user->id);
    }

    public function unban(User $user)
    {
        $user->unban();

        return redirect()->route('admin.users', $user->id);
    }

    public function add_report($user_id)
    {
        $user_info = User::findOrFail($user_id);
        $store = Store::latest()->get();

        return view('admin.report.create', [
            'user_info' => $user_info,
            'store'     => $store
        ]);
    }

    public function report_store(Request $request)
    {
        $request->validate([
            'date'    => 'string|max:255|required',
            'streams' => 'required|numeric|min:1',
            'money'   => 'required|numeric|min:1',
            'store'   => 'exists:stores,id|required',
            'file'    => 'required',
        ]);

        $save = new Report;
        $save->date = $request->date;
        $save->streams = $request->streams;
        $save->money = $request->money;
        $save->store_id = $request->store;
        $save->user_id = $request->id;

        $tempFile = Tempfile::findOrFail($request->file);

        if ($tempFile) {
            $from_path = 'file/tmp/'.$tempFile->folder.'/'.$tempFile->file;
            $to_path = 'uploads/csvs/'.time().'_'.$tempFile->file;
            Storage::move($from_path, $to_path);
            $save->file = 'uploads/csvs/'.time().'_'.$tempFile->file;

            rmdir(storage_path('app/file/tmp/'.$tempFile->folder));
        }

        $save->created_by = auth()->user()->id;
        $save->save();

        //balance update
        $user = User::find($request->id);
        $previous_balance = $user->balance;
        $new_balance = $previous_balance + $request->money;
        $user->balance = $new_balance;
        $user->save();

        //mail to user balance update
        Mail::to($user->email)->send(new Update_Balance($user));

        $this->message('success', 'New Report Added Successfully');
        return redirect()->route('admin.user', $request->id);

    }

    public function edit_report($id)
    {
        $report = Report::with('store')->findOrFail($id);
        $store = Store::latest()->get();
        return view('admin.report.edit', ['report' => $report], ['store' => $store]);
        //return $report;
    }

    public function update_report(Request $request)
    {
        $request->validate([
            'date'    => 'string|max:255|required',
            'streams' => 'required|numeric|min:1',
            'money'   => 'required|numeric|min:1',
            'store'   => 'exists:stores,id|required',

        ]);

        $save = Report::find($request->report_id);
        $pre_money = $save->money;
        $save->date = $request->date;
        $save->streams = $request->streams;
        $save->money = $request->money;
        $save->store_id = $request->store;
        $post_money = $request->money;

        if ($request->file) {

            if ($save->file != null) {
                unlink(storage_path('app/'.$save->file));
            }

            $tempFile = Tempfile::findOrFail($request->file);

            if ($tempFile) {
                $from_path = 'file/tmp/'.$tempFile->folder.'/'.$tempFile->file;
                $to_path = 'uploads/csvs/'.time().'_'.$tempFile->file;
                Storage::move($from_path, $to_path);
                $save->file = 'uploads/csvs/'.time().'_'.$tempFile->file;

                rmdir(storage_path('app/file/tmp/'.$tempFile->folder));
            }


        }
        $save->updated_by = auth()->user()->id;
        $save->updated_at = Carbon::now();
        $save->save();

        //balance update
        $user = User::find($save->user_id);
        $previous_balance = $user->balance;
        $new_balance = ($previous_balance - $pre_money) + $post_money;
        $user->balance = $new_balance;
        $user->save();

        //mail to user balance update
        Mail::to($user->email)->send(new Update_Balance($user));

        $this->message('success', 'Report Updated Successfully');
        return redirect()->route('admin.user', $request->id);

    }

    public function view_report($id)
    {
        $report = Report::with('users_4')->with('users_5')->with('store')->findOrFail($id);
        return view('admin.report.view', ['report' => $report]);
        // return $report;
    }

    public function report_download($id)
    {
        $report = Report::findOrFail($id);
        return Storage::download($report->file);
    }

    public function make_primium(Request $request, $id)
    {
        $user = User::find($id);
        $user->isPremium = "1";
        $user->plan = $request->plan;
        $user->save();
        $this->message('success', 'Subscription Updated Successfully');
        return redirect()->route('admin.user', $id);
    }

    public function user_requests()
    {
        $user_request = UserRequest::with('users_6')->with('album')->with('users_7')->latest()->get();
        return view('admin.user-request.index', ['user_request' => $user_request]);
        //return $user_request;
    }

    public function user_requests_accept($id)
    {

        $update = UserRequest::find($id);
        $update->status = "1";
        $update->passed_by = auth()->user()->id;
        $update->updated_at = Carbon::now();
        $update->save();

        //Update Request Mail to Client
        $request = $user_request = UserRequest::with('users_6')->with('album')->with('users_7')->findOrFail($update->id);
        $user = User::findOrFail($update->user_id);
        Mail::to($user->email)->send(new UserRequestStatusMail($user, $request));

        $this->message('success', 'Request Accepted Successfully');
        return redirect()->route('users.requests');
    }

    public function user_requests_decline($id)
    {
        $update = UserRequest::find($id);
        $update->status = "2";
        $update->passed_by = auth()->user()->id;
        $update->updated_at = Carbon::now();
        $update->save();

        //Update Request Mail to Client
        $request = $user_request = UserRequest::with('users_6')->with('album')->with('users_7')->findOrFail($update->id);
        $user = User::findOrFail($update->user_id);
        Mail::to($user->email)->send(new UserRequestStatusMail($user, $request));

        $this->message('success', 'Request Declined Successfully');
        return redirect()->route('users.requests');
    }

    public function user_settings()
    {
        $data = CustomPaymentInfo::first();
        return view('user-panel.user_settings')->with('data', $data);
    }

    public function user_settings_update(Request $request)
    {
        $request->validate([
            'f_name'    => 'nullable|string|max:55',
            'l_name'    => 'nullable|string|max:55',
            'email'     => 'nullable|string|email|max:255|unique:users',
            'address_1' => 'nullable|string|max:250',
            'address_2' => 'nullable|string|max:250',
            'city'      => 'nullable|string|max:50',
            'state'     => 'nullable|string|max:50',
            'zip'       => 'nullable|string|max:50',
            'file'      => 'image|mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:5000',
        ]);

        $users = User::find(auth()->user()->id);
        if (isset($request->f_name) || isset($request->l_name)) {
            $users->name = $request->f_name." ".$request->l_name;
            $users->f_name = $request->f_name;
            $users->l_name = $request->l_name;
        }
        if ($request->email != null) {
            $users->email = $request->email;
        }

        $users->address_1 = $request->address_1;
        $users->address_2 = $request->address_2;
        $users->city = $request->city;
        $users->state = $request->state;
        $users->zip = $request->zip;

        $users->save();
        if ($request->hasFile('file')) {
            if (isset($users->profile_picture)) {
                $previous_photo = $users->profile_picture;
                if (File::exists($previous_photo)) {
                    File::delete($previous_photo);
                }
            }

            $user = User::findOrFail(auth()->user()->id);
            $photo = $request->file;
            $img_name = $user->id.'_.'.$photo->getClientOriginalExtension();
            $photo->move('uploads/images/', $img_name);
            $user->profile_picture = 'uploads/images/'.$img_name;
            $user->save();
            // dd($user->profile_picture);
        }

        Session::flash('success', "Profile Updated successfully");
        return redirect()->back()->with('success', 'your message,here');
    }

    public function update_payment_settings(Request $request)
    {
        //echo "<pre>"; print_r($request->all()); die;
        CustomPaymentInfo::truncate();

        $obj = new CustomPaymentInfo;
        $obj->subscrption_price = $request->subscrption_price;
        $obj->stripe_secret_key = $request->stripe_secret_key;
        $obj->stripe_publish_key = $request->stripe_publish_key;
        $obj->paypal_client_key = $request->paypal_client_key;
        $obj->paypal_secret_key = $request->paypal_secret_key;
        $obj->save();

        Session::flash('success', "Payment details successfully");
        return redirect()->back();
    }

    public function update_password(Request $request)
    {
        $users = User::find(auth()->user()->id);
        if ($request->previous_password != null) {
            if ($request->new_password == null) {
                Session::flash('error', "Please input new password");
                return redirect()->back();
            } elseif ($request->confirm_new_password == null) {
                Session::flash('error', "Please input confirm new password");
                return redirect()->back();
            } else {
                if ($request->new_password != $request->confirm_new_password) {
                    Session::flash('error', "New password and Confirm new password didn't match");
                    return redirect()->back();
                }
            }
            $hashedPassword = auth()->user()->password;
            if (Hash::check($request->previous_password, $hashedPassword)) {
                if (!Hash::check($request->confirm_new_password, $hashedPassword)) {
                    $users->password = Hash::make($request->confirm_new_password);
                    // $users->email_verified_at = null;
                } else {
                    Session::flash('error', "new password can not be the old password!");
                    return redirect()->back();
                }
            } else {
                Session::flash('error', "old password doesn't matched");
                return redirect()->back();
            }

        }


        $users->save();


        Session::flash('success', "Password Updated successfully");
        return redirect()->back();
    }

    public function notification(Request $request)
    {

        $notifications = Notification::where('id', $request->id)->get();
        foreach ($notifications as $notification) {
            $notification->read_at = Carbon::now();
            $notification->save();
        }
        $data = json_decode($notification->data, true);
        $route = $data['route'];
        if (isset($data['route_id'])) {
            $route_id = $data['route_id'];
            return redirect()->route($route, $route_id);
        } else {
            return redirect()->route($route);
        }


        //return $route ;

    }

    public function welcome_alert()
    {
        $welcome = WelcomeAlert::latest()->get();
        return view('welcome.index', ['welcome' => $welcome]);
    }

    public function welcome_edit($id)
    {
        $welcome = WelcomeAlert::findOrFail($id);
        return $welcome;
    }

    public function welcome_create(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:55',
            'content' => 'required',
        ]);

        $welcome = new WelcomeAlert;
        $welcome->name = $request->name;
        $welcome->content = request('content');
        $welcome->status = 1;
        $welcome->created_by = auth()->user()->id;
        $welcome->save();

        $mark = WelcomeAlert::latest()->get();
        foreach ($mark as $mk) {
            if ($mk->id != $welcome->id) {
                $mk->status = 0;
                $mk->save();
            }
        }

        Session::flash('success', " $welcome->name alert created successfully");
        return redirect()->back();

    }

    public function welcome_status($id)
    {

        $welcome = WelcomeAlert::findOrFail($id);
        if ($welcome->status == 0) {
            $welcome->status = 1;
            $welcome->save();

            $mark = WelcomeAlert::latest()->get();
            foreach ($mark as $mk) {
                if ($mk->id != $welcome->id) {
                    $mk->status = 0;
                    $mk->save();
                }
            }
        } else {
            if ($welcome->status == 1) {
                $welcome->status = 0;
                $welcome->save();
            }
        }


        Session::flash('success', " $welcome->name alert status changed successfully");
        return redirect()->back();
    }

    public function welcome_edit_save(Request $request)
    {
        $request->validate([
            'edit_name'    => 'required|string|max:55',
            'edit_content' => 'required',
            'id'           => 'required',
        ]);

        $welcome = WelcomeAlert::findOrFail($request->id);
        $welcome->name = $request->edit_name;
        $welcome->content = $request->edit_content;
        $welcome->updated_by = auth()->user()->id;
        $welcome->updated_at = Carbon::now();
        $welcome->save();

        Session::flash('success', " $welcome->name alert updated successfully");
        return redirect()->back();
    }

    public function welcome_dnd($id)
    {
        $user = User::findOrFail(auth()->user()->id);
        $user->welcome_alert = $id;
        $user->save();
        return redirect()->back();
    }

    public function profilePicture(User $user)
    {
        $path = public_path($user->profile_picture);

        if (!file_exists($path)) {
            return Image::canvas(150, 150, '#ddd')
                ->text('No image.', 75, 75, function ($font) {
                    $font->align('center');
                    $font->valign('top');
                    $font->angle(45);
                })
                ->response('webp');
        }

        $img = Image::make($path)->resize(150, 150);

        return $img->response('png');
    }
}
