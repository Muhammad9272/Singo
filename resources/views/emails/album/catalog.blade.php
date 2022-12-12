@extends('layouts.app')
@push('page_css')
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">

@endpush
@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <h1>Your albums</h1>
            
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Your albums</div>
                    <div class="card-body table-responsive">
                    @if(session('success'))
                        <p class="alert alert-success text-center">
                            {{ session('success') }}
                        </p>
                    @elseif(session('error'))
                        <p class="alert alert-danger text-center">
                            {{ session('error') }}
                        </p>
                    @endif
                    
                        <table class="table display nowrap"  id = "table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class = "align-middle pl-2">Name</th>
                                    <th class = "align-middle pl-2">Genre</th>
                                    <th class = "align-middle pl-2">Album Status</th>
                                    <th class = "align-middle pl-2">Request Status</th>
                                    <th class = "align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($albums as $album)
                                <tr>
                                    <td class = "align-middle pl-2"><img width="50" height="50" src="{{ \Illuminate\Support\Facades\Storage::url('albums/'.$album->id.'/'.$album->cover) }}"></td>
                                    <td class = "align-middle pl-2">{{ $album->title }}</td>
                                    <td class = "align-middle pl-2">{{ $album->genre->name }}</td>
                                    <td class = "align-middle pl-2"><span class="btn btn-sm btn-{{ $album->getStatusColor() }}">{{ $album->getStatusText() }}</span></td>
                                    
                                @php    $request = App\Models\UserRequest::Where('album_id',$album->id)->orderby('created_at','DESC')->take(1)->get(); @endphp
                                    @forelse($request as $rq)
                                    <td class = "align-middle pl-2">
                                        @if($rq->status == 0  && $rq->isUpdated == 0)
                                            <span class = "btn btn-sm btn-warning">Pending</span>
                                            @endif
                                        @if($rq->status == 1 && $rq->isUpdated == 0)
                                            <span class = "btn btn-sm btn-success">Accepted</span>
                                            @endif
                                        @if($rq->status == 1 && $rq->isUpdated == 1)
                                            <span class = "btn btn-sm btn-success">Updated</span>
                                            @endif
                                        @if($rq->status == 2  && $rq->isUpdated == 0)
                                            <span class = "btn btn-sm btn-danger">Declined</span>
                                        @endif
                                    </td>
                                    @empty
                                    <td class = "align-middle pl-2">                                       
                                            <span class = "btn btn-sm btn-secondary">Not Requested</span>                                      
                                    </td>
                                    @endforelse
                                    <td class = "align-middle pl-2">
                                        <div class="btn-group">
                                            <a href="{{ route('album', $album->id) }}" class="btn btn-sm btn-dark"><i class="fas fa-eye"></i> View</a>
                                            <a href="{{ route('album.edit',$album->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="{{ route('album.request', $album->id) }}" class="btn btn-sm btn-danger"><i class="fas fa-edit"></i> Request</a>
                                        </div>
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
@endsection


@push('page_scripts')
<script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/buttons.html5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#table').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                 'pageLength'
            ]
        });
    } );
</script>

@endpush