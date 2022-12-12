<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MGSSDXG');</script>
<!-- End Google Tag Manager -->

<!!-- TikTok Pixel -->
<script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
  ttq.load('C816KVVV9S6QTNQPAMD0');
  ttq.page();
}(window, document, 'ttq');
</script>
<!-- End TikTok Pixel -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon-->
    <link rel="icon" href="{{ asset('image/singo-logo-dark.png') }}" type="image/png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" >

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link
      href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
      rel="stylesheet"
    />

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
        {{-- <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet"> --}}

        {{-- <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet"> --}}


    @php
        $unreads=auth()->user()->unreadNotifications;
        $id = auth()->user()->id;
        $setting = App\Models\User::with('UserSetting')->findOrFail($id);
        $darkmode = "";
        $select = "";
    @endphp

    @if(isset($setting->UserSetting) && $setting->UserSetting->dark_mode == 1)
        @php
            $darkmode = "dark-mode";
            $select = "checked"
        @endphp
    @endif


    <style type="text/css">
        body {
            font-family: 'Poppins', sans-serif !important;
           /* font-family: 'DM Sans', sans-serif !important;
            font-weight: 500;*/
        }
        @media (min-width: 992px) {
            .sidebar-mini.sidebar-collapse .main-sidebar:hover, .sidebar-profile-info {
                display: block;
            }
        }
    </style>

    <link href="{{ asset('css/custom_new.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-responsive.css') }}" rel="stylesheet">

    @yield('third_party_stylesheets')

    @stack('page_css')
</head>


<body class="sidebar-mini">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MGSSDXG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <div class="wrapper dark-theme" id="mlk-singo-bg" style="background-image: url({{ asset('image/icons/bg.png') }});">
        <!-- Main Header -->
        {{-- @include('layouts.partials.top-navbar') --}}

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
             
            
            <section class="content">
                <div class="d-none justify-content-end singo-nav-link">
                     <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 ml-auto">
                            @include('layouts.partials.alerts')
                        </div>
                    </div>
                </div>
                <div class="container-fluid mb-20">                   
                        @include('layouts.partials.top-navbar')                  
                </div>
                @yield('content')
            </section>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer text-center">
            <p style="color:#707EAE">
                Copyright &copy; 
                <span style="color: white;">
                    {{ date('Y') }}  <a style="color:white" href="https://app.singo.io">singo.io</a>.
                </span> 
                All rights reserved.
             </p>
        </footer>
    </div>
</body>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    window.intercomSettings = {
        app_id: "z0cm5f3w",
        name: "{{ auth()->user()->name }}",
        email: "{{ auth()->user()->email }}"
    };
</script>

<script>
    // We pre-filled your app ID in the widget URL: 'https://widget.intercom.io/widget/z0cm5f3w'
    (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/z0cm5f3w';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(document.readyState==='complete'){l();}else if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
</script>

<script src="{{ mix('js/app.js') }}"></script>

@yield('third_party_scripts')

@stack('page_scripts')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        $('[data-widget="pushmenu"]').on("click", function(){
            if(window.innerWidth > 991){
                $('.sidebar-profile-info').toggle();
            }
        });

        $("#dark_mode").click(function () {
            $("#dark_form").submit(); // Submit the form
        });
    });

    $(document).ready(function() {
        $('.select2custom').select2({
            closeOnSelect: false,
            templateResult: formatState,
            templateSelection: formatState
        });

       

        function formatState (opt) {
            if (!opt.id) {
                return opt.text.toUpperCase();
            } 

            var optimage = $(opt.element).attr('data-image'); 
            console.log(optimage)
            if(!optimage){
               return opt.text.toUpperCase();
            } else {                    
                var $opt = $(
                   '<span><img src="' + optimage + '" class="select-img" /> ' + opt.text.toUpperCase() + '</span>'
                );
                return $opt;
            }
        };


    });
    
</script>


</html>
