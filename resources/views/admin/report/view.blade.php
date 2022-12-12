@extends('layouts.app')

@section('content')

<div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <span class="float-left">
                            <h4>Report View</h4>
                        </span>
                        <span class="float-right">
                            <a href="{{ route('admin.report.edit', $report->id) }}"
                               class="btn btn-sm btn-info">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.user',$report->user_id) }}" class="btn btn-dark
                            btn-sm">Back</a>
                        </span>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped">
                                        <tr>
                                            <td>ID</td>
                                            <td>:</td>
                                            <td>
                                                <span class="font-weight-bold">
                                                    {{$report->id }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date</td>
                                            <td>:</td>
                                            <td>
                                                <span >
                                                    {{$report->date }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Streams</td>
                                            <td>:</td>
                                            <td>{{ $report->streams }}</td>
                                        </tr>
                                        <tr>
                                            <td>Store</td>
                                            <td>:</td>
                                            <td>{{$report->store->title}}</td>
                                        </tr>
                                        <tr>
                                            <td>Money</td>
                                            <td>:</td>
                                            <td>{{$report->money}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created by</td>
                                            <td>:</td>
                                            <td>{{ $report->users_4->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created at</td>
                                            <td>:</td>
                                            <td>{{ $report->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td>Last Modified by</td>
                                            <td>:</td>
                                            <td>@if(isset($report->users_5->name ))
                                                    {{ $report->users_5->name }}
                                                @else
                                                    Not Modified Yet
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Last Modified at</td>
                                            <td>:</td>
                                            <td>@if(isset($report->updated_at ))
                                                    {{ $report->updated_at }}
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

