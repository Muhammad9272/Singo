@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-12">
                {{-- <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="row pb-3 mb-4 border-bottom">
                                <div class="col-md-12 ml-auto">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <input type="text" class="form-control" placeholder="Search..."
                                                name="searchQuery" value="{{ request('searchQuery') }}">
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-success">Search</button>
                                            <a href="{{ route('admin.stores.index') }}" class="btn btn-warning">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> --}}

                <div class="card mt-5">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Reward Request</h4>
                        <div class="card-tools">
                           {{--  <a href="{{ route('admin.rewards.show_button') }}" class="btn btn-primary btn-sm">Add Reward</a> --}}
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <x-datatable.footable>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reward Title</th>
                                    <th>User</th>
                                    <th>User Email</th>
                                    <th>Points</th>
                                    <th>Change Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($rewardrequests as $key => $rewardrequest)
                                    <tr>
                                        <td> {{ $rewardrequest->id }} </td>
                                        <td> {{ $rewardrequest->reward->title }} </td>
                                        <td> {{ $rewardrequest->user->name }} </td>
                                        <td> {{ $rewardrequest->user->email }} </td>
                                        <td> {{ $rewardrequest->reward->points }} </td>
                                        <td> 
                                            @if($rewardrequest->status=="pending")
                                            <a href="{{ route('admin.reward.requests.status',['id'=> $rewardrequest->id] ) }}" class="badge badge-info">Pending</a>
                                            @else
                                             <a href="javascript:;" class="badge badge-success">Completed</a>
                                            @endif
                                         </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.rewardrequests.show', $rewardrequest->id) }}"
                                                    class="btn btn-sm btn-dark">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                {{-- <a href="{{ route('admin.rewards.edit',$reward->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </x-datatable.footable>
                        </div>
                    </div>
                   {{--  <div class="card-footer text-center">
                        {{ $rewards->Oneachside(2)->links('pagination.default') }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
