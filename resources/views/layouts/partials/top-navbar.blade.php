{{-- <nav class="main-header navbar navbar-dark">
    <div class="container-fluid navbar-inner">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item navbar-collapse-menu">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">

            @if (auth()->user()->type != 0)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-bell"></i><span class="badge text-danger">{{count($unreads)}}</span>
                    </a>

                    <div class="dropdown-menu p-2" aria-labelledby="navbarDropdown">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <form method="post" action="{{route('notification')}}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $notification->id }}">
                                <button class="dropdown-item border-bottom border-primary p-2 text-left" href="#"
                                        type="submit">
                                    [ {{ $notification->created_at }} ] {{ $notification->data['message'] }}
                                </button>
                            </form>

                        @empty
                            <a class="dropdown-item border-bottom border-primary p-2 text-left" href="#">
                                No Notification is available
                            </a>
                        @endforelse
                        <a class="dropdown-item text-center" href="{{route('mark_all_read')}}">Mark all as read</a>
                    </div>
                </li>
            @endif

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <span>Welcome {{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <li class="user-header bg-primary">
                        <p>
                            {{ Auth::user()->name }}<br>
                            aka. {{ auth()->user()->artistName }}
                            <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                            <small><strong>Balance: {{ auth()->user()->balance }}€</strong></small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="{{ route('wallet') }}" class="btn btn-default btn-flat">Wallet</a>
                        <a href="#" class="btn btn-default btn-flat float-right"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sign out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
 --}}


<nav class="navbar navbar-expand-lg sng-navbar">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto sng-navbar-ul">
      <li class="nav-item {{ navActiveClass('home') }}">
        <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
      </li>
      <li class="nav-item {{ navActiveClass('analytics.show') }}">
        <a class="nav-link" href="{{ route('analytics.show') }}">Anayltics</a>
      </li>
      <li class="nav-item {{ navActiveClass('albums') }}">
        <a class="nav-link" href="{{ route('albums') }}">Your music</a>
      </li>
       <li class="nav-item {{ navActiveClass('wallet') }}">
        <a class="nav-link" href="{{ route('wallet') }}">Wallet</a>
      </li>
    </ul>
    <span class="navbar-text">
      <a href="" class="btn btn-bird"> <img src="{{ asset('image/icons/bird.png') }}">  Bushido</a>
    </span>
  </div>
</nav>

 <p class="m-0">
     Hi, {{ auth()->user()->name }}
 </p>
  <div class="row ">

    <div class="col-md-4">
        
         <h2>Welcome to <span class="secondarycolor">Singo!</span></h2>
    </div>
    @if (auth()->user()->type != 0 && auth()->user()->unreadNotifications->count()>0)
    <div class="col-md-8">
         <div class="d-flex mlk-nav">
             
             <p class="time">{{auth()->user()->unreadNotifications->first()->created_at->format('d F , H:i A')}}</p>
             <div class="albumd dropdown ">
                                      
                    <a class="d-flex justify-content-between" data-toggle="modal" data-target="#NotificationsModal"
                    >
                        <p class="album ">{{auth()->user()->unreadNotifications->first()->data['message']}}</p> 
                        <span class="icon"><img src="{{ asset('image/icons/noti.png') }}"></sapn>
                    </a> 
             </div>
             
         </div>
    </div>
    @endif
  </div>


@if (auth()->user()->type != 0)
<!-- Modal -->
<div class="modal fade" id="NotificationsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Notifications({{count($unreads)}}) </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @forelse(auth()->user()->unreadNotifications as $notification)
            <form method="post" class="noti-formm" action="{{route('notification')}}">
                @csrf
                <input type="hidden" name="id" value="{{ $notification->id }}">
                <button class="notificaion-bttn" href="#"
                        type="submit">
                     [{{ $notification->created_at->format('F d, Y H:i A') }} ] {{ $notification->data['message'] }}
                </button>
            </form>
        @empty
            <a class="dropdown-item border-bottom border-primary p-2 text-left" href="#">
                No Notification is available
            </a>
        @endforelse
         <a class="dropdown-item text-center singo-btn secondarybgcolor mt-20" href="{{route('mark_all_read')}}">Mark all as read</a>
      </div>
      
    </div>
  </div>
</div>
@endif