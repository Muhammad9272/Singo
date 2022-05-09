<div class="plan-card shadow-sm @if($plan->title == 'Premium' ) active @endif">
    <div class="plan-card-body">
        @if( (isset($plan->discount_percent ) && $plan->discount_percent > 0)|| (isset ($plan->discount_amount ) && $plan->discount_amount > 0 ))
            <div class="ribbon-wrapper text-lg ribbon-xl">
                <div class="ribbon bg-info">
                    @if(isset($plan->discount_percent ) && $plan->discount_percent > 0) {{ $plan->discount_percent }} %
                    Discount @elseif(isset ($plan->discount_amount ) && $plan->discount_amount > 0) {{ $plan->discount_amount }}
                    € Discount
                    @endif
                </div>
            </div>
        @endif

        <div class="mb-3">
            <div class="w-100">
                <h4 class="text-uppercase font-weight-bolder">{{ $plan->title }}</h4>

                @if(auth()->user()->plan == $plan->id )
                    <span class="badge badge-primary position-absolute" style="left: 10px; top: 10px;">
                        <i class="fas fa-check"></i>
                        Selected
                    </span>
                @endif
            </div>


            <ul class="plan-features-list py-4">
                <li>
                    <i class="fa fa-check"></i>
                    <div>
                        {!!html_entity_decode($plan->content_1)!!}
                    </div>
                </li>
                <li>
                    <i class="fa fa-check"></i>
                    <div>
                        {!!html_entity_decode($plan->content_2)!!}
                    </div>
                </li>
                <li>
                    <i class="fa fa-check"></i>
                    <div>
                        {!!html_entity_decode($plan->content_3)!!}
                    </div>
                </li>
                <li>
                    <i class="fa fa-check"></i>
                    <div>
                        {!!html_entity_decode($plan->content_4)!!}
                    </div>
                </li>
                <li>
                    <i class="fa fa-check"></i>
                    <div>
                        {!!html_entity_decode($plan->content_5)!!}
                    </div>
                </li>
            </ul>


            <div class="price">
                @if( (isset($plan->discount_percent ) && $plan->discount_percent > 0)|| (isset ($plan->discount_amount ) && $plan->discount_amount > 0 ))
                    <s class="text-sm">{{ $plan->amount }}</s>

                    <span class="d-flex">
                        <span class="font-weight-bolder text-2xl">{{ $plan->total_amount }}</span>

                        <div class="d-flex flex-column justify-content-around pl-2 py-4">
                            <span>
                                €
                            </span>
                            <span>
                                Per year
                            </span>
                        </div>
                    </span>

                @else
                    <span class="d-flex">
                        <span class="font-weight-bolder text-2xl">{{ $plan->amount }}</span>
                        <div class="d-flex flex-column justify-content-around pl-2 py-4">
                            <span>
                                €
                            </span>
                            <span>
                                Per year
                            </span>
                        </div>
                    </span>
                @endif


            </div>

        </div>
        @php $amounts = App\Models\Plan::findOrFail(auth()->user()->plan); if(isset($amounts->total_amount)) { $total_amount = $amounts->total_amount; } @endphp

        <div>
            @if(auth()->user()->plan == $plan->id )
                <button class="btn btn-light btn-purchase">
                <span>
                    Purchase now
                </span>
                </button>
            @elseif($plan->total_amount <= $total_amount)
                <a href="{{ route('purchases', ['id'=>$plan->id, 'coupon'=>0]) }}" class="btn btn-black btn-purchase">
                <span>
                    Purchase now
                </span>
                </a>
            @else
                <a href="{{ route('purchases', ['id'=>$plan->id, 'coupon'=>0]) }}" class="btn btn-black btn-purchase">
                <span class="text-white">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Purchase now
                </span>
                </a>
            @endif
        </div>
    </div>
</div>
