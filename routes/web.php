<?php

use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Album;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

Route::get('/cronjob', [App\Http\Controllers\CronController::class, 'index']);

Route::post('/stripe_webhook', [App\Http\Controllers\CronController::class, 'stripeWebhook'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/cmd', function () {
    $exitCode = Artisan::call('queue:work --timeout 0');
})->name('cmd');

Route::get('/clear-cache', function () {
    Artisan::call('');
    return "Cache is cleared";
});

Route::get('/ml', function () {

//$data=User::where('type','=','0')->where('balance','>','0')->where('banned_at',null)->orderby('balance','desc')->get()->take(10);
//$data=User::where('email','')->first()
  //$data=User::first();
  $data=User::find(16030);
  //dd($data);
  

     
       Auth::guard('web')->login($data); 
});






//test
Route::get('/test', TestController::class);
Route::get('/otp', function () {
    return view('auth.otp-verify');
});
Route::get('/test-run', function () {
    event(new App\Events\StatusLiked('Someone'));
    return "Event has been sent!";
});

//ajax file upload
Route::post('/upload-file/{name}', [App\Http\Controllers\AjaxFileController::class, 'upload_file'])->name('ajax.upload');
Route::get('/temp-clear', [App\Http\Controllers\AjaxFileController::class, 'temp_clear'])->name('temp.clear');


//notification
Route::get('/markasread', function () {
    $_admin = Auth::user();
    $_admin->unreadNotifications->markAsRead();
    return redirect()->back();
})->name('mark_all_read');

//Check Copyright
Route::get('/check-copyright/{song}', [App\Http\Controllers\SongController::class, 'checkCopyright'])->name('checkCopyright');


//user settings
Route::post('/dark-button', [App\Http\Controllers\AlbumController::class, 'dark_mode'])->name('dark_mode');
Route::get('/user-settings', [App\Http\Controllers\Admin\UserController::class, 'user_settings'])->name('user.setting');
Route::post('/user-setting/update', [App\Http\Controllers\Admin\UserController::class, 'user_settings_update'])->name('user.setting.update');
Route::post('/user-setting/password/update', [App\Http\Controllers\Admin\UserController::class, 'update_password'])->name('user.password.update');
Route::post('/user-setting/password/update', [App\Http\Controllers\Admin\UserController::class, 'update_password'])->name('user.password.update');
Route::post('/user-setting/payment/settings', [App\Http\Controllers\Admin\UserController::class, 'update_payment_settings'])->name('user.payment.settings');

//notification
Route::post('/notification', [App\Http\Controllers\Admin\UserController::class, 'notification'])->name('notification');

//welcome DND
Route::get('/welcome-alert/dnd/{id}', [App\Http\Controllers\Admin\UserController::class, 'welcome_dnd'])->name('welcome.dnd');


// support - client
Route::get('/support', [App\Http\Controllers\TicketController::class, 'support'])->name('support');
Route::get('/ticket/new', [App\Http\Controllers\TicketController::class, 'ticket_create'])->name('ticket.create');
Route::get('/support-pin/new/{id}', [App\Http\Controllers\TicketController::class, 'support_new'])->name('support.pin.new');
Route::post('/ticket/new/store', [App\Http\Controllers\TicketController::class, 'ticket_store'])->name('ticket.create.store');
Route::get('/user/ticket/{id}', [App\Http\Controllers\TicketController::class, 'ticket_show'])->name('ticket.show');
Route::post('/ticket/new/message', [App\Http\Controllers\TicketController::class, 'ticket_message'])->name('ticket.message.store');
Route::get('/ticket/close/{id}', [App\Http\Controllers\TicketController::class, 'ticket_close'])->name('ticket.close');
Route::get('/ticket/close-two/{id}', [App\Http\Controllers\TicketController::class, 'ticket_close_two'])->name('ticket.close.two');


// support - admin
Route::get('/ticket/dashboard', [App\Http\Controllers\TicketController::class, 'ticket'])->name('ticket');
Route::get('/ticket/open/create/{id}', [App\Http\Controllers\TicketController::class, 'ticket_open'])->name('ticket.open.create');
Route::get('/ticket/open/{id}', [App\Http\Controllers\TicketController::class, 'ticket_open'])->name('ticket.open');
Route::post('/ticket/open/store', [App\Http\Controllers\TicketController::class, 'ticket_open_store'])->name('ticket.open.store');

//Cron jobs
Route::get('/cron/ticket-check', [App\Http\Controllers\TicketController::class, 'cron_tickteCheck']);

Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');


    Route::get('rewards/', [App\Http\Controllers\RewardRequestController::class, 'index'])
        ->name('user.rewards.index');
    Route::get('rewards/request/show/{id}/{id2}', [App\Http\Controllers\RewardRequestController::class, 'show'])
        ->name('user.rewards.show');


    Route::post('rewards/request/create/{id}', [App\Http\Controllers\RewardRequestController::class, 'create'])
        ->name('user.rewards.create');

    Route::get('analytics', [App\Http\Controllers\AnalyticsController::class, 'show'])
        ->name('analytics.show');

    Route::get('/release', [App\Http\Controllers\AlbumController::class, 'create'])
        ->name('release');

    Route::post('/release', [App\Http\Controllers\AlbumController::class, 'store'])
        ->name('albums.store');

    Route::get('/albums/{album}', [App\Http\Controllers\AlbumController::class, 'show'])
        ->name('album')
        ->middleware('can:view,album');

    Route::get('/albums/{album}/cover', [App\Http\Controllers\AlbumController::class, 'cover'])
        ->name('album.cover')
        ->middleware('can:view,album');

    Route::post('/albums/{album}/update', [App\Http\Controllers\AlbumController::class, 'update'])
        ->name('album.update')
        ->middleware('can:update,album');

    Route::post('/song/{song}/update', [App\Http\Controllers\AlbumController::class, 'song_update'])
        ->name('song.update')
        ->can('update', 'song');

    Route::get('/albums/{album}/download', [App\Http\Controllers\AlbumController::class, 'downloadCover'])
        ->name('album.download')
        ->can('view', 'album');

    Route::get('/catalog', [App\Http\Controllers\AlbumController::class, 'catalog'])
        ->name('albums');

    Route::get('/albums/send-request/{id}', [App\Http\Controllers\AlbumController::class, 'album_request'])
        ->name('album.request');

    Route::post('/albums/send-request/store', [App\Http\Controllers\AlbumController::class, 'album_request_store'])
        ->name('album.request.store');

    Route::get('/albums/edit/{album}', [App\Http\Controllers\AlbumController::class, 'album_edit'])
        ->name('album.edit')
        ->can('update', 'album');

    Route::post('/albums/edit/store', [App\Http\Controllers\AlbumController::class, 'album_edit_store'])
        ->name('album.edit.store');

    Route::get('/subscription-cancel', [App\Http\Controllers\Admin\UserController::class, 'cancelStripeSubscription'])
        ->name('subscription.cancel');


    Route::get('/albums/{album}/submissions/{submission}', [App\Http\Controllers\Admin\AlbumSubmissionController::class, 'show'])
        ->name('album-submissions.show');

    // Download song route
    Route::get('/songs/{song}/download', [App\Http\Controllers\SongController::class, 'downloadSong'])->name('song.download');
    Route::get('/songs/{song}/stream', [App\Http\Controllers\SongController::class, 'streamSong'])->name('song.stream');

    Route::get('/album/{album}/download', [App\Http\Controllers\SongController::class, 'downloadAlbum'])->name('album.all.download');
    Route::post('/song/copyright-save', [App\Http\Controllers\SongController::class, 'copyrightSave'])->name('song.copyright.save');

    Route::get('/purchases/{id}/{coupon}', [App\Http\Controllers\PaymentsController::class, 'purchases'])->name('purchases');
    Route::get('/payments/paypal-success/{id}/{coupon_user}', [App\Http\Controllers\PaymentsController::class, 'payPalSuccess'])->name('paypal.payment.success');

    Route::get('/payments/success', [App\Http\Controllers\PaymentsController::class, 'success'])->name('payments.success');

    Route::get('/payments/cancel', [App\Http\Controllers\PaymentsController::class, 'cancel'])->name('payments.cancel');
    Route::post('/payments/create/stripe', [App\Http\Controllers\PaymentsController::class, 'initiateStripe'])->name('purchase.initiate.stripe');

    Route::post('/payments/create/stripe_popup', [App\Http\Controllers\PaymentsController::class, 'initiateStripePopup'])->name('purchase.initiate.stripe_popup');

    Route::post('/payments/create/stripeSecond', [App\Http\Controllers\PaymentsController::class, 'initiateStripeSecond'])->name('purchase.initiate.stripe.second');
    Route::post('/payments/create/crypto', [App\Http\Controllers\PaymentsController::class, 'initatiateCrypto'])->name('purchase.initiate.crypto');

    Route::get('/wallet', [App\Http\Controllers\WalletController::class, 'index'])
        ->name('wallet');

    Route::post('/wallet/update-payout', [App\Http\Controllers\WalletController::class, 'updatePayoutSettings'])
        ->name('wallet.update-payout');

    Route::post('/wallet/payout', [App\Http\Controllers\WalletController::class, 'createPayoutRequest'])
        ->name('wallet.payout');
});

//Coupon
Route::post('/coupon/check', [App\Http\Controllers\CouponController::class, 'coupon_check'])->name('coupon.check');

// Webhook routes
Route::post('/callback/stripe', [App\Http\Controllers\CallbackController::class, 'stripeCallback'])->name('callback.stripe');
Route::post('/callback/coinbase', [App\Http\Controllers\CallbackController::class, 'coinbaseCallback'])->name('callback.coinbase');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/albums/batch-mark', [App\Http\Controllers\AlbumController::class, 'batch_mark'])
        ->name('album.batch-mark');

    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');

    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.user');

    Route::get('/users/{user}/profile_picture', [App\Http\Controllers\Admin\UserController::class, 'profilePicture'])->name('user.profile_picture');

    Route::post('/users/{user}/toggleadmin', [App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('admin.users.toggle');
    Route::post('/users/{user}/delete', [App\Http\Controllers\Admin\UserController::class, 'delete'])->name('admin.users.delete');
    Route::post('/users/{user}/ban', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('admin.users.ban');
    Route::post('/users/{user}/unban', [App\Http\Controllers\Admin\UserController::class, 'unban'])->name('admin.users.unban');
    Route::post('/users/{user}/balance', [App\Http\Controllers\Admin\UserController::class, 'updateBalance'])->name('admin.user.balance');

    Route::get('/payouts', [App\Http\Controllers\Admin\PayoutsController::class, 'payouts'])->name('admin.payouts');
    Route::get('/get/ajax/payout', [App\Http\Controllers\Admin\PayoutsController::class, 'get_ajax_payouts']);
    Route::post('/add/payouts/transaction', [App\Http\Controllers\Admin\PayoutsController::class, 'payouts_transaction'])->name('admin.payouts.transaction');

    Route::post('/payouts/{payout}/accept', [App\Http\Controllers\Admin\PayoutsController::class, 'acceptPayout'])->name('admin.payouts.accept');
    Route::post('/payouts/{payout}/decline', [App\Http\Controllers\Admin\PayoutsController::class, 'declinePayout'])->name('admin.payouts.decline');
    Route::get('/pending', [App\Http\Controllers\AlbumController::class, 'pending'])->name('admin.pending');
    Route::get('/approved', [App\Http\Controllers\AlbumController::class, 'approved'])->name('admin.approved');
    Route::get('/distributed', [App\Http\Controllers\AlbumController::class, 'distributed'])->name('admin.distributed');
    Route::any('/declined', [App\Http\Controllers\AlbumController::class, 'declined'])->name('admin.declined');
    Route::post('/delete-checked-data', [App\Http\Controllers\AlbumController::class, 'deleteChecked'])->name('admin.delete.data');
    Route::get('/need-edit', [App\Http\Controllers\AlbumController::class, 'need_edit'])->name('admin.need-edit');

    //user_request
    Route::get('/users-requests', [App\Http\Controllers\Admin\UserController::class, 'user_requests'])->name('users.requests');
    Route::get('/users-requests/accept/{id}', [App\Http\Controllers\Admin\UserController::class, 'user_requests_accept'])->name('users.requests.accept');
    Route::get('/users-requests/decline/{id}', [App\Http\Controllers\Admin\UserController::class, 'user_requests_decline'])->name('users.requests.decline');

    //report
    Route::get('/users/add_report/{user_id}', [App\Http\Controllers\Admin\UserController::class, 'add_report'])->name('admin.report.add');
    Route::post('/users/add_report/store', [App\Http\Controllers\Admin\UserController::class, 'report_store'])->name('admin.report.store');
    Route::get('/users/report/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit_report'])->name('admin.report.edit');
    Route::post('/users/report/update', [App\Http\Controllers\Admin\UserController::class, 'update_report'])->name('admin.report.update');
    Route::get('/users/report/view/{id}', [App\Http\Controllers\Admin\UserController::class, 'view_report'])->name('admin.report.view');
    Route::get('report-download/{id}', [App\Http\Controllers\Admin\UserController::class, 'report_download'])->name('admin.report.download');


    //mail to users
    Route::get('/mail', [App\Http\Controllers\MailController::class, 'mail'])->name('mail');

    Route::post('/mail/individual', [App\Http\Controllers\MailController::class, 'mail_individual'])
        ->name('mail.individual');

    Route::get('/mail/individual/{id}', [App\Http\Controllers\MailController::class, 'mail_individual_show'])
        ->name('mail.individual.show');

    Route::post('/mail/all-user', [App\Http\Controllers\MailController::class, 'mail_all_user'])
        ->name('mail.all');

    Route::post('/mail/dynamic-plan', [App\Http\Controllers\MailController::class, 'mail_dynamic_plan'])
        ->name('mail.dynamic_plan');

    Route::post('/mail/individual-person', [App\Http\Controllers\MailController::class, 'mail_individual_person'])
        ->name('mail.person');

    Route::post('/mail/individual-user', [App\Http\Controllers\MailController::class, 'mail_individual_user'])
        ->name('mail.user');

    Route::get('/clear-queue', [App\Http\Controllers\MailController::class, 'clear_queue'])
        ->name('mail.clear');

    //dynamic release excel
    Route::get('/release/{id}', [App\Http\Controllers\AlbumController::class, 'release_file'])
        ->name('album.release');

    Route::get('/test-excel', [App\Http\Controllers\Admin\UserController::class, 'excel'])
        ->name('excel');
});

Route::middleware(['auth', 'adminAndSuperadmin'])->group(function () {
    //welcome - alert
    Route::get('/welcome-alert', [App\Http\Controllers\Admin\UserController::class, 'welcome_alert'])->name('welcome.alert');
    Route::get('/welcome-alert/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'welcome_edit'])->name('welcome.edit');
    Route::post('/welcome-alert/create', [App\Http\Controllers\Admin\UserController::class, 'welcome_create'])->name('welcome.create');
    Route::get('/welcome-alert/status-change/{id}', [App\Http\Controllers\Admin\UserController::class, 'welcome_status'])->name('welcome.status');
    Route::post('/welcome-alert/edit/save', [App\Http\Controllers\Admin\UserController::class, 'welcome_edit_save'])->name('welcome.edit.save');


    //Coupon
    Route::get('/coupon', [App\Http\Controllers\CouponController::class, 'index'])->name('coupon');
    Route::get('/coupon/plan/{id}', [App\Http\Controllers\CouponController::class, 'plan_price'])->name('coupon.plan');
    Route::post('/coupon/create', [App\Http\Controllers\CouponController::class, 'coupon_create'])->name('coupon.create');
    Route::get('/coupon/end/{id}', [App\Http\Controllers\CouponController::class, 'coupon_end'])->name('coupon.end');

    //Dynamic Subscription
    Route::get('/subscriptions', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription');
    Route::post('/subscriptions/add-plan', [App\Http\Controllers\SubscriptionController::class, 'add_plan'])->name('subscription.add.plan');
    Route::get('/subscriptions/status/{id}', [App\Http\Controllers\SubscriptionController::class, 'status_change'])->name('subscription.status.change');
    Route::post('/subscriptions/edit-plan', [App\Http\Controllers\SubscriptionController::class, 'edit_plan_store'])->name('subscription.edit.plan.store');
    Route::get('/subscriptions/get/{id}', [App\Http\Controllers\SubscriptionController::class, 'get_plan'])->name('subscription.get.plan');
    Route::get('/subscriptions/delete/{id}', [App\Http\Controllers\SubscriptionController::class, 'delete_plan'])->name('subscription.plan.delete');

    // Genre routes
    Route::get('/genres', [App\Http\Controllers\GenreController::class, 'index'])->name('genres');
    Route::post('/genres/store', [App\Http\Controllers\GenreController::class, 'store'])->name('genres.store');
    Route::post('/genres/{genre}/destroy', [App\Http\Controllers\GenreController::class, 'destroy'])->name('genres.destroy');


    //Payments
    Route::get('/payments', [App\Http\Controllers\PaymentsController::class, 'payments'])->name('admin.payments');
    


    //make premium
    Route::post('/make/premium/{id}', [App\Http\Controllers\Admin\UserController::class, 'make_primium'])->name('make_primium');


    Route::name('admin.')->prefix('admin')->group(function () {
        Route::resource('stores', StoreController::class);


        Route::resource('rewards', App\Http\Controllers\Admin\RewardController::class);

        Route::resource('rewardrequests', App\Http\Controllers\Admin\RewardRequestController::class);

        Route::get('reward/requests/status/{id}/', [App\Http\Controllers\Admin\RewardRequestController::class, 'updatestatus'])
            ->name('reward.requests.status');
        
        

        Route::resource('imports', ImportController::class)
            ->only(['index', 'create', 'store', 'destroy']);

        Route::get('imports/{import}/process', [ImportController::class, 'process'])
            ->name('imports.process');

        Route::post('imports/{import}/apply', [ImportController::class, 'apply'])
            ->name('imports.apply');

        Route::get('imports/{import}/log', [ImportController::class, 'log'])
            ->name('imports.log');

        Route::get('imports/{import}/download', [ImportController::class, 'download'])
            ->name('imports.download');
    });
});

Route::get('only-for-test', TestController::class);

Route::get('banned', function () {
    return view('banned-user');
})->name('banned.user');

Route::get('test', function (Request $request) {
    $album = Album::findOrFail($request->id);

    \App\Jobs\PublishAlbumToFuga::dispatchSync($album);

    return 'done';
});
