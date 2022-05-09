<div class="col-md-10 m-auto">
    <div class="from-group">
        <div class="form-group row">
            <label for="previous_password" class="col-sm-5 col-form-label">Subscription Status</label>
            <div class="col-sm-7 pt-4">
                @if(auth()->user()->stripe_subscription_id && auth()->user()->stripe_subscription_status == 1)
                    Active (
                    <a href="{{ route('subscription.cancel') }}">
                        Cancel Subscription
                    </a>
                    )
                @else
                    @if(auth()->user()->stripe_subscription_id)
                        Subscription Cancelled
                    @else
                        No Subscription Yet !
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
