<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\Plan;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Subscription;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PremiumPurchase_Success;
use App\Mail\PremiumPurchase_Cancel;
use Illuminate\Http\Request;
use Stripe\Customer;
use Stripe\Exception\CardException;
use Stripe\Stripe;

class PaymentsController extends Controller
{

    public function __construct()
    {
        return $this->middleware('auth');
    }

    //Dynamic plan
    public function purchases($id, $coupon = null)
    {
        // check if stripe customer created or not.
        $check_user = User::findOrFail(auth()->user()->id);

        if ($check_user->stripe_customer_id == null) {
            Stripe::setApiKey(config('app.stripe_sk'));
            $checkout = Customer::create([
                'email'       => auth()->user()->email,
                'name'        => auth()->user()->name,
                'description' => 'Singo test customer for test',
                'metadata'    => [
                    'user_id' => auth()->user()->id,
                ],
            ]);

            $check_user->stripe_customer_id = $checkout->id;
            $check_user->save();
        }


        $plans = Plan::findOrFail($id);
        $amount = $plans->total_amount;
        $plan = $plans->title;

        if ($plans->status != 1) {
            return redirect()->back();
        }

        if ($coupon > 0) {
            $coupon_user = CouponUser::findOrFail($coupon);

            if ($coupon_user->user_id != auth()->user()->id) {
                return redirect()->route('home');
            }

            $coupon = Coupon::findOrFail($coupon_user->coupon_id);
            if ($coupon->discount_amount > 0) {
                $discount = $coupon->discount_amount."€";
            } elseif ($coupon->discount_percent > 0) {
                $discount = $coupon->discount_percent."%";
            }

            $status = "Coupon Successfully Activated. You got $discount discount";
        } else {
            $coupon = null;
            $coupon_user = null;
            $status = null;
        }

        return view('payments.purchase', [
            'amount' => $amount,
            'plan' => $plan,
            'plans' => $plans,
            'coupon_user' => $coupon_user,
            'coupon' => $coupon,
            'status' => $status
        ]);

    }

    public function initiateStripePopup(Request $request)
    {
        $plans = Plan::findOrFail($request->plan);

        $plan = $plans->title;
        $amount = $plans->total_amount;
        $plan_id = $plans->id;

        $uid = auth()->user()->id;
        $user_info = User::findOrFail($uid);

        $stripe_coupon_id = '';
        if ($request->discount_user_id > 0) {
            $coupon_user = CouponUser::findOrFail($request->discount_user_id);
            $coupon_user_id = $coupon_user->id;
            $coupon = Coupon::findOrFail($coupon_user->coupon_id);
            $amount = $coupon->discounted_price;
            $stripe_coupon_id = $coupon->stripe_coupon_id;
        } else {
            $coupon_user_id = 0;
        }

        $plan_description = $plans->title.' Subscription';
        $city = $user_info->city ? $user_info->city : 'Test City';
        $country = $user_info->state ? $user_info->state : 'Test Country';
        $address_1 = $user_info->address_1 ? $user_info->address_1 : 'Line 1';
        $address_2 = $user_info->address_2 ? $user_info->address_2 : 'Line 2';
        $postal_code = $user_info->zip ? $user_info->zip : '43434';
        $state = $user_info->state ? $user_info->state : 'Test State';

        try {
            $record = DB::table('custom_payment_info')
                ->take(1)
                ->first();

            Stripe::setApiKey($record->stripe_secret_key);

            $customer = Customer::create(array(
                "email"       => $_POST['stripeEmail'],
                'name'        => $user_info->name,
                'description' => $plan_description,
                "address"     => [
                    "city"        => $city, "country" => $country, "line1" => $address_1, "line2" => $address_2,
                    "postal_code" => $postal_code, "state" => $state
                ],
                "source"      => $_POST['stripeToken'], // The token submitted from Checkout
            ));


            if ($stripe_coupon_id) {
                $subscription_data = \Stripe\Subscription::create(array(
                    "customer" => $customer->id,
                    "coupon"   => $stripe_coupon_id,
                    "items"    => array(
                        array(
                            "plan" => $_POST['stripe_plan_id'],
                        ),
                    ),
                ));
            } else {
                $subscription_data = \Stripe\Subscription::create(array(
                    "customer" => $customer->id,
                    "items"    => array(
                        array(
                            "plan" => $_POST['stripe_plan_id'],
                        ),
                    ),
                ));
            }

            if ($subscription_data->status != 'active') {
                $subscription_data->delete();

                return redirect()->back()->with('danger', 'Failed to create a subscription for you. Make sure you have sufficient balance in your card.');
            }

            $success = "Thanks! You've subscribed to the ".$plan." plan.";

            if ($request->discount_user_id > 0) {
                $user_discount = $request->discount_user_id;
                $coupon_user = CouponUser::findOrFail($request->discount_user_id);
                $coupon_user_id = $coupon_user->id;
                $coupon = Coupon::findOrFail($coupon_user->coupon_id);
                $amount = $coupon->discounted_price;
            } else {
                $user_discount = null;
            }

            $payment = new Payment;
            $payment->method = "Stripe";
            $payment->plan = $plans->title;
            $payment->user_discount = $user_discount;
            $payment->amount = $amount;
            $payment->user_id = auth()->user()->id;
            $payment->save();

            $user_info->isPremium = "1";
            $user_info->plan = $plans->id;
            $user_info->stripe_customer_id = $customer->id;
            $user_info->stripe_subscription_status = 1;
            $user_info->stripe_subscription_id = $subscription_data->id;
            $user_info->stripe_start_date = date('Y-m-d h:i:s');
            $user_info->stripe_end_date = date('Y-m-d h:i:s', strtotime('+1 years'));
            $user_info->save();

            $subscription = new Subscription;
            $subscription->user_id = auth()->user()->id;
            $subscription->plan_id = $plans->id;
            $subscription->stripe_customer_id = $customer->id;
            $subscription->stripe_subscription_id = $subscription_data->id;
            $subscription->stripe_plan_start = time();
            $subscription->stripe_plan_end = strtotime('+1 years');
            $subscription->save();

            $uid = auth()->user()->id;
            $user_info = User::findOrFail($uid);

            Mail::to($user_info->email)->queue(new PremiumPurchase_Success($user_info));

            return view('payments.success');

        } catch (CardException $e) {
            $error = "Your card's security code is incorrect.";
        } catch (Exception $e) {
            $error = "Sorry, we weren't able to authorize your card. You have not been charged.";
        }

        return view('payments.cancel');
    }

    public function payPalSuccess($id, $coupon_user = 0)
    {
        $i = 0;
        $uid = auth()->user()->id;
        $plans = Plan::findOrFail($id);
        $amount = $plans->total_amount;
        $check = Payment::where('user_id', $uid)->where('plan', $plans->title)->get();
        $user_info = User::findOrFail($uid);
        foreach ($check as $ck) {
            $i++;
        }

        if ($coupon_user > 0) {
            $user_discount = $coupon_user;
            $coupon_user = CouponUser::findOrFail($coupon_user);
            $coupon_user_id = $coupon_user->id;
            $coupon = Coupon::findOrFail($coupon_user->coupon_id);
            $amount = $coupon->discounted_price;
        } else {
            $user_discount = null;
        }

        if ($i == 0) {
            $payment = new Payment;
            $payment->method = "Paypal";
            $payment->plan = $plans->title;
            $payment->user_discount = $user_discount;
            $payment->amount = $amount;
            $payment->user_id = auth()->user()->id;
            $payment->save();

            $user_info->isPremium = "1";
            $user_info->plan = $plans->id;
            $user_info->save();
        } else {
            return redirect()->route('home');
        }


        Mail::to($user_info->email)->send(new PremiumPurchase_Success($user_info));
        return view('payments.success');
    }

    public function initiateStripe(Request $request)
    {
        $plans = Plan::findOrFail($request->id);
        $plan = $plans->title;
        $amount = $plans->total_amount;
        $plan_id = $plans->id;

        if ($request->discount_user_id > 0) {
            $coupon_user = CouponUser::findOrFail($request->discount_user_id);
            $coupon_user_id = $coupon_user->id;
            $coupon = Coupon::findOrFail($coupon_user->coupon_id);
            $amount = $coupon->discounted_price;
        } else {
            $coupon_user_id = 0;
        }

        $amount = sprintf('%0.2f', $amount);
        $parts = explode(".", $amount);
        if ($parts[1]) {
            $set = $parts[0].$parts[1];
        } else {
            $set = $parts[0]."00";
        }


        Stripe::setApiKey(config('app.stripe_sk'));

        $checkout = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                'price'    => 'prod_KbduPpEr8tDC21',
                'quantity' => 1,
            ],
            'mode'                 => 'payment',
            'success_url'          => config('app.url').'/payments/success',
            'cancel_url'           => config('app.url').'/payments/cancel',
            'metadata'             => [
                'user_id'        => auth()->user()->id,
                'amount'         => $set,
                'plan'           => $plan,
                'plan_id'        => $plan_id,
                'coupon_user_id' => $coupon_user_id,
            ]

        ]);

        return response()->json(['id' => $checkout->id]);
    }


    public function initatiateCrypto(Request $request)
    {
        $plans = Plan::findOrFail($request->id);
        $plan = $plans->title;
        $amount = $plans->total_amount;
        $plan_id = $plans->id;

        if ($request->discount_user_id > 0) {
            $coupon_user = CouponUser::findOrFail($request->discount_user_id);
            $coupon_user_id = $coupon_user->id;
            $coupon = Coupon::findOrFail($coupon_user->coupon_id);
            $amount = $coupon->discounted_price;
        } else {
            $coupon_user_id = 0;
        }

        $ch = curl_init('https://api.commerce.coinbase.com/charges');
        $payload = json_encode(array(
            'name'         => 'Buy '.$plan,
            'description'  => 'Buy our '.$plan.' package',
            'local_price'  => array(
                'amount'   => $amount,
                'currency' => 'EUR'
            ),
            'pricing_type' => 'fixed_price',
            'metadata'     => array(
                'user_id'        => auth()->user()->id,
                'amount'         => $amount,
                'plan'           => $plan,
                'plan_id'        => $plan_id,
                'coupon_user_id' => $coupon_user_id,
            ),
            'redirect_url' => config('app.url').'/payments/success',
            'cancel_url'   => config('app.url').'/payments/cancel'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json', 'X-CC-Version: 2018-03-2',
            'X-CC-Api-Key: '.config('app.coinbase_commerce_api')
        ));
        # Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Send request.
        $result = curl_exec($ch);
        $json = json_decode($result);
        curl_close($ch);
        return redirect($json->data->hosted_url);
    }


    public function success()
    {

        //Mail to client
        $i = 0;
        $uid = auth()->user()->id;
        $check = Payment::where('user_id', $uid)->get();
        $user_info = User::findOrFail($uid);


        Mail::to($user_info->email)->send(new PremiumPurchase_Success($user_info));
        return view('payments.success');
    }

    public function cancel()
    {
        //Mail to client
        $uid = auth()->user()->id;
        $user_info = User::findOrFail($uid);
        Mail::to($user_info->email)->send(new PremiumPurchase_Cancel($user_info));

        return view('payments.cancel');
    }

    public function payments(Request $request)
    {
        $methods = [
            1 => 'Stripe',
            2 => 'Paypal',
            3 => 'Crypto',
        ];
        $payments = Payment::query()
            ->when(request('searchQuery'), function ($query) {
                $query->whereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'LIKE', '%' . request('searchQuery') . '%');
                });
            })
            ->when(request('method'), function ($query) use ($methods) {
                $query->where('method', $methods[request('method')]);
            })
            ->when(request('date'), function ($query) use ($methods) {
                $query->whereDate('created_at', request('date'));
            })
            ->with('user')
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('admin.payments', ['payments' => $payments]);
    }
}
