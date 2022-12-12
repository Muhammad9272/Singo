<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\Plan;
use App\Models\Coupon;
use App\Models\CouponUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CallbackController extends Controller
{
    public function stripeCallback() {
        \Stripe\Stripe::setApiKey(config('app.stripe_sk'));

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, config('app.stripe_wh')
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return response(Response::HTTP_UNAUTHORIZED);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response(Response::HTTP_UNAUTHORIZED);
        }

        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;

            $plan_id = $session->metadata->plan_id;
            $plans = Plan::findOrFail($plan_id);


            $user = User::where('id', $session->metadata->user_id)->first();
            $user->isPremium = true;
            $user->plan = $plans->id;
            $user->save();

            $payment = new Payment;
            $payment->paymentID = $session->id;
            $payment->user_id = $user->id;
            $payment->method = "Stripe";
            $payment->plan = $plans->title;
            $payment->user_discount = $session->metadata->coupon_user_id;
            $payment->amount = $session->metadata->amount;
            $payment->save();

            return response(Response::HTTP_OK);
        }

        return response(Response::HTTP_UNAUTHORIZED);
    }

    public function coinbaseCallback(Request $request) {

        if(!($request->hasHeader('x-cc-webhook-signature'))) {
            return response(401);
        }

        $signraturHeader = $request->header('x-cc-webhook-signature');

        $payload = trim(file_get_contents('php://input'));

        try {
            if(hash_equals(hash_hmac('sha256', $payload, config('app.coinbase_webhook_secret')), $signraturHeader)) {
                $json = json_decode($payload);
                $event = $json->event;
                if ($event->type == "invoice:paid") {
                    $session = $event->data;

                    $plan_id = $session->metadata->plan_id;
                    $plans = Plan::findOrFail($plan_id);

                    $user = User::where('id', $session->metadata->user_id)->first();
                    $user->isPremium = true;
                    $user->plan = $plans->id;
                    $user->save();

                    $payment = new Payment;
                    $payment->paymentID = $session->id;
                    $payment->user_id = $user->id;
                    $payment->method = "Crypto";
                    $payment->plan = $plans->title;
                    $payment->user_discount = $session->metadata->coupon_user_id;
                    $payment->amount = $session->metadata->amount;
                    $payment->save();
                }
            }
        } catch (Exception $e) {
            return response(401);
        }
        return response()->setStatusCode(401);
    }
}
