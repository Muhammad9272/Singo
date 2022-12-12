@extends('layouts.app')

@section('content')

<div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="float-left">
                            <h4>Reward Request</h4>
                        </span>
                        {{-- <span class="float-right">
                            <a href="{{ route('store.edit', $store->id)
                            }}"
                               class="btn btn-sm btn-info">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="{{ route('store') }}" class="btn btn-dark
                            btn-sm">Back</a>
                        </span> --}}
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">
                                
                                <h3 class="text-white">Reward Info</h3>
                                <p class="text-white"><strong>User name:</strong> {{$rewardrequests->user->name}}</p>
                                 
                                <p class="text-white"><strong>User email:</strong> {{$rewardrequests->user->email}}</p>
                                 <p class="text-white"><strong>Contact No:</strong> {{$rewardrequests->contact_no}}</p>

                                <p class="text-white"><strong>Title:</strong> {{$rewardrequests->reward->title}}</p>
                                <p class="text-white"><strong>Rank:</strong> {{$rewardrequests->reward->rank}}</p>

                                <p class="text-white"><strong>Details:</strong> {!! $rewardrequests->reward->detail !!}</p>
                               
                               

                            </div>
                            
                            @if($rewardrequests->reward->is_physical==1)
                            <div class="col-md-6">

                                <h3 class="text-white">Address (For physical reward)</h3>
                                <p class="text-white"><strong>First name:</strong> {{$rewardrequests->fname}}</p>
                                <p class="text-white"><strong>Last name:</strong> {{$rewardrequests->lname}}</p>
                                <p class="text-white"><strong>Street address:</strong> {{$rewardrequests->street_no}}</p>
                                <p class="text-white"><strong>Contact No:</strong> {{$rewardrequests->contact_no}}</p>
                                <p class="text-white"><strong>Zip code:</strong> {{$rewardrequests->zip_code}}</p>
                                <p class="text-white"><strong>City:</strong> {{$rewardrequests->city}}</p>
                                <p class="text-white"><strong>Country:</strong> {{$rewardrequests->country}}</p>
                                <p class="text-white"><strong>Additional Info:</strong> {{$rewardrequests->info}}</p>

                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

