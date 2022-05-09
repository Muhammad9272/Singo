@extends('layouts.app')


@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <h1>Payout requests</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Pending Payouts</div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped display nowrap" id = "table_one">
                            <thead>
                            <tr>
                                <th class = "align-middle pl-2">ID</th>
                                <th class = "align-middle pl-2">Name</th>
                                <th class = "align-middle pl-2">Amount</th>
                                <th class = "align-middle pl-2">Currency</th>
                                <th class = "align-middle pl-2">Payout Method</th>
                                <th class = "align-middle text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pending as $payout)
                                <tr>
                                    <td class = "align-middle pl-2">{{ $payout->id }}</td>
                                    <td class = "align-middle pl-2">{{ $payout->user->name }}</td>
                                    <td class = "align-middle pl-2">{{ $payout->amount }}</td>
                                    <td class = "align-middle pl-2">{{ $payout->currency }}</td>
                                    <td class = "align-middle pl-2">{{ $payout->payoutMethod }}</td>
                                    <td class = "align-middle text-center">

                                        <button type="button" class="btn btn-success btn-sm" onclick="payoutAccept({{$payout->id}})"><i class="fas fa-check-circle"></i> Accept</button>
                                        {{-- 
                                        <form method="post" action="{{ route('admin.payouts.accept', $payout->id) }}" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check-circle"></i> Accept</button>
                                        </form>
                                        --}}
                                        <form method="post" action="{{ route('admin.payouts.decline', $payout->id) }}" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times-circle"></i> Decline</button>
                                        </form>
                                        <a href="{{ route('admin.user', $payout->user->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-eye"></i> View User</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Accepted Payouts</div>
                    <div class="card-body table-responsive">

                        <table class="table table-striped display nowrap" id = "table_two">
                            <thead>
                            <tr>
                                <th class = "align-middle pl-2">ID</th>
                                <th class = "align-middle pl-2">Name</th>
                                <th class = "align-middle pl-2">Amount</th>
                                <th class = "align-middle pl-2">Currency</th>
                                <th class = "align-middle pl-2">Payout Method</th>
                                
                                <th class = "align-middle text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accepted as $payout)
                                <tr>
                                    <td class = "align-middle text-left">{{ $payout->id }}</td>
                                    <td class = "align-middle text-left">{{ $payout->user->name }}</td>
                                    <td class = "align-middle text-left">{{ $payout->amount }}</td>
                                    <td class = "align-middle text-left">{{ $payout->currency }}</td>
                                    <td class = "align-middle text-left">{{ $payout->payoutMethod }}</td>
                                    
                                    <td class = "align-middle text-center">
                                        <!-- data-toggle="modal" data-target="#TransactionModal" -->
                                        <button data-id="{{$payout->user->id}}" class="btn btn-warning btn-sm" onclick="ViewTransaction({{$payout->id}})"><i class="fas fa-eye"></i> View Transaction</button>

                                        <a href="{{ route('admin.user', $payout->user->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-eye"></i> View User</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Declined Payouts</div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped nowrap" id = "table_three">
                            <thead>
                            <tr>
                                <th class = "align-middle pl-2">ID</th>
                                <th class = "align-middle pl-2">Name</th>
                                <th class = "align-middle pl-2">Amount</th>
                                <th class = "align-middle pl-2">Currency</th>
                                <th class = "align-middle pl-2">Payout Method</th>
                                <th class = "align-middle text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($declined as $payout)
                                <tr>
                                    <td class = "align-middle text-left">{{ $payout->id }}</td>
                                    <td class = "align-middle text-left">{{ $payout->user->name }}</td>
                                    <td class = "align-middle text-left">{{ $payout->amount }}</td>
                                    <td class = "align-middle text-left">{{ $payout->currency }}</td>
                                    <td class = "align-middle text-left">{{ $payout->payoutMethod }}</td>
                                    <td class = "align-middle text-center">
                                        <a href="{{ route('admin.user', $payout->user->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-eye"></i> View User</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Transaction Modal -->
    <div class="modal fade" id="TransactionModal" tabindex="-1" role="dialog" aria-labelledby="TransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                        <h4 id="TransactionModalLabel" class="mb-0">Edit Payout "<span id="artist_name1"></span>"</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4"> 
                                <p class="mb-0"><strong>Payout Amount:</strong></p>
                            </div>
                            <div class="col-md-8">
                                <p class="mb-0"><span id="payout_amount1"></span></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"> 
                                <p class="mb-0"><strong>Payout Method:</strong></p>
                            </div>
                            <div class="col-md-8">
                                <p class="mb-0"><span id="payout_method1"></span></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"> 
                                <p class="mb-0"><strong>Payout Information:</strong></p>
                            </div>
                            <div class="col-md-8">
                                <p class="mb-0"><span id="payout_info1"></span></p>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <div class="col-md-4"> 
                                <p class="mb-0"><strong>Payout Date:</strong></p>
                            </div>
                            <div class="col-md-8">
                                <p class="mb-0"><span id="payout_date1"></span></p>
                            </div>
                        </div>
                        <form action="{{route('admin.payouts.transaction')}}" enctype="multipart/form-data" method="POST" class="form-horizontal">  
                            @csrf
                            <input type="hidden" name="payout_id" id="payoutId1">                          
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <p class="mb-0"><strong>Transaction ID <span class="text-danger">*</span></strong></p>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="transactionID" id="transactionId" placeholder="Enter Transaction ID" class="form-control form-control-success" required="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8 ml-auto">
                                    <input type="submit" value="Edit Payment" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="payment_send" tabindex="-1" role="dialog" aria-labelledby="payment_sendModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                        <h4 id="payment_sendModalLabel" class="mb-0">Payout "<span id="artist_name"></span>"</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4"> 
                                <p class="mb-0"><strong>Payout Amount:</strong></p>
                            </div>
                            <div class="col-md-8">
                                <p class="mb-0"><span id="payout_amount"></span></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"> 
                                <p class="mb-0"><strong>Payout Method:</strong></p>
                            </div>
                            <div class="col-md-8">
                                <p class="mb-0"><span id="payout_method"></span></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"> 
                                <p class="mb-0"><strong>Payout Information:</strong></p>
                            </div>
                            <div class="col-md-8">
                                <p class="mb-0"><span id="payout_info"></span></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"> 
                                <p class="mb-0"><strong>Payout Date:</strong></p>
                            </div>
                            <div class="col-md-8">
                                <p class="mb-0"><span id="payout_date"></span></p>
                            </div>
                        </div>
                        <form action="{{route('admin.payouts.transaction')}}" enctype="multipart/form-data" method="POST" class="form-horizontal">  
                            @csrf
                            <input type="hidden" name="payout_id" id="payoutId">                          
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <p class="mb-0"><strong>Transaction ID <span class="text-danger">*</span></strong></p>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="transactionID" placeholder="Enter Transaction ID" class="form-control form-control-success" required="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8 ml-auto">
                                    <input type="submit" value="Payment send" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
@endsection


@push('page_scripts')

{{--<script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>--}}
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
        $('#table_three').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'pageLength'
            ]
        });
    } );
    
    //add payout transaction id
    function ViewTransaction(pid){
       jQuery.ajax({
          url: "{{ url('/get/ajax/payout') }}/?payoutID="+pid+"&type=view",
          method: 'get',
          success: function(result){
            console.log(result);
             $('#payoutId1').val(pid);
             $("#artist_name1").text(result.artistName);
             $("#payout_amount1").text(result.amount);
             $("#payout_method1").text(result.payoutMethod);
             $("#payout_info1").text(result.payout_info);
             $("#payout_date1").text(result.date);
             $("#transactionId").val(result.transactionId);
             $('#TransactionModal').modal('show');
          }}); 
       
    }

    function payoutAccept(pid){
        //alert(pid);
        jQuery.ajax({
              url: "{{ url('/get/ajax/payout') }}/?payoutID="+pid+"&type=accept",
              method: 'get',
              // data: {
              //    name: jQuery('#name').val(),
              //    type: jQuery('#type').val(),
              //    price: jQuery('#price').val()
              // },
              success: function(result){
                console.log(result);
                 $('#payoutId').val(pid);
                 $("#artist_name").text(result.artistName);
                 $("#payout_amount").text(result.amount);
                 $("#payout_method").text(result.payoutMethod);
                 $("#payout_info").text(result.payout_info);
                 $("#payout_date").text(result.date);
                 $('#payment_send').modal('show');
              }});
    }
</script>


@endpush