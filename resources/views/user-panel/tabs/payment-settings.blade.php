<div class="col-md-5 m-auto paysettings py-5 px-4"  style="border-radius: 25px;">

          
                @if(auth()->user()->stripe_subscription_id && auth()->user()->stripe_subscription_status == 1)                    
                    <div>
                        @php
                        $plan=auth()->user()->subscriptionPlan;
                        @endphp                        
                        <div class="col-12 col-md-12 m-auto">
                            <x-home.payment-settings-card
                                :plan="$plan"
                            />
                        </div>
                    </div>
                @else
                    <p class="text-white">
                    @if(auth()->user()->stripe_subscription_id)
                        Subscription Cancelled
                    @else
                        No Subscription Yet !
                    @endif
                    </p>
                @endif

</div>
