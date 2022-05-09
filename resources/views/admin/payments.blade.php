@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <div class="col-md-12">
                <h1>Payments</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="row pb-3 mb-4 border-bottom">
                                <div class="col-md-12 ml-auto">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <input type="text" class="form-control" placeholder="Search..."
                                                name="searchQuery" value="{{ request('searchQuery') }}">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <input type="date" name="date" id="payments-date" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <select name="method" class="form-control">
                                                <option value="">-- Select Method --</option>
                                                <option {{ request('method') ==  1 ? 'selected' : '' }} value="1">Stripe</option>
                                                <option {{ request('method') ==  2 ? 'selected' : '' }} value="2">Paypal</option>
                                                <option {{ request('method') ==  3 ? 'selected' : '' }} value="3">Crypto</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-success">Search</button>
                                            <a href="{{ route('admin.payments') }}" class="btn btn-warning">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">All payments</div>
                    <div class="card-body table-responsive">
                        <x-datatable.footable class="table table-striped">
                            <thead>
                            <tr>
                                <th class="align-middle pl-2">#</th>
                                <th class="align-middle text-center">Payment ID</th>
                                <th class="align-middle pl-2">Method</th>
                                <th class="align-middle pl-2">Plan</th>
                                <th class="align-middle pl-2">Amount</th>
                                <th class="align-middle text-center">Date</th>
                                <th class="align-middle pl-2">User</th>
                                <th class="align-middle pl-2">Artist Name</th>
                                <th class="align-middle text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payments as $key => $payment)
                                <tr>
                                    <td class="align-middle pl-2">{{ $payment->id }}</td>
                                    <td class="align-middle text-center">{{ $payment->paymentID }}</td>
                                    <td class="align-middle pl-2">{{ $payment->method }}</td>
                                    <td class="align-middle pl-2">
                                        {{ $payment->plan ?? 'not set' }}
                                        @php
                                            if($payment->user_discount){
                                                $cu = App\Models\CouponUser::findOrFail($payment->user_discount);
                                                $cd = App\Models\Coupon::findOrFail($cu->coupon_id);
                                                echo "(".$cd->code.")";
                                            }
                                        @endphp
                                    </td>
                                    <td class="align-middle pl-2">{{ $payment->amount ?? 'not set' }}</td>
                                    <td class="align-middle text-center">{{ $payment->created_at }}</td>
                                    <td class="align-middle pl-2">{{ $payment->user->name }}</td>
                                    <td class="align-middle pl-2">{{ $payment->user->artistName }}</td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('admin.user', $payment->user->id) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </x-datatable.footable>
                    </div>
                    <div class="card-footer">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
