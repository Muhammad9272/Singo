@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <h1>{{ $title }}</h1>
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
                                            <input type="date" name="date" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <x-form.select-plan name="plan" value="{{ request('plan') }}"/>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <x-form.select-genre name="genre" value="{{ request('genre') }}"/>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <input type="text" class="form-control" placeholder="Search..."
                                                   name="searchQuery" value="{{ request('searchQuery') }}">
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-success">Search</button>
                                            <button class="btn btn-warning">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ $title }}
                        </h4>
                      
                        <div class="card-tools">
                        @if(isset($showDeleteFile))
                            <button id="showDeleteConfirmBox" class="btn btn-danger">Delete Files
                            </button>
                        @endif
                            <a href="#modal-batch-mark-album" data-toggle="modal" class="btn btn-success">Batch Mark</a>
                        </div>
                    </div>
                    <div class="card-body ">

                        <table class="table display nowrap table-responsive" id="table">
                            <thead>
                            <tr>
                                <th class="align-middle pl-2">
                                    <input type="checkbox" id="checkbox-select-all">
                                </th>
                                <th class="align-middle pl-2">Thumb</th>
                                <th class="align-middle pl-2">Name</th>
                                <th class="align-middle pl-2">Upc</th>
                                <th class="align-middle pl-2">Genre</th>
                                <th class="align-middle pl-2">Creator</th>
                                <th class="align-middle pl-2">Artist</th>
                                <th class="align-middle pl-2">Subscription</th>
                                <th class="align-middle pl-2">Release Date</th>
                                @if(isset($showDeleteFile))
                                <th class="align-middle pl-2">File Deleted</th>
                                @endif
                                <th class="align-middle pl-2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($albums as $album)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="checkbox-album" data-album-id="{{ $album->id }}">
                                    </td>
                                    <td class = "align-middle pl-2"><img width="50" height="50" src="{{ $album->cover_route }}"></td>
                                    <td class = "align-middle pl-2">{{ $album->title }}</td>
                                    <td class = "align-middle pl-2">@if(!is_null($album->upc)) {{ $album->upc }} @else {{ __('Not set yet') }} @endif</td>
                                    <td class = "align-middle pl-2">{{ $album->genre->name }}</td>
                                    <td class = "align-middle pl-2">{{ $album->name }}</td>
                                    <td class = "align-middle pl-2">{{ $album->artistName }}</td>
                                    <td class = "align-middle pl-2">
                                    @php
                                        $plans = App\Models\Plan::findOrFail($album->plan);
                                    @endphp
                                        <div class="btn align-middle" style="background-color: {{ $plans->show_button }}"><span class="text-white">{{ $plans->title }}</span></div>
                                    </td>
                             
                                  
                                  
                                    <td class="align-middle pl-2">@if(isset($album->release)){{date('d-m-Y', strtotime($album->release))}}@else
                                    Not Set @endif</td>
                                    @if(isset($showDeleteFile))
                                    <td class="align-middle pl-2">
                                    @if ($album->song_deleted == 'Yes')
                                        <span class="badge badge-danger">Deleted
                                        </span>
                                    @endif
                                    </td>
                                    @endif
                                    <td class="align-middle pl-2">
                                        <span class="btn-group">
                                            <a href="{{ route('album.edit', $album->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="{{ route('album', $album->id) }}" class="btn btn-sm btn-success"><i class="fas fa-eye"></i> View</a>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        {{ $albums->Oneachside(2)->links('pagination.default') }}
                        @if(isset($pageCount))
                        <div class="select-checkbox" style="width:40px;">
                            <select name="selecteddata" id="selecteddata">
                                <option value="10" @if($pageCount == 10) selected @endif >10</option>
                                <option value="50" @if($pageCount == 50) selected @endif >50</option>
                                <option value="100" @if($pageCount == 100) selected @endif >100</option>
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-batch-mark-album" tabindex="-1" role="dialog"
         aria-labelledby="modal-batch-mark-album-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-bulk-batch-album-label">Batch Mark</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="form-group">
                            <label for="">Status</label>
                            <select id="batch-mark-status" class="form-control">
                                @foreach(\App\Models\Album::STATUES as $status => $text)
                                    <option value="{{ $status }}">{{ $text }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-submit-batch-mark-form" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-delete-files"  role="dialog"
         aria-labelledby="modal-delete-files" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-bulk-batch-album-label">Delete Files</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <p>Are you sure you want to delete these files ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-submit-file-delete" class="btn btn-primary">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $('#checkbox-select-all').click(function (e) {
            $('.checkbox-album').prop('checked', this.checked)
        });

        $('.checkbox-album').change(function (e) {
            if (this.checked) {
                $('#checkbox-select-all').prop('checked', true);
            } else {
                if (($('.checkbox-album:checked').length === 0)) {
                    $('#checkbox-select-all').prop('checked', false);
                }
            }
        })

        $('#selecteddata').change(function () {
            var limit = $('#selecteddata').val();
            var dataval = "?albums="+limit;
            window.location.href = dataval;
        });
        //file delete code 
        $("#showDeleteConfirmBox").click(function(){
            $("#modal-delete-files").modal("show");
        })
        $('#btn-submit-file-delete').click(function () {
            $("#modal-delete-files").modal("show");
            let checkedAlbums = [];
           
            $('.checkbox-album:checked').each((index, elm) => {
                checkedAlbums.push(parseInt(elm.getAttribute('data-album-id')))
            });
            console.log(checkedAlbums);
            if(checkedAlbums.length == 0 ){
                toastr.error("Please select an album");
                return false;

            }
            $("#btn-submit-file-delete").html('Deleting...');
            $("#btn-submit-file-delete").attr('disabled','disabled');
            $.ajax({
                url			: "{{ route('admin.delete.data') }}",
                type		: "POST",
                dataType	: "JSON",
                data  		: {
                    albums: checkedAlbums,
                    status: $('#batch-mark-status').val(),
                },
                "success"    : function(data, status){
                   

                    if(status == 'success') {
                        toastr.success("Files are deleted");
                        $("#btn-submit-file-delete").html('Delete Files');
                        $("#btn-submit-file-delete").removeAttr('disabled');
                        setTimeout(function () {
                            location.reload()
                        }, 1000)
                    }
                },
                "error"	     : function(error){
                    $("#btn-submit-file-delete").html('Delete Files');
                    $("#btn-submit-file-delete").removeAttr('disabled');
                    toastr.error(error.responseJSON.message)
                }

            });

        });


        $('#btn-submit-batch-mark-form').click(function () {
            let checkedAlbums = [];

            $('.checkbox-album:checked').each((index, elm) => {
                checkedAlbums.push(parseInt(elm.getAttribute('data-album-id')))
            });

            $.ajax({
                url			: "{{ route('album.batch-mark') }}",
                type		: "POST",
                dataType	: "JSON",
                data  		: {
                    albums: checkedAlbums,
                    status: $('#batch-mark-status').val(),
                },
                "success"    : function(data, status){
                    $('#modal-batch-mark-album').modal('hide');

                    if(status == 'success') {
                        toastr.success(data.message);

                        setTimeout(function () {
                            location.reload()
                        }, 1000)
                    }
                },
                "error"	     : function(error){
                    toastr.error(error.responseJSON.message)
                }
            });
        });

    </script>
@endpush
