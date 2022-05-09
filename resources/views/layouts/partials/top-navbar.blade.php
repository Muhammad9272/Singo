<nav class="main-header navbar navbar-dark">
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
