@extends('layouts.app')

@section('content')
@if(session('success'))
<p class="alert alert-success text-center mt-1">
    {{ session('success') }}
</p>
@elseif(session('error'))
<p class="alert alert-danger text-center mt-1">
    {{ session('error') }}
</p>
@endif

@if(isset($status))
<p class="alert alert-info text-center mt-1">
    {{ $status }}
</p>
@endif
    <div class="container-fluid ">
        <div class="row justify-content-center align-content-center">
            <div class="col-md-4 col-sm-12">
                <div class="card text-center mt-5">
                    <div class="card-header">Purchase {{ $plans->title ?? '' }}</div>
                    <div class="card-body">
                        <span class="text-bold" style="display:none">
                            Use This: 4242424242424242 as test card number<br>
                            CVC: Any 3 digits <br>
                            DATE: Any future date<br>
                        </span>
                        <p>You are about to buy our {{ $plans->title ?? '' }} rank for @if($coupon == null) <strong>{{ $plans->total_amount ?? '' }} €</strong> @else <s class="mr-2">{{ $plans->total_amount ?? '' }} €</s>   <span id="discounted_price" value="{{ $coupon->discounted_price }}">{{ $coupon->discounted_price }} € </span>@endif. After purchasing you will receive your rank automatically. You can pay with credit card or with crypto. Please choose your payment method below</p>
                        <button id="initiateStripe" type="submit" class="btn btn-sm btn-primary mb-2 w-100" style = "display:none;background-color:#006AB1;    padding: 13px 0px"><i class="fas fa-credit-card"></i> Buy with credit card</button>
                        <form style="display:none" method="post" action="{{ route('purchase.initiate.crypto') }}">
                            @csrf
                            <input type="hidden" name="id" id="plan_id" value="{{ $plans->id ?? '' }}">
                            <input type="hidden" name="discount_user_id" id="discount_user_id" value="{{ $coupon_user->id ?? '0' }}">
                            <button name="payment" value="crypto" type="submit" class="btn btn-sm btn-primary w-100" style = "background-color:#006AB1;    padding: 13px 0px"><i class="fab fa-bitcoin"></i> Buy with crypto</button>
                        </form>
						
						<?php
								$plan_id =  $plans->stripe_plan_id;
								//$plan_id = $plans->title == 'Basic' ? 'price_1K3hMdFWn64iS5yRZ772bTeM':'price_1K3hNLFWn64iS5yR9dzBQRFI';
								$record = \DB::table('custom_payment_info')->take(1)->first();
								//echo "<pre>"; print_r($record->stripe_publish_key); ;
						?>	
						
						<form method="post" action="{{ route('purchase.initiate.stripe_popup') }}">
                            @csrf
							 
							
								<input name="plan" type="hidden" value="{{ $plans->id ?? '' }}" />         
								<input name="interval" type="hidden" value="year" />
								<input type="hidden" name="discount_user_id" id="discount_user_id" value="{{ $coupon_user->id ?? '0' }}">								
								<input name="price" type="hidden" value="4.90" />         
								<input name="currency" type="hidden" value="usd" />         
								<input name="stripe_plan_id" type="hidden" value="{{$plan_id}}" />         
								
								<script
								  src="https://checkout.stripe.com/checkout.js" class="stripe-button"
								  data-key="{{$record->stripe_publish_key}}"
								  data-name="{{ $plans->title ?? '' }}"
								  data-description="Purchase {{ $plans->title ?? '' }}"
								  data-panel-label="Subscribe Now"
								  data-label="Subscribe Now"
								  data-email="{{\Auth::user()->email}}"
								  data-locale="auto">
								</script>
							</form>
							
							<script>
								setTimeout(function(){
								  $(function() {
									$(".stripe-button-el").replaceWith('<button name="payment" value="stripe-popup" type="submit" class="btn btn-sm btn-primary w-100" style = "background-color:#006AB1;padding: 13px 0px;margin-top: 8px;"><i class="fab fa-stripe"></i> Buy with credit card</button>');
									});
								  }, 300);
							</script>
							
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" name="business" value="mers.distrokid3@gmail.com">
                                <input type="hidden" name="item_name" value="Singo.io {{ $plans->title ?? '' }} Membership">
                                <input type="hidden" name="item_number" value="SNG001002334">
                                <input type="hidden" name="amount" value="@if($coupon == null){{ $plans->total_amount ?? '' }}@else {{ $coupon->discounted_price }} @endif">
                                <input type="hidden" name="tax" value="">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="currency_code" value="EUR">
                                <input type="hidden" name="first_name" value="">
                                <input type="hidden" name="last_name" value="">
                                <input type="hidden" name="address1" value="">
                                <input type="hidden" name="city" value="">
                                <input type="hidden" name="state" value="">
                                <input type="hidden" name="zip" value="">
                                <input type="hidden" name="country" value="">
                                <input type="hidden" name="return" value="@if($coupon == null){{ route('paypal.payment.success', ['id' => $plans->id, 'coupon_user' => 0]) }}@else {{ route('paypal.payment.success', ['id' => $plans->id, 'coupon_user' => $coupon_user->id]) }} @endif">
                                <input type="hidden" name="return_cancel" value="{{ route('payments.cancel')  }}">
                                <div class = "btn-sm btn-primary w-100 mt-2" style = "background-color:#006AB1">
                                    <span class = "float-middle">
                                        <input type="image" name="submit" style="height: 34px;object-fit: cover;"
                                            src="{{ asset('assets/vendor/images/pay-pal.png') }}"
                                            alt="Buy Now">
                                        </span>
                                </div>
                            </form>
                            <div class="mt-2">
                                <a href="#" class="btn btn-sm btn-warning w-100" style = "padding: 13px 0px" id="coupon_click" class="btn btn-primary" data-toggle="modal" data-target="#generateauto"><span class="text-white"> <i class="fas fa-gift"></i> Have a coupon?</span></a>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


  <!-- Modal -->
<form action="{{ route('coupon.check') }}" method="post">
    @csrf
    <input type="hidden" name="plan_id" value="{{ $plans->id }}">
  <div class="modal fade" id="generateauto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Coupon Code</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group ">
                <label for="coupon_code">Enter Coupon Code</label>
                <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" id="coupon_code" class="form-control" required>
                <small id="coupon_code_price_note" class="form-text text-muted"></small>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"  style="width: 20%;">Skip</button>
          <button type="submit" class="btn btn-primary w-75">Check</button>
        </div>
      </div>
    </div>
  </div>
</form>


@endsection

@push('page_scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script>

        var Stripe = new Stripe("{{ config('app.stripe_pk') }}")
        window.$("#initiateStripe").click(() => {
            var id = $('#plan_id').val();
            var discount_user_id = $('#discount_user_id').val();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('discount_user_id', discount_user_id);
            fetch("/payments/create/stripe", {
                method: "POST",
                body:formData,
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (session) {
                    return Stripe.redirectToCheckout({ sessionId: session.id });
                })
                .then(function (result) {
                    // If redirectToCheckout fails due to a browser or network
                    // error, you should display the localized error message to your
                    // customer using error.message.
                    if (result.error) {
                        alert(result.error.message);
                    }
                })
                .catch(function (error) {
                    console.error("Error:", error);
                });
        });

    </script>
    <!-- Google Tag Manager -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GTM-MGSSDXG"></script>
    <!-- End Google Tag Manager -->
    <!-- Event snippet for Purchase + Value conversion page -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        var transaction_id = (Math.random() + 1).toString(36).substring(7)+"-{{ \Auth::user()->id }}";
        gtag('js', new Date());
        gtag('event', 'conversion', {
        'send_to': 'AW-10832034363/WHxRCIz-o7MDELv0jq0o', 'value': {{ $plans->total_amount ?? 0 }},
        'currency': 'EUR',
        'transaction_id': transaction_id
        });
    </script>
@endpush
