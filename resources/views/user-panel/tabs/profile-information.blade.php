@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/dropzone-5.7.0/dist/min/dropzone.min.css') }}">
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
    </style>
@endpush

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div>
    <form action="{{ route('user.setting.update') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="picture-container">
            <div class="picture">
                @if(isset(auth()->user()->profile_picture))
                    <img class="img-responsive img-circle profile-img" id="wizardPicturePreview" src="{{ auth()->user()->profile_picture }}" alt="">
                @else
                    <img class="img-responsive img-circle profile-img" id="wizardPicturePreview" src="{{ asset('image/user.png') }}" alt="">
                @endif
                <div class="img-upload-overlay">
                    <div class="img-upload-overlay-inner">
                        <i class="fas fa-camera text-xl"></i>
                    </div>
                </div>
                <input type="file" id="wizard-picture" class="" name="file" accept="image/*">
            </div>
        </div>
        <h3 class="text-center mt-4 mb-4" id="">{{ auth()->user()->name }}</h3>

        <div class="row pt-4">
            <div class="col-md-10 m-auto">
                <div class="from-group">
                    <label for="">Full Name</label>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" id="f_name" name="f_name" value="{{ auth()->user()->f_name }}" placeholder="First Name" required>
                        @if ($errors->has('f_name'))
                            <span class="text-danger text-left">{{ $errors->first('f_name') }}</span>
                        @endif

                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" id="l_name" name="l_name" value="{{auth()->user()->l_name}}" placeholder="Last Name">
                        @if ($errors->has('l_name'))
                            <span class="text-danger text-left">{{ $errors->first('l_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="{{auth()->user()->email}}">
                    @if ($errors->has('email'))
                        <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="address_1">Address</label>
                    <input type="text" class="form-control" id="address_1" name="address_1" value="{{auth()->user()->address_1}}" placeholder="1234 Main St">
                    @if ($errors->has('address_1'))
                        <span class="text-danger text-left">{{ $errors->first('address_1') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="address_2">Address 2</label>
                    <input type="text" class="form-control" id="address_2" name="address_2" value="{{auth()->user()->address_2}}" placeholder="Apartment, studio, or floor">
                    @if ($errors->has('address_2'))
                        <span class="text-danger text-left">{{ $errors->first('address_2') }}</span>
                    @endif
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{auth()->user()->city}}">
                        @if ($errors->has('city'))
                            <span class="text-danger text-left">{{ $errors->first('city') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{auth()->user()->state}}" placeholder="">
                        @if ($errors->has('state'))
                            <span class="text-danger text-left">{{ $errors->first('state') }}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control" id="zip" name="zip" value="{{auth()->user()->zip}}">
                        @if ($errors->has('zip'))
                            <span class="text-danger text-left">{{ $errors->first('zip') }}</span>
                        @endif
                    </div>
                </div>

                <div class="text-center pt-4">
                    <button type="submit" class="btn btn-dark-purple btn-lg px-6">Update Profile</button>
                </div>
            </div>
        </div>
    </form>
</div>
