<aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <div class="d-flex justify-content-center">
            <img src="{{ asset('image/singo-logo.png') }}" alt="Singo.io Logo" class="brand-image m-0"
                 style="float:none;max-height:60px;">
        </div>
    </a>
    <hr class="custom-hr">

    <div class="text-center mt-3 hover14">
        <figure> <img style="width: 70%;" src="{{asset(App\Helpers\AppHelper::tstreams())}}"> </figure>
    </div>

    <div class="sidebar overflow-hidden" style="height: {{auth()->user()->type != 0?'auto':''}}">



        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
            
            <ul class="nav nav-pills nav-sidebar flex-column {{ auth()->user()->type == '0' ? 'position-absolute11 bottom11-0 mt2-4' : '' }}" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.partials.sidebar-bottom-menu')
            </ul>
        </nav>

        <!-- Sidebar user panel -->
        <div class="row mt-5 mt-auto" style="margin-bottom: 145px;">
            <div class="col-4 align-middle text-center">
                @if(isset(auth()->user()->profile_picture))
                    <img src="{{ asset(auth()->user()->profile_picture) }}" class="img-square" alt="User Image"
                         style="height:50px;  width:50px;margin-left: 3px;object-fit: cover;">
                @else
                    <img src="{{ asset('image/user.png') }}" class="img-square" alt="User Image"
                         style="height:50px;  width:50px;margin-left: 3px;object-fit: cover;">
                @endif

                <p class="mlk-online-ico">
                    <i class="fa fa-circle online-circle"></i>
                </p>
            </div>

            <div class="col-6 pl-0 sidebar-profile-info">
                <p class="mb-1 align-middle">
                    {{ auth()->user()->name }}
                </p>
                <p class="mb-0 align-middle text-sm">
                   {{ auth()->user()->balance }}{{$pcurrency_symbol}}
                </p>
            </div>
            <div class="col-2 pl-0 d-flex align-items-center">
                <a href="#" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <img  class="w-30" src="{{ asset('image/icons/logout.svg') }}">
                </a>
            </div>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
            </form>
        </div>


    </div>

</aside>
