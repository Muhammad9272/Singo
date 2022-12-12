@extends('layouts.app')

@push('page_css')
 
    <style>
        .picture-container {
            position: relative;
            text-align: center;
        }

        .picture {
            cursor: pointer;
            position: relative;
            height: 150px;
            width: 150px;
            color: #FFFFFF;
            margin: 0px auto;
            transition: all 0.2s;
            -webkit-transition: all 0.2s;
        }

        .picture input[type="file"] {
            cursor: pointer;
            display: block;
            height: 100%;
            width: 100%;
            opacity: 0 !important;
            position: absolute;
            top: 0;
        }

        .picture-src {
            width: 100%;
        }

        .img-upload-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 100%;
            opacity: 0;
            transition: .5s ease;
            background-color: rgba(255, 255, 255, 0.3);
        }
        .img-upload-overlay-inner {
            height: 100%;
            width: 100%;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .picture:hover .img-upload-overlay {
            opacity: 1;
        }
        .note-editable{
            /*background: white !important; */
            color: white !important;
        }
        .note-editable p, h1, h2, h3, h4{
            color: white; !important;
        }

    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-12">
            <div class="card mt-5">
                <div class="card-header">
                    <span class="float-left">
                        <h4>Add Reward</h4>
                    </span>
                    <span class="float-right">
                        <a href="{{ route('admin.rewards.index') }}" class="btn btn-info btn-sm">Back</a>
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
                            <form action="{{ route('admin.rewards.store') }}" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label"> Title <span class="text-danger">*</span> </label>
                                    <div class="col-md-9">
                                        <input type="text" name="title" value="{{ old('title') }}" class="form-control form-control-success" required>

                                        @if ($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label"> Sub Title <span class="text-danger">*</span> </label>
                                    <div class="col-md-9">
                                        <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="form-control form-control-success" required>

                                        @if ($errors->has('subtitle'))
                                            <span class="text-danger">{{ $errors->first('subtitle') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label"> Points <span class="text-danger">*</span> </label>
                                    <div class="col-md-9">
                                        <input type="number" step="0.0001" name="points" value="{{ old('points') }}" class="form-control form-control-success" required>

                                        @if ($errors->has('points'))
                                            <span class="text-danger">{{ $errors->first('points') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label"> Rank <span class="text-danger">*</span> </label>
                                    <div class="col-md-9">
                                        <input type="text" name="rank" value="{{ old('rank') }}" class="form-control form-control-success" required>

                                        @if ($errors->has('rank'))
                                            <span class="text-danger">{{ $errors->first('rank') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label"> Detail <span class="text-danger">*</span> </label>
                                    <div class="col-md-9">
                                        <textarea class="form-control sumernote" name="detail"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">   </label>
                                    <div class="col-md-9">
                                       <div class="form-check">
                                          <input class="form-check-input" type="checkbox" name="is_physical" value="1" id="flexCheckChecked" checked>
                                          <label class="form-check-label" for="flexCheckChecked">
                                           Physical Reward
                                          </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label"> Reward Photo <span class="text-danger">*</span> </label>
                                    <div class="col-md-9">                                     
                                        <div class="picture">
                                                <img class="img-responsive  profile-img" id="wizardPicturePreview" src="{{ asset('image/icons/rwig.png') }}" alt="">                                   
                                            <div class="img-upload-overlay">
                                                <div class="img-upload-overlay-inner">
                                                    <i class="fas fa-camera text-xl"></i>
                                                </div>
                                            </div>
                                            <input type="file" id="wizard-picture" class="" name="photo" accept="image/*">
                                        </div>
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

        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>

                 $(document).ready(function() {
                    $('.sumernote').summernote({
                        height: 250,
                        // codemirror: { "theme": "darkly" }, 
                    });
                  });
          </script>

    <script>
        $(document).ready(function () {
            $("#wizard-picture").change(function () {
                readURL(this);
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


    </script>
@endpush

