@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <div class="col-md-12">
                <h1>Users</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">All users</div>

                    <div class="card-body table-responsive">
                        <form action="{{ route('admin.users') }}">
                            <div class="row pb-3 mb-4 border-bottom">
                                <div class="col-md-3 ml-auto">
                                    <input type="text" class="form-control" placeholder="Search..." name="searchQuery" value="{{ request('searchQuery') }}">
                                </div>
                            </div>
                        </form>

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="align-middle text-center"></th>
                                <th class="align-middle pl-2">ID</th>
                                <th class="align-middle pl-2">Name</th>
                                <th class="align-middle pl-2">Artist Name</th>
                                <th class="align-middle pl-2">Email</th>
                                <th class="align-middle pl-2">Subscription</th>
                                <th class="align-middle pl-2">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    @if($user->profile_picture != null)
                                        <td class="align-middle text-center">
                                            <img width="50" height="50" style="object-fit: cover;" src="{{ $user->profile_picture_route }}">
                                        </td>
                                    @else
                                        <td class="align-middle text-center">
                                            <img width="50" height="50" style="object-fit: cover;" src="{{ asset('image/user.png') }}">
                                        </td>
                                    @endif

                                    <td class="align-middle pl-2">{{ $user->id }}</td>
                                    <td class="align-middle pl-2">{{ $user->name }}</td>
                                    <td class="align-middle pl-2">{{ $user->artistName }}</td>
                                    <td class="align-middle pl-2"> {{ $user->email }}</td>

                                    @if($user->stripe_subscription_status ==0 && strtotime($user->stripe_end_date) > time())
                                        <td class="align-middle pl-2">
                                            @php
                                                $plans = App\Models\Plan::findOrFail($user->plan);
                                            @endphp

                                            <div class="btn align-middle" style="background-color: {{ $plans->show_button }}">
                                                <span class="text-white">{{ $plans->title }}</span>
                                            </div>

                                            <br />
                                            <small> Until {{$user->stripe_end_date}}</small>
                                        </td>
                                    @else
                                        <td class="align-middle pl-2">
                                            @php
                                                $plans = App\Models\Plan::findOrFail($user->plan);
                                            @endphp
                                            <div class="btn align-middle" style="background-color: {{ $plans->show_button }}">
                                                <span class="text-white">{{ $plans->title }}</span>
                                            </div>
                                        </td>
                                    @endif

                                    <td class="align-middle pl-2">
                                        <a href="{{ route('admin.user', $user->id) }}" class="btn btn-info"><i class="fas fa-eye"></i> View</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
