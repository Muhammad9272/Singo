@extends('layouts.app')


@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <div class="col-md-12">
                <h1>User Requests</h1>
            </div>
        </div>
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
            <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Pending Requests</div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped display" id = "table_one">
                                <thead>
                                    <tr>
                                        <th class = "align-middle pl-2">SL</th>
                                        <th class = "align-middle pl-2">Album Title</th>
                                        <th class = "align-middle pl-2">Requested by</th>
                                        <th class = "align-middle pl-2">Requested for</th>
                                        <th class = "align-middle pl-2">Request</th>
                                        <th class = "align-middle pl-2">Requested at</th>
                                        <th class = "align-middle text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($user_request as $key => $ur)
                                @if ($ur->status == 0)
                                    <tr>
                                        <td class = "align-middle pl-2">{{ $key + 1 }}</td>
                                        <td class = "align-middle pl-2">{{ $ur->album->title }}</td>
                                        <td class = "align-middle pl-2">{{ $ur->users_6->name }}</td>
                                        <td class = "align-middle pl-2">@if(isset($ur->album_id))Edit Album @endif</td>
                                        <td class = "align-middle pl-2">{{ $ur->reason }}</td>
                                        <td class = "align-middle pl-2">{{date('d-m-Y', strtotime($ur->created_at))}}</td>
                                        <td class = "align-middle text-center">
                                            <span class = "btn-group">                                                                                           
                                                <a href ="{{ route('users.requests.accept',$ur->id) }}" class="btn btn-success btn-sm"><i class="fas fa-check-circle"></i> Accept</button>                                            
                                                <a href ="{{ route('users.requests.decline',$ur->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-times-circle"></i> Decline</button>
                                                <a href ="{{ route('album',$ur->album_id) }}" class="btn btn-warning btn-sm"><i class="fas fa-eye"></i> View Album</a>
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Accepted Requests</div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped display" id = "table_two">
                                <thead>
                                    <tr>
                                        <th class = "align-middle pl-2">SL</th>
                                        <th class = "align-middle pl-2">Album Title</th>
                                        <th class = "align-middle pl-2">Requested by</th>
                                        <th class = "align-middle pl-2">Requested for</th>
                                        <th class = "align-middle pl-2">Request</th>
                                        <th class = "align-middle pl-2">Requested at</th>
                                        <th class = "align-middle pl-2">Status</th>
                                        <th class = "align-middle pl-2">Accepted by</th>
                                        <th class = "align-middle pl-2">Accepted at</th>
                                        <th class = "align-middle pl-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($user_request as $key => $ur)
                                @if ($ur->status == 1)
                                    <tr>
                                        <td class = "align-middle pl-2">{{ $key + 1 }}</td>
                                        <td class = "align-middle pl-2">{{ $ur->album->title }}</td>
                                        <td class = "align-middle pl-2">{{ $ur->users_6->name }}</td>
                                        <td class = "align-middle pl-2">@if(isset($ur->album_id))Edit Album @endif</td>
                                        <td class = "align-middle pl-2">{{ $ur->reason }}</td>
                                        <td class = "align-middle pl-2">{{date('d-m-Y', strtotime($ur->created_at))}}</td>
                                        <td class = "align-middle pl-2">@if(($ur->isUpdated  == 0))<span class = "btn btn-sm btn-danger">Not updated</span>@else <span class = "btn btn-sm btn-success">Updated</span> @endif</td>
                                        <td class = "align-middle pl-2">{{ $ur->users_7->name }}</td>
                                        <td class = "align-middle pl-2">{{date('d-m-Y', strtotime($ur->updated_at))}}</td>
                                        <td class = "align-middle pl-2"><a href ="{{ route('album',$ur->album_id) }}" class="btn btn-warning btn-sm"><i class="fas fa-eye"></i> View Album</a></td>
                                    </tr>
                                @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Declined Requests</div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped display" id = "table_three">
                                <thead>
                                    <tr>
                                        <th class = "align-middle pl-2">SL</th>
                                        <th class = "align-middle pl-2">Album Title</th>
                                        <th class = "align-middle pl-2">Requested by</th>
                                        <th class = "align-middle pl-2">Requested for</th>
                                        <th class = "align-middle pl-2">Request</th>
                                        <th class = "align-middle pl-2">Requested at</th>
                                        <th class = "align-middle pl-2">Declined by</th>
                                        <th class = "align-middle pl-2">Declined at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($user_request as $key => $ur)
                                @if ($ur->status == 2)
                                    <tr>
                                        <td class = "align-middle pl-2">{{ $key + 1 }}</td>
                                        <td class = "align-middle pl-2">{{ $ur->album->title }}</td>
                                        <td class = "align-middle pl-2">{{ $ur->users_6->name }}</td>
                                        <td class = "align-middle pl-2">@if(isset($ur->album_id))Edit Album @endif</td>
                                        <td class = "align-middle pl-2">{{ $ur->reason }}</td>
                                        <td class = "align-middle pl-2">{{date('d-m-Y', strtotime($ur->created_at))}}</td>
                                        <td class = "align-middle pl-2">{{ $ur->users_7->name }}</td>
                                        <td class = "align-middle pl-2">{{date('d-m-Y', strtotime($ur->updated_at))}}</td>
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
        $('#table_three').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'pageLength'
            ]
        });
    } );
</script>
@endpush
