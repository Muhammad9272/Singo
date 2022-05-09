<aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <div class="d-flex justify-content-center">
            <img src="{{ asset('image/singo-logo.png') }}" alt="Singo.io Logo" class="brand-image m-0"
                 style="opacity: .8;float:none;max-height:60px;">
        </div>
    </a>

    <div class="sidebar overflow-hidden">

        <!-- Sidebar user panel -->
        <div class="row mt-5">
            <div class="col-md-4 align-middle text-center">
                @if(isset(auth()->user()->profile_picture))
                    <img src="{{ auth()->user()->profile_picture }}" class="img-circle" alt="User Image"
                         style="height:50px;  width:50px;margin-left: 3px;object-fit: cover;">
                @else
                    <img src="{{ asset('image/user.png') }}" class="img-circle" alt="User Image"
                         style="height:50px;  width:50px;margin-left: 3px;object-fit: cover;">
                @endif
            </div>

            <div class="col-md-8 pl-0 sidebar-profile-info">
                <p class="mb-1 align-middle">
                    {{ auth()->user()->name }}
                </p>
                <p class="mb-0 align-middle text-sm">
                    <i class="fa fa-circle online-circle"></i>
                    Online
                </p>
            </div>
        </div>

        <nav class="mt-5">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>

            <ul class="mt-4 nav nav-pills nav-sidebar flex-column {{ auth()->user()->type == '0' ? 'position-absolute bottom-0' : '' }}" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.partials.sidebar-bottom-menu')
            </ul>
        </nav>
    </div>

</aside>
