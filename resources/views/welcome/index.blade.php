@extends('layouts.app')
@push('page_css')
<style>
    .status {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    }

    .status input {
    opacity: 0;
    width: 0;
    height: 0;
    }

    .status_slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #f30049;
    -webkit-transition: .4s;
    transition: .4s;
    }

    .status_slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    input:checked + .status_slider {
    background-color: #23f381;
    }

    input:focus + .status_slider {
    box-shadow: 0 0 1px #23f381;
    }

    input:checked + .status_slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    /* Rounded sliders */
    .status_slider.round {
    border-radius: 34px;
    }

    .status_slider.round:before {
    border-radius: 50%;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid" id="close_class">
        <div class="row content-header mb-2">
            <h1>Welcome alert</h1>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="float-left">
                            <h4>All alert</h4>
                        </span>
                        <span class="float-right">
                            <a href="javascript:void(0);" class="btn btn-sm create_modal btn-primary">Add new alert</a>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="table-responsive">
                                <table class="table table-dark table-striped display nowrap" id = "tableOne">
                                    <thead>
                                        <tr>
                                            <th class = "align-middle text-center pl-2">#SL</th>
                                            <th class = "align-middle text-center pl-2">Name</th>
                                            <th class = "align-middle text-center pl-2">Created at</th>
                                            <th class = "align-middle text-center pl-2">Created by</th>
                                            <th class = "align-middle text-center pl-2">Status</th>
                                            <th class = "align-middle text-center pl-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($welcome as $key1 =>$wc)
                                            <tr>
                                                <td class = "align-middle text-center pl-2">
                                                    {{ $key1+1 }}
                                                </td>
                                                <td class = "align-middle text-center pl-2">
                                                    {{ $wc->name }}
                                                </td>
                                                <td class = "align-middle text-center pl-2">
                                                    {{  date('d-M-y', strtotime($wc->created_at)) }}
                                                </td>
                                                <td class = "align-middle text-center pl-2">
                                                    {{  $wc->created_by }}
                                                </td>
                                                <td class = "align-middle text-center pl-2">
                                                    <div class="form-group" style="display: flex;justify-content: space-around;align-items: flex-end;">
                                                        <label class="status mb-0" style = "margin-top: 3px;">
                                                            <input type="checkbox" class="checkbox" name = "edit_status" data-id="{{ $wc->id }}" value = "1" id = "edit_status" @if($wc->status==1) checked @endif>
                                                            <span class="status_slider round" ></span>
                                                        </label>
                                                    </div>

                                                    <a href="{{ route('welcome.status', $wc->id) }}" class="d-none status_{{ $wc->id }}"></a>
                                                </td>
                                                <td class="align-middle text-center pl-2">
                                                    <div class="btn-group">
                                                        <a href="javascript: void(0);" data-id="{{ $wc->id }}"
                                                            class="btn btn-sm btn-dark show_modal"><i
                                                                class="fas fa-eye "></i>
                                                            View</a>
                                                        <a href="javascript: void(0);" data-id="{{ $wc->id }}"
                                                            class="btn btn-sm btn-info edit_modal"><i
                                                                class="fas fa-edit"></i>
                                                            Edit</a>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 <input type="hidden" name="check_click" id="check_click" value="0">

    <!-- Button trigger modal -->
    <button type="button" id="btnShowAlert" class="btn btn-primary d-none" data-toggle="modal" data-target="#showAlert"></button>

    <!-- Modal -->
    <div class="modal fade" id="showAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModelLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            <div class="modal-body">
                <div class="showDiv">

                </div>
                <div>
                    <div class="mt-2">
                        <button class="btn btn-danger" style="width: 49%;" data-dismiss="modal">Close</button>
                        <a href="javascript: void(0);" class="btn btn-primary" data-dismiss="modal" style="width: 49%;">Don't show this again</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>




       <!-- Modal -->
       <button type="button" id="showCreate" class="btn btn-primary d-none" data-toggle="modal" data-target="#create_alert"></button>
       <div class="modal fade" id="create_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create new alert</h5>
                        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="" name="" action="{{ route('welcome.create') }}">
                            @csrf
                                <div class="form-group ">
                                    <label for="name">Alert Name</label>
                                    <input type="text" name="name" value="" id="name" class="form-control form-control-sm" required>
                                    <small id="name_note" class="form-text text-muted"></small>
                                </div>
                                <div class="form-group ">
                                    <label for="content">Show Content</label>
                                    <textarea name="content" value="" id="content" class="form-control form-control-sm" ></textarea>
                                    <small id="content_note" class="form-text text-muted"></small>
                                </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger close-btn" style="width: 20%;" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary w-75">Create New Alert</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>



       <!-- Modal -->
       <button type="button" id="showEdit" class="btn btn-primary d-none" data-toggle="modal" data-target="#edit_alert"></button>
       <div class="modal fade" id="edit_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit alert</h5>
                        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('welcome.edit.save') }}">
                            @csrf
                            <input type="hidden" name="id" id="id" value="">
                                <div class="form-group ">
                                    <label for="edit_name">Alert Name</label>
                                    <input type="text" name="edit_name" value="" id="edit_name" class="form-control form-control-sm" required>
                                    <small id="edit_name_note" class="form-text text-muted"></small>
                                </div>
                                <div class="form-group ">
                                    <label for="edit_content">Show Content</label>
                                    <textarea name="edit_content" value="" id="edit_content" class="form-control form-control-sm" ></textarea>
                                    <small id="edit_content_note" class="form-text text-muted"></small>
                                </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger close-btn" style="width: 20%;" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary w-75">Edit Alert</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>



@endsection

@push('page_scripts')
<script src="{{ asset('assets/vendor/ckeditor5_more/build/ckeditor.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/buttons.html5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#tableOne').DataTable({
            dom: 'Bfrtip',
            bPaginate: false,
            searching: false,
            buttons: [
            ]
        });

        ClassicEditor.create( document.querySelector( '#content' ), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'fontBackgroundColor',
                    'fontColor',
                    'fontFamily',
                    'fontSize',
                    'highlight',
                    'link',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'alignment',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            language: 'en',
                licenseKey: '',
        } )
        .then( editor => {
            window.editor2 = editor;
        });
        $('.show_modal').click(function () {
            var id = $(this).data('id');
            $('#id').val(id);
            if(id != null){
                let _url = ("{{ route('welcome.edit', ['id']) }}");
                let __url = _url.replace('id', id);
                $.ajax({
                    url: __url,
                    method: "GET",
                    success: function (response) {
                        $('.showDiv').html(response.content);
                        $('#showModelLabel').html(response.name);
                        $('#btnShowAlert').click();
                    }
                });
            }
        });

        $('.checkbox').click(function () {
            var click_id = $(this).data('id');
            var check_id = '.status_'+click_id;
            var href = $(check_id).attr('href');
            window.location.href = href;
            $(check_id).trigger('click');

        })

        $('.create_modal').click(function() {
            $('#showCreate').click();
        });

        $('.edit_modal').click(function() {
            let check_val = $('#check_click').val();
            if( check_val >= 1 )
            {
                editor1.destroy();
            }
            else{
                $('#check_click').val( check_val+1 );
            }
            $('#showEdit').click();
            var id = $(this).data('id');
            $('#id').val(id);
            if(id != null){
                let _url = ("{{ route('welcome.edit', ['id']) }}");
                let __url = _url.replace('id', id);
                $.ajax({
                    url: __url,
                    method: "GET",
                    success: function (response) {
                        $('#id').val(response.id);
                        $('#edit_name').val(response.name);
                        ClassicEditor.create( document.querySelector( '#edit_content' ), {
                            toolbar: {
                                items: [
                                    'heading',
                                    '|',
                                    'bold',
                                    'italic',
                                    'fontBackgroundColor',
                                    'fontColor',
                                    'fontFamily',
                                    'fontSize',
                                    'highlight',
                                    'link',
                                    '|',
                                    'bulletedList',
                                    'numberedList',
                                    '|',
                                    'alignment',
                                    '|',
                                    'undo',
                                    'redo'
                                ]
                            },
                            language: 'en',
                            licenseKey: '',
                        } )
                        .then( editor => {
                            window.editor1 = editor;
                            editor1.setData(response.content);
                        } );
                    }
                });
            }

        });


    });
</script>

@endpush
