@extends('layouts.app')
@push('page_css')
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
<style>

</style>
@endpush

@section('content')
@php
    $count = 0;
@endphp
    <div class="container-fluid" id="close_class">
        <div class="row content-header mb-2">
            <h1>Coupon</h1>
        </div>
        @if(session('success'))
            <p class="alert alert-success text-center mt-2">
                {{ session('success') }}
            </p>
        @elseif(session('error'))
            <p class="alert alert-danger text-center mt-2">
                {{ session('error') }}
            </p>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-center">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="float-left">
                            <h4>Create coupon</h4>
                        </span>

                    </div>
                    <div class="card-body">
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
        </div> --}}


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="float-left">
                            <h4>Coupon control <small>(Runnig coupons)</small></h4>
                        </span>
                        <span class="float-right">
                            <a href="#" class="btn btn-sm btn-primary" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Add new cupon</a>
                        </span>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover display nowrap table-striped" id="table_one">
                                        <thead>
                                        <tr>
                                            <th class = "align-middle pl-2">#SL</th>
                                            <th class = "align-middle pl-2">Coupon Code</th>
                                            <th class = "align-middle pl-2">Plan Price</th>
                                            <th class = "align-middle pl-2">Discount</th>
                                            <th class = "align-middle pl-2">Discounted Price</th>
                                            <th class = "align-middle pl-2">User Used</th>
                                            <th class = "align-middle pl-2">Start date</th>
											<th class = "align-middle pl-2">Stripe Coupon ID</th>
                                            <th class = "align-middle pl-2">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($coupons as $key1 =>$cp)
                                            @if(!$cp->end_date)
                                                <tr>
                                                    <td class = "align-middle pl-2">
                                                        {{ $key1+1 }}
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{ $cp->code }}
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{ $cp->plan_price }} €
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        @if($cp->discount_percent)
                                                            {{ $cp->discount_percent }} %
                                                        @elseif($cp->discount_amount)
                                                            {{ $cp->discount_amount }} €
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{ $cp->discounted_price }} €
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{ App\Models\CouponUser::where('coupon_id', $cp->id)->distinct('user_id')->count() }}
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{  date('d-M-y', strtotime($cp->start_date)) }}
                                                    </td>
													<td class = "align-middle pl-2">
                                                        {{  $cp->stripe_coupon_id }}
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        <a href="{{ route('coupon.end', $cp->id) }}" class="btn btn-sm btn-danger"> End Coupon </a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="float-left">
                            <h4>Coupon history <small>(Ended coupons)</small></h4>
                        </span>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover display nowrap table-striped" id="table_two">
                                        <thead>
                                        <tr>
                                            <th class = "align-middle pl-2">#SL</th>
                                            <th class = "align-middle pl-2">Coupon Code</th>
                                            <th class = "align-middle pl-2">Plan Price</th>
                                            <th class = "align-middle pl-2">Discount</th>
                                            <th class = "align-middle pl-2">Discounted Price</th>
                                            <th class = "align-middle pl-2">User Used</th>
                                            <th class = "align-middle pl-2">Start date</th>
                                            <th class = "align-middle pl-2">End date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($coupons as $key1 =>$cp)
                                            @if($cp->end_date)
                                                <tr>
                                                    <td class = "align-middle pl-2">
                                                        {{ $count+1 }}
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{ $cp->code }}
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{ $cp->plan_price }} €
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        @if($cp->discount_percent)
                                                            {{ $cp->discount_percent }} %
                                                        @elseif($cp->discount_amount)
                                                            {{ $cp->discount_amount }} €
                                                        @else
                                                        @endif
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{ $cp->discounted_price }} €
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{ App\Models\CouponUser::where('coupon_id', $cp->id)->distinct('user_id')->count() }}
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{  date('d-M-y', strtotime($cp->start_date)) }}
                                                    </td>
                                                    <td class = "align-middle pl-2">
                                                        {{  date('d-M-y', strtotime($cp->end_date)) }}
                                                    </td>
                                                </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>





  <!-- Modal -->
  <div class="modal fade" id="generateauto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Generate automate coupon</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="generate_coupon_id">Select Plan for Coupon</label><span class="text-danger">*</span>
                <select class="form-control" id="generate_coupon_id" name="generate_coupon_id" required>
                    <option hidden >select plan ...</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"  style="width: 20%;">Close</button>
          <button type="submit" class="btn btn-primary w-75">Generate</button>
        </div>
      </div>
    </div>
  </div>





<!-- Large modal -->
<form action="{{ route('coupon.create') }}" method="post">
    @csrf
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Custom Coupon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="plan_id">Select Plan for Coupon</label><span class="text-danger">*</span>
                    <select class="form-control" id="plan_id" name="plan_id"  required>
                        <option hidden>select plan ...</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="coupon_code">Coupon Code</label><span class="text-danger">*</span>
                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Enter coupon code" required>
                </div>
				
				<div class="form-group">
                    <label for="stripe_coupon_id">Stripe Coupon ID</label>
                    <input type="text" class="form-control" id="stripe_coupon_id" name="stripe_coupon_id" placeholder="Enter stripe coupon code" required>
                </div>

                <div class="form-group">
                    <label for="plan_price">Plan Price</label><span class="text-danger">*</span>
                    <input type="number" step=any class="form-control" id="plan_price" name="plan_price" placeholder="plan price will be set automatically when you select plan" readonly required>
                </div>

                <div class="form-group mb-0">
                    <label for="discount_percent">Coupon Discount</label><span class="text-danger">*</span>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <input type="number" step=any name="discount_percent" value="{{ old('discount_percent') }}" id="discount_percent" class="form-control" >
                        <small id="discount_percent_note" class="form-text text-muted">Discount in percent</small>
                    </div>
                    <div class="col-md-6">
                        <input type="number" step=any name="discount_amount" value="{{ old('discount_amount') }}" id="discount_amount" class="form-control" >
                        <small id="discount_amount_note" class="form-text text-muted">Discount in number</small>
                    </div>
                </div>

                <div class="form-group ">
                    <label for="discounted_price">Discounted price</label>
                    <input type="number" step=any name="discounted_price" value="{{ old('discounted_price') }}" id="discounted_price" class="form-control" placeholder="discounted price will be counted automatically" readonly required>
                    <small id="discounted_price_note" class="form-text text-muted"></small>
                </div>

                <div class="form-group">
                    <label for="status">Select Status</label><span class="text-danger">*</span>
                    <select class="form-control" id="status" name="status" required>
                        <option value="1" selected>Running</option>
                        <option value="0" disabled>Ended</option>
                    </select>
                </div>



            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" style="width: 20%;" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary w-75">Create coupon</button>
            </div>
        </div>
    </div>
</div>
</form>







@endsection

@push('page_scripts')
<script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/buttons.html5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#table_one').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                 'pageLength'
            ]
        });
        $('#table_two').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                 'pageLength'
            ]
        });


        $('#plan_id').change(function(){
            let id = $('#plan_id').val();
            if(id != null){
                let _url = ("{{ route('coupon.plan', ['id']) }}");
                let __url = _url.replace('id', id);
                $.ajax({
                    url: __url,
                    method: "GET",
                    success: function (response) {
                        $('#plan_price').val(response);
                    }
                });
            }
        });

        function payment_calculation() {

            let discount_percent = $("#discount_percent");
            let discount_percent_val = $("#discount_percent").val();
            let discount_amount = $("#discount_amount");
            let discount_amount_val = $("#discount_amount").val();
            let plan_price = $("#plan_price");
            let plan_price_val = $("#plan_price").val();
            let discounted_price = $("#discounted_price");
            let discounted_price_val = $("#discounted_price").val();

            let total = 0;

            if ((plan_price_val != '') && (discount_percent_val != '')) {
                total = plan_price_val - ((plan_price_val * discount_percent_val) / 100);
            } else {
                total = plan_price_val;
            }
            if (discount_percent_val) {
                discount_amount.val('');
                discount_amount.attr('disabled', 'disabled');
            } else {
                discount_amount.removeAttr('disabled', 'disabled');
            }
            if (discount_amount_val) {
                discount_percent.val('');
                discount_percent.attr('disabled', 'disabled');
            } else {
                discount_percent.removeAttr('disabled', 'disabled');
            }
            if (discount_percent_val && discount_amount_val) {
                discount_amount.val('');
                discount_percent.val('');
                discount_amount.removeAttr('disabled', 'disabled');
                discount_percent.removeAttr('disabled', 'disabled');
            }
            if (discount_amount_val != '' && total > 0) {
                total = total - discount_amount_val;
            }
            discounted_price.val(parseFloat(total).toFixed(2));
        }

        payment_calculation();

        $(document).on('keyup change focusout', "#plan_id, #plan_price, #discount_percent, #discount_amount", function () {
            payment_calculation();
        });



    } );
</script>


@endpush
