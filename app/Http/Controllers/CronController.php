<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Song;
use App\Models\UserSetting;
use App\Models\Plan;
use App\Models\WelcomeAlert;
use App\Models\Album;
use App\Services\Publishers\FugaPublisher;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\User_Store;
use App\Mail\OtpMail;
use App\Models\Subscription;

use GuzzleHttp\Cookie\FileCookieJar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use SiteEditor\Request as SiteEditorRequest;
use App\Services\CodeGeneratorService;
use DB;

class CronController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();

        $albums = Album::with('songs')
            ->select([
                'albums.id', 'albums.title', 'albums.created_at',
                'albums.release', 'albums.genre_id', 'albums.status',
                'users.artistName', 'users.name', 'users.plan',
                'users.isPremium', 'albums.user_id', 'albums.cover',
                'albums.fuga_product_id', 'albums.fuga_cover_image_id',
            ])
            ->join('users', 'albums.user_id', '=', 'users.id')
            ->where('status', Album::STATUS_PENDING)
            ->where('albums.id', 675) // Test Album
            ->orderBy('albums.created_at')
            ->orderBy('users.isPremium')
            ->limit(1)
            ->get();

        foreach ($albums as $album) {
            $service = new FugaPublisher($album);
            $service->publish();
        }

        dd(DB::getQueryLog());
    }

    public function stripeWebhook(Request $request)
    {
        $data = $request->all();

        Log::info('StripeWebhook Event: ' . $data['type']);

        $obj = $data['data'];


        switch ($data['type']){
            case 'customer.subscription.deleted':
                $this->onSubscriptionDelete($obj);
                break;

            case 'customer.subscription.updated':
                $this->onSubscriptionUpdate($obj);
                break;
        }
    }

    public function onSubscriptionDelete($obj)
    {
        $subscription_id = $obj['object']['id'];

        Subscription::where('stripe_subscription_id', $subscription_id)
            ->update([
            'stripe_plan_start' => $obj['object']['current_period_start'],
            'stripe_plan_end' => $obj['object']['current_period_end']
        ]);

        User::where('stripe_subscription_id', $subscription_id)
            ->update([
            'stripe_subscription_status' => Subscription::SUBSCRIPTION_STATUS_CANCELED,
            'stripe_start_date' => date('Y-m-d h:i:s', $obj['object']['current_period_start']),
            'stripe_end_date' => date('Y-m-d h:i:s', $obj['object']['current_period_end'])
        ]);
    }

    public function onSubscriptionUpdate(array $obj)
    {
        $subscription_id = $obj['object']['id'];

        Subscription::where('stripe_subscription_id', $subscription_id)->update([
            'stripe_plan_start' => $obj['object']['current_period_start'],
            'stripe_plan_end' => $obj['object']['current_period_end']
        ]);

        User::where('stripe_subscription_id', $subscription_id)->update([
            'stripe_start_date' => date('Y-m-d h:i:s', $obj['object']['current_period_start']),
            'stripe_end_date' => date('Y-m-d h:i:s', $obj['object']['current_period_end'])
        ]);
    }
}
