<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MC38RF4');</script>
    <!-- End Google Tag Manager -->

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
        integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
        crossorigin="anonymous" />

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        .login-page, .register-page {
            background-color: #232323 !important;
            borer: 1px solid #E8F0FE !important;
        }
        .logo_image{
            height: 200px !important;
            width: auto;
        }
        input.transparent-input{
            background-color:rgba(0,0,0,0) !important;
        }
        .input-group-text {
            color: #E8F0FE !important;
            background-color:rgba(0,0,0,0) !important;
        }
        .white-color{
            color: #E8F0FE !important;
        }
        .custom-color{
            color: #5D5D5D !important;
        }
        .form-control {
            color: #ffffff !important;
        }
    </style>

</head>

<body class="hold-transition login-page">
	<!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MC38RF4"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="login-box">
        <div class="login-logo">
            <img src="{{ asset('image/singo-logo.png') }}" class="logo_image" alt="Singo.io">
        </div>

        <div class="">
            <div class="">
                <p class="custom-color">You are only one step a way from your new password, recover your password now.
                </p>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    @php
                        if (!isset($token)) {
                            $token = \Request::route('token');
                        }
                    @endphp

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="input-group mb-3">
                        <input type="email" name="email"
                            class="transparent-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                        @if ($errors->has('email'))
                            <span class="error invalid-feedback">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password"
                            class="transparent-input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                        @if ($errors->has('password'))
                            <span class="error invalid-feedback">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="transparent-input form-control"
                            placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <span class="error invalid-feedback">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ route('login') }}">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>

    </div>

    <script src="{{ mix('js/app.js') }}" defer></script>

</body>

</html>
