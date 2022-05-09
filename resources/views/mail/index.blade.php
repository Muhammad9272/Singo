@extends('layouts.app')
@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
    <style type="text/css">
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -8px !important;

        }

        input[type="checkbox"] {
            width: 25px;
            height: 25px;
            margin-top: 5px;
        }
    </style>
@endpush

@section('content')
    @php
        $i = 1;
        $j = 1;
        $k = 1;

    @endphp
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <h1>Mail to Users</h1>
        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs pull-right d-flex justify-content-between" role="tablist">
                            <li class="nav-item ">
                                <a class="nav-link active" data-toggle="tab" href="#tab-ind-us">Indivisual User</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#tab-all">All Users</a>
                            </li>

                            @foreach ($plans as $plan)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab-{{ $plan->id }}">{{ $plan->title }} users</a>
                                </li>
                            @endforeach


                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-ind-ps">Indivisual Person</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body ">
                        @if (session('error'))
                            <p class="alert alert-danger text-center">
                                {{ session('error') }}
                            </p>
                        @elseif(session('success'))
                            <p class="alert alert-success text-center">
                                {{ session('success') }}
                            </p>
                        @endif
                        <div class="tab-content">
                            <div class="tab-pane fade show active " id="tab-ind-us" role="tabpanel">
                                <div class="col-md-8 col-lg-8 m-auto">
                                    <div class="card">
                                        <div class="card-header">Individual User</div>
                                        <div class="card-body">
                                            <div class="col-md-12">
                                                <form action="{{route('mail.individual')}}" method="POST">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <label class="col-md-3 form-control-label">User</label>
                                                        <div class="col-md-9">
                                                            <select name="user" id="user" class="form-control" required>
                                                                <option value="">Choose...</option>

                                                            </select>
                                                            <small>*Individual user search by name or email</small> <br>
                                                            @if ($errors->has('user'))
                                                                <span class="text-danger">{{ $errors->first('user') }}</span>
                                                            @endif
                                                            <script>document.getElementById('user').value = "{{ old('user') }}";</script>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-9 ml-auto">
                                                            <input type="submit" value="Search" class="btn btn-sm btn-primary">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade " id="tab-all" role="tabpanel">
                                <div class="col-md-12 col-lg-12 m-auto">

                                    <div class="card-header">
                                        <span class="float-left">All User</span>
                                        <span class="float-right"><a href="{{ route('cmd') }}" class="btn btn-sm btn-info" target="_blank">Run queue</a></span>
                                        <span class="float-right mr-4">
                                                <form action="{{route('mail.all')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="message" id="message" class="" value="This is a Test Mail from All-User Mail">
                                                    <input type="hidden" name="all_user[]" value="{{ auth()->user()->id }}">
                                                    <input type="hidden" name="title" value="Test Mail">
                                                    <input type="submit" value="Test Mail" class="btn btn-sm btn-success">
                                                </form>
                                            </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <form action="{{route('mail.all')}}" method="POST">
                                                @csrf
                                                <div class="row mb-3">
                                                    <div class="col-md-8 offset-md-2">
                                                        <div class="form-group">
                                                            <div class="mb-2">
                                                                <label for="title">Title<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="title">
                                                                <small>This title will be the subject of this mail</small>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="message">Mail
                                                                <span class="text-danger">*</span></label>
                                                            <textarea name="message" id="message" class="all_us_message form-control mb-2"></textarea>
                                                            <small>This content will go to the body of the template</small>
                                                            @if ($errors->has('message'))
                                                                <span class="text-danger">{{ $errors->first('message') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="submit" value="Send" class="btn btn-sm btn-primary" onclick="return confirm('Please make sure there is a running queue')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @foreach ($plans as $plan)
                                @php
                                    $i = 1;
                                @endphp
                                <div class="tab-pane fade " id="tab-{{ $plan->id }}" role="tabpanel">
                                    <div class="col-md-12 col-lg-12 m-auto">
                                        <div class="card-header">
                                            <span class="float-left">All {{ $plan->title }} User</span>
                                            <span class="float-right"><a href="{{ route('cmd') }}" class="btn btn-sm btn-info" target="_blank">Run queue</a></span>
                                            <span class="float-right mr-4">
                                                <form action="{{route('mail.dynamic_plan')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="message" id="message" class="" value="This is a Test Mail from All-{{ $plan->title }}-User Mail">
                                                    <input type="hidden" name="all_user[]" value="{{ auth()->user()->id }}">
                                                    <input type="hidden" name="title" value="Test Mail From {{ $plan->title }}">
                                                    <input type="submit" value="Test Mail" class="btn btn-sm btn-success">
                                                </form>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <div class="col-md-12">
                                                <form action="{{route('mail.dynamic_plan')}}" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                                    <div class="row mb-3">
                                                        <div class="col-md-8 offset-md-2">
                                                            <div class="form-group">
                                                                <div class="mb-2">
                                                                    <label for="title">Title<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="title">
                                                                    <small>This title will be the subject of this mail</small>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="message">Mail
                                                                    <span class="text-danger">*</span></label>
                                                                <textarea name="message" id="message" class="dynamic_msg form-control mb-2"></textarea>
                                                                <small>This content will go to the body of the template</small>
                                                                @if ($errors->has('message'))
                                                                    <span class="text-danger">{{ $errors->first('message') }}</span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="submit" value="Send" class="btn btn-sm btn-primary" onclick="return confirm('Please make sure there is a running queue')">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="tab-pane fade " id="tab-ind-ps" role="tabpanel">
                                <div class="col-md-12 col-lg-12 m-auto">
                                    <div class="card-header">Individual Person</div>
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <form action="{{route('mail.person')}}" method="POST">
                                                @csrf
                                                <div class="row mt-3">
                                                    <div class="col-md-8 offset-md-2">
                                                        <div class="form-group">
                                                            <div class="mb-2">
                                                                <label for="title">Title<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="title">
                                                                <small>This title will be the subject of this mail</small>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Mail <span class="text-danger">*</span></label>
                                                            <textarea name="message" value="" class="ind_us_message form-control mb-2"></textarea>
                                                            <small>This content will go to the body of the template</small>
                                                            @if ($errors->has('message'))
                                                                <span class="text-danger">{{ $errors->first('message') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="mb-2">Receivers Email
                                                                <input type="email" class="form-control" name="receivers_mail" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="submit" value="Send" class="btn btn-sm btn-primary">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" id="queue_table">
                    <div class="card-header">
                        <span class="float-left">Queue Table <a href="{{ route('mail') }}" class="btn btn-sm btn-primary">Refresh</a>  </span>
                        <span class="float-right"><a href="{{ route('mail.clear') }}" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#staticBackdrop">Clear Table</a></span>
                    </div>
                    <span class="text-center"> {{(count($job)*20)/60}} Minutes Remaining</span>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped nowrap" id="table_queue">
                                <thead>
                                <tr>
                                    <th class="align-middle text-center">SL</th>
                                    <th class="align-middle text-center">Id</th>
                                    <th class="align-middle text-center">Attempts</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($job as $queue)
                                    <tr>
                                        <td class="align-middle text-center">{{  $queue->id }}</td>
                                        <td class="align-middle text-center">{{ $queue->id }}</td>
                                        <td class="align-middle text-center">{{ $queue->attempts }}</td>
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

    <div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Clear Queue Table</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <i class="fa fa-times"></i>
                    </span>
                    </button>
                </div>
                <div class="modal-body">
                    Cleaning the queue table will delete all the queued-data. That means if there any running queued mail it will be stopped.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-success" data-dismiss="modal">Close</button>
                    <a type="button" class="btn btn-sm btn-danger" href="{{ route('mail.clear') }}">I Understood</a>
                </div>
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
    <script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        $("#user").select2();
    </script>
    <script>
        $(document).ready(function () {
            $('#table_one').DataTable({
                dom: 'Bfrtip',
                bPaginate: false,
                buttons: []
            });
            $('#table_two').DataTable({
                dom: 'Bfrtip',
                bPaginate: false,
                buttons: []
            });
            $('#table_three').DataTable({
                dom: 'Bfrtip',
                bPaginate: false,
                buttons: []
            });
            $('#table_queue').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pageLength'
                ]
            });

        });
    </script>
    <script>ClassicEditor
            .create(document.querySelector('.ind_us_message'), {

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
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side',
                        'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                },
                licenseKey: '',


            })
            .then(editor => {
                window.editor_ind_us_message = editor;
            })
            .catch(error => {
                console.error('Oops, something went wrong!');
                console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                console.warn('Build id: fi8kzgqtl52b-5fl88maz3hkc');
                console.error(error);
            });
    </script>
    <script>ClassicEditor
            .create(document.querySelector('.all_us_message'), {

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
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side',
                        'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                },
                licenseKey: '',


            })
            .then(editor => {
                window.editor_all_us_message = editor;
            })
            .catch(error => {
                console.error('Oops, something went wrong!');
                console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                console.warn('Build id: fi8kzgqtl52b-5fl88maz3hkc');
                console.error(error);
            });
    </script>
    <script>
        $(".dynamic_msg").each(function () {

            ClassicEditor.create(this, {

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
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side',
                        'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                },
                licenseKey: '',


            })
                .then(editor => {
                    window.editor_dynamic_msg = editor;
                })
                .catch(error => {
                    console.error('Oops, something went wrong!');
                    console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                    console.warn('Build id: fi8kzgqtl52b-5fl88maz3hkc');
                    console.error(error);
                });

        });
    </script>
@endpush
