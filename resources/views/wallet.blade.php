@extends('layouts.app')
@push('page_css')
    <x-styles.datatable/>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center">
                <h5 class="text-white">Current Balance</h5>
                <h1>{{ auth()->user()->balance }} $</h1>

                <button class="singo-btn secondarybgcolor" data-toggle="modal" data-target="#requestPayout">Request payout </button>
            </div>
            <div class="col-md-12">
                {{--  <div class="card">
                    <div class="card-header">
                        Request Payment
                        <span class="float-right">Current Balance: {{ auth()->user()->balance }}$</span>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <p class="alert alert-success text-center">
                                {{ session('success') }}
                            </p>
                        @elseif(session('error'))
                            <p class="alert alert-danger text-center">
                                {{ session('error') }}
                            </p>
                        @endif

                        <form method="post" action="{{ route('wallet.payout') }}">
                            @csrf
                            <div class="form-group">
                                <label for="amount">Payment Amount </label>
                                <input type="number" id="amount" name="amount" class="form-control" required min="5" max="{{ auth()->user()->balance }}">
                                <div class="my-2">
                                    <span class="text-info">Please Request at least 5 $</span><br>
                                    <span class="text-info">Do not request more than your balance ( {{auth()->user()->balance}} $ )</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payout">Payout method</label>
                                <select class="form-control" name="payoutMethod" id="payout" required>

                                    @if(isset(auth()->user()->btcAddress))
                                        <option value="Bitcoin">Bitcoin</option>
                                    @else
                                        <optgroup label="Please Provide Bitcoin Address to Use Bitcoin"></optgroup>
                                    @endif
                                    @if(isset(auth()->user()->ltcAddress))
                                        <option value="Litecoin">Litecoin</option>
                                    @else
                                        <optgroup label="Please Provide Litecoin Address to Use Litecoin"></optgroup>
                                    @endif
                                    @if(isset(auth()->user()->ethAddress))
                                        <option value="Ethereum">Ethereum</option>
                                    @else
                                        <optgroup label="Please Provide Ethereum Address to Use Ethereum"></optgroup>
                                    @endif
                                    @if(isset(auth()->user()->paypalEmail))
                                        <option value="Paypal">Paypal</option>
                                    @else
                                        <optgroup label="Please Provide Paypal Email to Use Paypal"></optgroup>
                                    @endif
                                    @if(isset(auth()->user()->iban))
                                        <option value="Bank">Bank transfer</option>
                                    @else
                                        <optgroup label="Please Provide IBAN Email to Use Bank transfer"></optgroup>
                                    @endif
                                </select>
                            </div>
                            <button class="btn btn-success">Request payout</button>
                            <p class="font-weight-light py-1">
                                <l>New report is coming every month between 25.-30. and payout requests will be processed on 15. of a month.</l><br><br>
								<i class="text-blue fas fa-exclamation-circle"> Streaming stores report earnings usually with a 3-month delay (sometimes more) so please be patient, once we got a report we will automatically add this to your wallet.</i>
                            </p>
                        </form>
                    </div>
                </div> --}}
                <div class="card11">
                    <h3>Report</h3>
                    <div class="table-responsive">
                        <table id="table_one" class="table ">
                            <thead>
                            <tr>
                                <th class="align-middle pl-2">SL</th>
                                <th class="align-middle pl-2">Date</th>
                                <th class="align-middle pl-2">Streams</th>
                                <th class="align-middle pl-2">Stores</th>
                                <th class="align-middle pl-2">Money</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($reports->count()>0)
                                @foreach($reports as $rt)
                                    <tr>
                                        <td class="align-middle pl-2">{{ $loop->iteration }}</td>
                                        <td class="align-middle pl-2">{{ date('d-m-Y', strtotime($rt->date)) }}</td>
                                        <td class="align-middle pl-2">{{ $rt->streams }}</td>
                                        <td class="align-middle pl-2">{{ $rt->store->title }}</td>
                                        <td class="align-middle pl-2">${{ $rt->money }}</td>
                                    </tr>
                                @endforeach
                            @else
                            <tr> 
                                <td class="text-grey1">No data Found</td>
                                
                            </tr>
                            @endif

                            </tbody>
                        </table>
                        <div class="text-center light-drk">
                               {{ $reports->Oneachside(2)->links('pagination.default') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card11">
                    <h3>Payout history</h3> 
                    <div class=" table-responsive">
                        <table class="table " id="table_two">
                            <thead>
                            <tr>
                                <th class="align-middle pl-2">Currency</th>
                                <th class="align-middle pl-2">Amount</th>
                                <th class="align-middle pl-2">Payout method</th>
                                <th class="align-middle pl-2">Date</th>
                                <th class="align-middle pl-2">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(auth()->user()->payouts()->count()>0)
                                    @foreach(auth()->user()->payouts()->get() as $payout)
                                        <tr>
                                            <td class="align-middle pl-2">{{ $payout->currency }}</td>
                                            <td class="align-middle pl-2">{{ $payout->amount }}</td>
                                            <td class="align-middle pl-2">{{ $payout->payoutMethod }}</td>
                                            <td class="align-middle pl-2">{{ $payout->created_at }}</td>
                                            <td class="align-middle pl-2">
                                                <span class="btn btn-{{ $payout->getStatusColor() }}">{{ $payout->getStatusText() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr> 
                                    <td class="text-grey1">No data Found</td>
                                    
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        @if(auth()->user()->payouts()->count()>0)
                        <div class="pagination-centered">
                            <div class="content">
                                <i class="fas fa-arrow-left"></i>
                                <p class="m-0">Showing 1 to {{auth()->user()->payouts()->count()}} of 1 entries</p>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Payout methods</div>
                    <div class="card-body">
                        @include('user-panel.tabs.payment-methods')
                    </div>
                </div>
            </div>
        </div> --}}
    </div>



    {{-- Modal --}}
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="requestPayout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

                {{-- <div class="card">
                    <div class="card-header">
                        Request Payment
                        <span class="float-right">Current Balance: {{ auth()->user()->balance }}$</span>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <p class="alert alert-success text-center">
                                {{ session('success') }}
                            </p>
                        @elseif(session('error'))
                            <p class="alert alert-danger text-center">
                                {{ session('error') }}
                            </p>
                        @endif

                        <form method="post" action="{{ route('wallet.payout') }}">
                            @csrf
                            <div class="form-group">
                                <label for="amount">Payment Amount </label>
                                <input type="number" id="amount" name="amount" class="form-control" required min="5" max="{{ auth()->user()->balance }}">
                                <div class="my-2">
                                    <span class="text-info">Please Request at least 5 $</span><br>
                                    <span class="text-info">Do not request more than your balance ( {{auth()->user()->balance}} $ )</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payout">Payout method</label>
                                <select class="form-control" name="payoutMethod" id="payout" required>

                                    @if(isset(auth()->user()->btcAddress))
                                        <option value="Bitcoin">Bitcoin</option>
                                    @else
                                        <optgroup label="Please Provide Bitcoin Address to Use Bitcoin"></optgroup>
                                    @endif
                                    @if(isset(auth()->user()->ltcAddress))
                                        <option value="Litecoin">Litecoin</option>
                                    @else
                                        <optgroup label="Please Provide Litecoin Address to Use Litecoin"></optgroup>
                                    @endif
                                    @if(isset(auth()->user()->ethAddress))
                                        <option value="Ethereum">Ethereum</option>
                                    @else
                                        <optgroup label="Please Provide Ethereum Address to Use Ethereum"></optgroup>
                                    @endif
                                    @if(isset(auth()->user()->paypalEmail))
                                        <option value="Paypal">Paypal</option>
                                    @else
                                        <optgroup label="Please Provide Paypal Email to Use Paypal"></optgroup>
                                    @endif
                                    @if(isset(auth()->user()->iban))
                                        <option value="Bank">Bank transfer</option>
                                    @else
                                        <optgroup label="Please Provide IBAN Email to Use Bank transfer"></optgroup>
                                    @endif
                                </select>
                            </div>
                            <button class="btn btn-success">Request payout</button>
                            <p class="font-weight-light py-1">
                                <l>New report is coming every month between 25.-30. and payout requests will be processed on 15. of a month.</l><br><br>
                                <i class="text-blue fas fa-exclamation-circle"> Streaming stores report earnings usually with a 3-month delay (sometimes more) so please be patient, once we got a report we will automatically add this to your wallet.</i>
                            </p>
                        </form>
                    </div>
                </div> --}}
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Request Payment
        </h5>

        <h5 class="float-right m-0">Current Balance: {{ auth()->user()->balance }}$


         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </h5>
        
      </div>
      <div class="modal-body">

                <div class="card">
                    {{-- <div class="card-header">
                        Request Payment
                        <span class="float-right">Current Balance: {{ auth()->user()->balance }}$</span>
                    </div> --}}
                    <div class="card-body">
                        @if(session('success'))
                            <p class="alert alert-success text-center">
                                {{ session('success') }}
                            </p>
                        @elseif(session('error'))
                            <p class="alert alert-danger text-center">
                                {{ session('error') }}
                            </p>
                        @endif

                        <form method="post" action="{{ route('wallet.payout') }}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="amount">Payment Amount </label>
                                    <input type="number" id="amount" name="amount" class="form-control" required min="5" max="{{ auth()->user()->balance }}">
                                    <div class="my-2">
                                        <span class="text-info">Please Request at least 5 $</span><br>
                                        <span class="text-info">Do not request more than your 
                                            <br>balance ( {{auth()->user()->balance}} $ )</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="payout">Payout method</label>
                                    <select class="form-control custom-select" name="payoutMethod" id="payout" required>

                                        @if(isset(auth()->user()->btcAddress))
                                            <option value="Bitcoin">Bitcoin</option>
                                        @else
                                            <optgroup label="Please Provide Bitcoin Address to Use Bitcoin"></optgroup>
                                        @endif
                                        @if(isset(auth()->user()->ltcAddress))
                                            <option value="Litecoin">Litecoin</option>
                                        @else
                                            <optgroup label="Please Provide Litecoin Address to Use Litecoin"></optgroup>
                                        @endif
                                        @if(isset(auth()->user()->ethAddress))
                                            <option value="Ethereum">Ethereum</option>
                                        @else
                                            <optgroup label="Please Provide Ethereum Address to Use Ethereum"></optgroup>
                                        @endif
                                        @if(isset(auth()->user()->paypalEmail))
                                            <option value="Paypal">Paypal</option>
                                        @else
                                            <optgroup label="Please Provide Paypal Email to Use Paypal"></optgroup>
                                        @endif
                                        @if(isset(auth()->user()->iban))
                                            <option value="Bank">Bank transfer</option>
                                        @else
                                            <optgroup label="Please Provide IBAN Email to Use Bank transfer"></optgroup>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="text-center">
                                <button class="singo-btn secondarybgcolor">Request payout</button>
                            </div>
                            
                            <p class="font-weight-light py-1">
                                <l>New report is coming every month between 25.-30. and payout requests will be processed on 15. of a month.</l><br><br>
                                <i class="text-blue fas fa-exclamation-circle"> Streaming stores report earnings usually with a 3-month delay (sometimes more) so please be patient, once we got a report we will automatically add this to your wallet.</i>
                            </p>
                        </form>
                    </div>
                </div>


      </div>
      
    </div>
  </div>
</div>
@endsection

@push('page_scripts')
    <script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/buttons.html5.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            // $('#table_one').DataTable({
            //     dom: 'Bfrtip',
            //     // buttons: [
            //     //     'pageLength'
            //     // ],
            //     searching: false,
            //     bPaginate: false,
            //     buttons: []
            // });
            // $('#table_two').DataTable({
            //     dom: 'Bfrtip',
            //     bPaginate: false,
            //     searching: false,
            //     buttons: []
            //     // buttons: [
            //     //     'pageLength'
            //     // ]
            // });
        });
    </script>
@endpush
