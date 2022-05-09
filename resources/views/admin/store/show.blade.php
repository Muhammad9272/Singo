@extends('layouts.app')

@section('content')

<div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="float-left">
                            <h4>Store View</h4>
                        </span>
                        <span class="float-right">
                            <a href="{{ route('store.edit', $store->id)
                            }}"
                               class="btn btn-sm btn-info">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="{{ route('store') }}" class="btn btn-dark
                            btn-sm">Back</a>
                        </span>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped">
                                        <tr>
                                            <td>Title</td>
                                            <td>:</td>
                                            <td>
                                                <span class="font-weight-bold">
                                                    {{$store->title }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Id</td>
                                            <td>:</td>
                                            <td>{{ $store->id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created by</td>
                                            <td>:</td>
                                            <td>{{ $store->users_1->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created at</td>
                                            <td>:</td>
                                            <td>{{ $store->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td>Last Modified by</td>
                                            <td>:</td>
                                            <td>@if(isset($store->users_2->name ))
                                                    {{ $store->users_2->name }}
                                                @else
                                                    Not Modified Yet
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Last Modified at</td>
                                            <td>:</td>
                                            <td>@if(isset($store->updated_at ))
                                                    {{ $store->updated_at }}
                                                @else
                                                    Not Modified Yet
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

