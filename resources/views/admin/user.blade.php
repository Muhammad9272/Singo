@extends('layouts.app')

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection

@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid orig-dtb">
        <div class="row content-header mb-2">
            <div class="col-md-12">
                <h1> User Information </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    <span class="float-left">
                        <h5 class="mb-0 mt-1">User #{{ $user->id }}</h5>
                    </span>
                        @if (auth()->user()->type == 3)
                            <span class="float-right ml-2">
                        <form action="{{route('make_primium',$user->id)}}" method="post">
                            @csrf
                            <select name="user_status " id="user_status" class="form-control form-control-success rounded-0 select-btn-dk" required>
                                <option hidden value="">Choose...</option>
                                    @if( $user->status == 1 )
                                    <option value="" selected disabled> Active </option>
                                    <option value="0"> Disabled </option>
                                @elseif( $user->status == 0 )
                                    <option value="0"> Active </option>
                                    <option value="" selected disabled> Disabled </option>
                                @else
                                @endif
                            </select>
                            @if ($errors->has('plan'))
                                <span class="text-danger">{{ $errors->first('plan') }}</span>
                            @endif
                        </form>
                    </span>
                        @endif
                        <span class="float-right">
                        @if (auth()->user()->type == 3)
                                <form action="{{route('make_primium',$user->id)}}" method="post">
                            @csrf
                            <select name="plan" id="plan" class="form-control form-control-success rounded-0 select-btn-dk" required>
                                <option hidden value="">Choose...</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}" @if($user->plan == $plan->id) selected disabled @endif>{{ $plan->title }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('plan'))
                                        <span class="text-danger">{{ $errors->first('plan') }}</span>
                                    @endif
                        </form>
                            @endif
                    </span>
                    </div>
                    <div class="card-body">
                        @if(session('balance'))
                            <div class="alert alert-success">{{ session('balance') }}</div>
                        @endif
                        @if(session('success'))
                            <p class="alert alert-success text-center">
                                {{ session('success') }}
                            </p>
                        @elseif(session('error'))
                            <p class="alert alert-danger text-center">
                                {{ session('error') }}
                            </p>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-white">Information</h5>
                                <p><strong>Name: </strong>{{ $user->name }}</p>
                                <p><strong>Artist Name: </strong>{{ $user->artistName }}</p>
                                <p><strong>Email: </strong>{{ $user->email }}</p>
                                <p><strong>Stripe Customer ID: </strong>{{ $user->stripe_customer_id ?? 'Not set yet' }}
                                    @if($user->stripe_subscription_id && $user->stripe_subscription_status==1)
                                        <a href="{{ route('subscription.cancel') }}">Cancel Subscription</a>
                                    @else
                                        <br>Subscription Cancelled
                                    @endif
                                </p>

                                <p>
                                    <strong>Paid Plan: </strong>
                                    @php
                                        $plans = App\Models\Plan::findOrFail($user->plan);
                                    @endphp
                                    {{ $plans->title }}
                                </p>
                                <p><strong>Balance: </strong>&dollar;{{ $user->balance }}</p>
                                <p>
                                    <strong>Administrator: </strong>@if($user->type == 0) User @elseif($user->type == 1)Admin @elseif($user->type == 2) Moderator @else Super-Admin @endif
                                </p>
                                <p>
                                    <strong>Bitcoin Address: </strong>@if($user->btcAddress) {{ $user->btcAddress }} @else Not set @endif
                                </p>
                                <p>
                                    <strong>Litecoin Address: </strong>@if($user->ltcAddress) {{ $user->ltcAddress }} @else Not set @endif
                                </p>
                                <p>
                                    <strong>Ethereum Address: </strong>@if($user->ethAddress) {{ $user->ethAddress }} @else Not set @endif
                                </p>
                                <p>
                                    <strong>Paypal: </strong>@if($user->paypalEmail) {{ $user->paypalEmail }} @else Not set @endif
                                </p>
                                <p><strong>IBAN: </strong>@if($user->iban) {{ $user->iban }} @else Not set @endif</p>
                                <p>
                                    <strong>Support Pin: </strong>@if($user->support_pin) {{ $user->support_pin }} @else Not set @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if(auth()->user()->type == 3 )

                                    @if($user->type != 3 )
                                        <h6 class="text-white"><strong>Update Privileges</strong></h6>
                                        <form method="post" class="" action="{{ route('admin.users.toggle', $user->id) }}">
                                            @csrf
                                            <select name="type" id="type" class="form-control form-control-success custom-select" required>
                                                <option selected hidden value="">Choose...</option>
                                                <option value="0">User</option>
                                                <option value="1">Admin</option>
                                                <option value="2">Moderator</option>
                                            </select>
                                            @if ($errors->has('type'))
                                                <span class="text-danger">{{ $errors->first('type') }}</span>
                                            @endif
                                            <button type="submit" class="btn btn-warning float-right mt-2" onclick="return confirm('Are you sure to update this user privilege?')">Update</button>
                                        </form>

                                        <div class="py-3 border-bottom">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="mt-3 text-white"><strong>Delete User</strong></h6>
                                                    <form method="post" action="{{ route('admin.users.delete', $user->id) }}" onclick="return confirm('Are you sure to delete this user?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger d-inline">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>

                                                <div class="col-md-6">
                                                    @if($user->isBanned())
                                                        <h6 class="mt-3 text-white"><strong>Unban User</strong></h6>
                                                        <form method="post" action="{{ route('admin.users.unban', $user->id) }}" onclick="return confirm('Are you sure to unban this user?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success d-inline">
                                                                <i class="fas fa-trash"></i> Unban
                                                            </button>
                                                        </form>
                                                    @else
                                                        <h6 class="mt-3 text-white"><strong>Ban User</strong></h6>
                                                        <form method="post" action="{{ route('admin.users.ban', $user->id) }}" onclick="return confirm('Are you sure to ban this user?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning d-inline">
                                                                <i class="fas fa-trash"></i> Ban
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    @endif
                                    <h6 class="mt-3 text-white"><strong>Update balance</strong></h6>
                                    <form method="post" action="{{ route('admin.user.balance', $user->id) }}">
                                        @csrf
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control @error('balance') is-invalid @enderror" name="balance" placeholder="0.00" id="balance">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">&dollar;</span>
                                            </div>
                                            @error('balance')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button class="btn btn-success" type="submit">
                                            <i class="fas fa-sync-alt"></i> Update
                                        </button>
                                    </form>



                                @endif

                                <div class="row mt-4">
                                    <div class="col-md-12 m-auto">
                                        <table class="table  table-striped">
                                            <tr style="padding-top:0px;padding-bottom:0px;">
                                                <td><strong>Address-1</strong></td>
                                                <td>:</td>
                                                <td>{{ $user->address_1 ?? 'not set yet' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Address-2</strong></td>
                                                <td>:</td>
                                                <td>{{ $user->address_2 ?? 'not set yet'  }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>City</strong></td>
                                                <td>:</td>
                                                <td>{{ $user->city ?? 'not set yet'  }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>State</strong></td>
                                                <td>:</td>
                                                <td>{{ $user->state ?? 'not set yet'  }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Zip</strong></td>
                                                <td>:</td>
                                                <td>{{ $user->zip ?? 'not set yet'  }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(auth()->user()->type == 3 || auth()->user()->type == 1)
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0">Total Streams</h4>
                        </div>
                        <div class="card-body">
                            <x-charts.artist-streams :user_id="$user->id"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0">Total Downloads</h4>
                        </div>
                        <div class="card-body">
                            <x-charts.artist-downloads :user_id="$user->id"/>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-12">

                    <div>
                        <i class="text-blue fas fa-exclamation-circle"></i>
                        "DSP_30" = Streams under 30 seconds
                    </div>
                </div> --}}
                <div class="col-md-12 mt-20">
                    <div class="text-center">
                        <img class="w-40" src="{{ asset('image/icons/info.svg') }}" >
                        <p> "DSP_30" = Streams under 30 seconds</p> 
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->type == 3 || auth()->user()->type == 1)
            <div class="card mt-2">
                <div class="card-header">
                    <span class="float-left">
                        <h4>Report</h4>
                    </span>
                    <span class="float-right">
                    <a href="{{ route('admin.report.add', $user->id) }}" class="btn btn-primary btn-sm">Add New</a>
                    </span>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_one" class="table table-dark table-striped display  ">
                            <thead>
                            <tr>
                                <th class="align-middle pl-2">SL</th>
                                <th class="align-middle pl-2">Date</th>
                                <th class="align-middle pl-2">Streams</th>
                                <th class="align-middle pl-2">Stores</th>
                                <th class="align-middle pl-2">Money</th>
                                <th class="align-middle pl-2">Data</th>
                                <th class="align-middle pl-2">Added By</th>
                                <th class="align-middle text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reports as $key => $rt )
                                <tr>
                                    <td class="align-middle pl-2"> {{$key + 1 }}</td>
                                    <td class="align-middle pl-2">{{date('d-m-Y', strtotime($rt->date))}}</td>
                                    <td class="align-middle pl-2">{{$rt->streams}}</td>
                                    <td class="align-middle pl-2">{{optional($rt->store)->title}}</td>
                                    <td class="align-middle pl-2">${{$rt->money}}</td>
                                    <td class="align-middle pl-2">
                                        <a href="{{ route('admin.report.download', $rt->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-download mr-2"></i>Download
                                        </a>
                                    </td>
                                    <td class="align-middle pl-2">
                                        @php
                                            $users = App\Models\User::findOrFail($rt->created_by);
                                        @endphp
                                        {{ $users->name }}
                                    </td>
                                    <td class="align-middle pl-2">
                                        <div class="btn-group">
                                            <a href="{{route('admin.report.view',$rt->id)}}" class="btn btn-sm btn-dark">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{route('admin.report.edit',$rt->id)}}" class="btn btn-sm btn-info">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Albums from this user</div>
                    <div class="card-body table-responsive">
                        <table class=" table table-dark table-striped display" id="table_two">
                            <thead>
                            <tr>
                                <th class="align-middle"></th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">Genre</th>
                                <th class="align-middle">Status</th>
                                <th class="align-middle">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->albums()->get() as $album)
                                <tr>
                                    <td class="align-middle">
                                        <img width="50" height="50" src="{{ \Illuminate\Support\Facades\Storage::url('albums/'.$album->id.'/'.$album->cover) }}">
                                    </td>
                                    <td class="align-middle">{{ $album->title }}</td>
                                    <td class="align-middle">{{ $album->genre->name }}</td>
                                    <td class="align-middle">
                                        <span class="btn btn-sm btn-{{ $album->getStatusColor() }}">{{ $album->getStatusText() }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('album', $album->id) }}" class="btn btn-sm btn-success"><i class="fas fa-eye"></i> View</a>
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
                <div class="card card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-history mr-2"></i>Ticket History</i></h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-2 p-2">
                            <table class="table table-dark table-striped display" id="table_three">
                                <thead>
                                <tr>
                                    <th class="align-middle text-center">Ticket Type</th>
                                    <th class="align-middle text-center">Subject</th>
                                    <th class="align-middle text-center">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tickets as $key_2 => $ticket)
                                    <tr>
                                        <td class="align-middle text-center">
                                            {{ $ticket->getTicketType() }}
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="ticketId">
                                                <a href="{{ route('ticket.show', $ticket->id) }}">#{{ $ticket->id }}</a>
                                            </span>
                                            <br>
                                            <span class="ticketSub">
                                                <a href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->subject }}</a>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">{{ $ticket->getTicketStatus() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('third_party_scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="https://unpkg.com/chart.js@^2.9.3/dist/Chart.min.js"></script>
    <script src="{{ asset('js/chartjs.js') }}"></script>
   {{--  <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script> --}}
@endsection

@push('page_scripts')
    <script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/buttons.html5.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#table_one').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pageLength'
                ]
            });
            $('#table_two').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pageLength'
                ]
            });
            $('#table_three').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pageLength'
                ]
            });
        });
    </script>
    <script>
        $('#plan').change(function () {
            this.form.submit();
        });
    </script>
@endpush
