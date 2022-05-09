@extends('layouts.app')
@push('page_css')
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <h1>Send mail to {{$user_details->name}}</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Individual User Mail</div>
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


                    <div class="col-md-12">
                        <form action="{{route('mail.user')}}" method="POST" >
                            @csrf
                            <input type="hidden" name ="user_id" value="{{$user_details->id}}">
                            <div class="row mt-3">
                                <div class="col-md-8 offset-md-2">
                                    <label for="">Sending Mail to {{$user_details->name}} at {{$user_details->email}}</label><br>
                                    <div class="form-group">
                                        <div class ="mb-2">
                                            <label for="title">Title<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name = "title">
                                            <small>This title will be the subject of this mail</small>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label for="message">Mail <span class="text-danger">*</span></label>
                                        <textarea name="message" id="message" class="ind_user_mail form-control mb-2" ></textarea>
                                        <small>This content will go to the body of the template</small>
                                        @if ($errors->has('message'))
                                            <span class="text-danger">{{ $errors->first('message') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <div class ="mb-2">Receivers Email   <input type="email" class="form-control" name = "receivers_mail" value = "{{$user_details->email}}" readonly></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Send" class="btn btn-primary">
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
@endsection


@push('page_scripts')
<script src="{{ asset('assets/vendor/ckeditor5_more/build/ckeditor.js') }}"></script>
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
<script>ClassicEditor
    .create( document.querySelector( '.ind_user_mail' ), {

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



    } )
    .then( editor => {
        window.editor_ind_us_message = editor;
    } )
    .catch( error => {
        console.error( 'Oops, something went wrong!' );
        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
        console.warn( 'Build id: fi8kzgqtl52b-5fl88maz3hkc' );
        console.error( error );
    } );
</script>

@endpush
