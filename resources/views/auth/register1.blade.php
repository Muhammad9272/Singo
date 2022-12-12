<!DOCTYPE html>
<html lang="en">
<head>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MGSSDXG');</script>
<!-- End Google Tag Manager -->

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} | Registration Page</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <style>
        .login-page, .register-page {
            background-color: #232323 !important;
            borer: 1px solid #E8F0FE !important;
        }

        .logo_image {
            height: 200px !important;
            width: auto;
        }

        input.transparent-input {
            background-color: rgba(0, 0, 0, 0) !important;
        }

        .input-group-text {
            color: #E8F0FE !important;
            background-color: rgba(0, 0, 0, 0) !important;
        }

        .white-color {
            color: #E8F0FE !important;
        }

        .custom-color {
            color: #5D5D5D !important;
        }

        .form-control {
            color: #ffffff !important;
        }
    </style>


</head>
<body class="hold-transition register-page">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MGSSDXG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<div class="register-box">
    <div class="register-logo">
        <img src="{{ asset('image/singo-logo.png') }}" class="logo_image" alt="Singo.io">
    </div>

    <div class=" mb-4">
        <div class="">
            <p class="custom-color">Register a new membership</p>

            <form method="post" action="{{ route('register') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="text"
                           name="name"
                           class="transparent-input form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="First & Last Name">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="text"
                           name="artistName"
                           class="transparent-input form-control @error('artistName') is-invalid @enderror"
                           value="{{ old('artistName') }}"
                           placeholder="Artist Name">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user-secret"></span></div>
                    </div>
                    @error('artistName')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="transparent-input form-control @error('email') is-invalid @enderror"
                           placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                           name="password"
                           class="transparent-input form-control @error('password') is-invalid @enderror"
                           placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                           name="password_confirmation"
                           class="transparent-input form-control"
                           placeholder="Retype password">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>


                {{-- <div>
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                </div> --}}

                <div class="row">
                    <div class="col-12">
                        <div class="icheck-primary">
                            <input type="checkbox" id="receiveEmail" name="receiveEmail">
                            <label for="receiveEmail" class="custom-color d-inline">
                                Yes, I would like to receive product information and offers from Singo.io (optional)
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="icheck-primary">
                            <input type="checkbox" id="artistNamecheck" name="artistNamecheck" required>
                            <label for="artistNamecheck" class="custom-color d-inline">
                                The artist name can't be changed after registration
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="agree"
                                   data-error="You must need to agree our terms and conditions." required>
                            <label for="agreeTerms" class="custom-color">
                                I agree to the <a href="https://singo.io/terms.html">terms & service.</a>
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4 mt-2">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->

    <!-- /.form-box -->
</div>
<!-- /.register-box -->

<script src="{{ mix('js/app.js') }}" defer></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>
</html>
