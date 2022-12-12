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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('css/login-reg.css') }}" rel="stylesheet">

    {{-- <style>
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
    </style> --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body class="sign-forms" style="background-image: url({{ asset('image/loginbg.svg') }});" >
	<!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MC38RF4"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
      


     <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light pl-0 ">
              {{-- <a class="navbar-brand" href="#">Navbar</a> --}}
              
                    <a href="{{ route('home') }}" class="navbar-brand brand-link " style="">
                            <div class="d-flex justify-content-center custt-loogo">
                                <img src="{{ asset('image/login_logo.webp') }}" alt="Singo.io Logo" class="brand-image m-0"
                                     style="">
                            </div>
                    </a>
                    <button class="navbar-toggler nav-tog-btn" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>

                   
                

                <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                      <a class="nav-item nav-link active" href="https://singo.io/index.html#music">Music Distribution <span class="sr-only">(current)</span></a>
                      <a class="nav-item nav-link" href="https://singo.io/index.html#pricing">Pricing</a>
                      <a class="nav-item nav-link" href="">Blog</a>
                      <a class="nav-item nav-link" href="https://singo.io/index.html#video-sec">Video</a>
                      <a class="nav-item nav-link" href="https://singo.io/about.html ">About Us</a>
                      <a class="nav-item nav-link" href="https://singo.io/index.html#faq-sec">Faq</a>
                      <a class="nav-item nav-link" href="https://singo.io/support.html">Support</a>
                      <a class="nav-item  singo-btn ml-3 mr-3 mb-2 cus-padding" href="{{ route('login') }}">LOG IN</a>
                      <a class="nav-item  singo-btn secondarybgcolor mb-2 cus-padding" href="{{ route('register') }}" style="color: black!important">REGISTER</a>
                    </div>
                </div>
            </nav>  
        </div>
    </header>

    <!-- /.login-box -->

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-xl-11 mx-auto mt-50">
                <div class="card flex-row my-5 shadow rounded-3 overflow-hidden login-card" >          
                    <div class="card-body p-4 p-sm-5">
                        <h3>Welcome <span class="secondarycolor">Back</span></h3>
                        <p class="card-title mb-5 fw-light fs-5">Welcome back! Please enter your details.</p>
                        <form method="post" action="{{ url('/login') }}">
                            @csrf
                          
                            <div class="form-floating mb-3">
                                <label for="floatingInputEmail">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingInputEmail"  name="email" value="{{ old('email') }}" placeholder="Email">
                                
                                    @error('email')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <label for="floatingPassword">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" placeholder="Password" name="password">
                                @error('password')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror                        
                            </div>

                             <!-- 2 column grid layout for inline styling -->
                            <div class="row mb-4">
                                <div class="col d-flex justify-content-start">
                                  <!-- Checkbox -->
                                  <div class="form-check custom-control custom-checkbox">
                                    <input class="form-check-input custom-control-input" type="checkbox" value="1" id="remember"  />
                                    <label class="form-check-label custom-control-label small" for="remember"> Remember for 30 days </label>
                                  </div>
                                </div>
                                <div class="col d-flex justify-content-end">
                                  <!-- Simple link -->
                                  <a href="{{ route('password.request') }}" class="secondarycolor cus-linkk small">Forgot password ?</a>
                                </div>
                            </div>

                            <div class="mb-4">
                                <button class="singo-btn w-100 secondarybgcolor text-uppercase" type="submit">SIGN IN</button>
                            </div>

                            <div class="d-grid mb-2">
                                <button class="singo-btn w-100 fw-bold text-uppercase text-light" type="submit">
                                  <img src="{{ asset('image/icons/google.svg') }}"> &nbsp; Sign up with Google
                                </button>
                            </div>

                            <p class="mt-4 text-center">
                              Don’t have an account? <a class="text-center mt-2 secondarycolor cus-linkk" href="{{ route('register') }}">Sign up for free</a>
                            </p> 
                        </form>
                    </div>

                    <div class="card-img-left d-none d-md-flex">             
                        <img class="w-100" src="{{ asset('image/login_sec.png') }}">               
                       
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>
