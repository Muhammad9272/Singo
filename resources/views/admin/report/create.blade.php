@extends('layouts.app')

@push('page_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
          rel="stylesheet"/>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-12">
                <div class="card mt-5">
                    <div class="card-header">
                    <span class="float-left">
                        <h4>Add Report</h4>
                    </span>
                        <span class="float-right">
                        <a href="{{ route('admin.user', $user_info->id) }}" class="btn btn-info btn-sm">Back</a>
                    </span>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <form action="{{ route('admin.report.store')}}" enctype="multipart/form-data"
                                      method="POST" class="form-horizontal">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$user_info->id}}">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label"> Date <span
                                                class="text-danger">*</span> </label>
                                        <div class="col-md-9">
                                            <input type="date" name="date" value="{{ old('date') }}"
                                                   class="form-control form-control-success" required>
                                            @if ($errors->has('date'))
                                                <span class="text-danger">{{ $errors->first('date') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Streams <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <input type="number" name="streams" value="{{ old('streams') }}"
                                                   class="form-control form-control-success" step=any required>
                                            @if ($errors->has('streams'))
                                                <span class="text-danger">{{ $errors->first('streams') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Store <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <select name="store" id="store" class="form-control form-control-success"
                                                    required>
                                                <option selected hidden value="">Choose...</option>
                                                @foreach ($store as $st)
                                                    <option value="{{$st->id}}">{{$st->title}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('store'))
                                                <span class="text-danger">{{ $errors->first('store') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Money <span
                                                class="text-danger">*</span> </label>
                                        <div class="col-md-9">
                                            <input type="number" name="money" value="{{ old('money') }}" step=any
                                                   class="form-control form-control-success" required>
                                            @if ($errors->has('money'))
                                                <span class="text-danger">{{ $errors->first('money') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cover">Attach CSV
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="mb-2">
                                            <input type="file" class="" id="file" data-name="file" name="file">
                                            @if ($errors->has('file'))
                                                <span class="text-danger">{{ $errors->first('file') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-9 ml-auto">
                                            <input type="submit" value="Submit" class="btn btn-primary">
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
@endsection

@push('page_scripts')

    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script>
        // Get a reference to the file input element
        const inputElement = document.getElementById('file');

        // Create a FilePond instance
        const pond = FilePond.create(inputElement, {});

        var name = inputElement.dataset.name;
        var _url = ("{{ route('ajax.upload', ['name']) }}");
        var __url = _url.replace('name', name);

        pond.setOptions({
            server: {
                url: __url,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
        });

        for (let el of document.querySelectorAll('.filepond--credits')) el.style.visibility = 'hidden';

    </script>
@endpush
