@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-12">
            <div class="card mt-5">
                <div class="card-header">
                    <span class="float-left">
                        <h4>Edit Report</h4>
                    </span>
                    <span class="float-right">
                        <a href="{{ route('admin.user', $report->user_id) }}" class="btn btn-info btn-sm">Back</a>
                    </span>
                </div>

                <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <form action="{{ route('admin.report.update')}}" enctype="multipart/form-data" method="POST" class="form-horizontal">
                                @csrf
                                <input type = "hidden" name = "id" value =  "{{$report->user_id}}" >
                                <input type = "hidden" name = "report_id" value =  "{{$report->id}}" >                               
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label"> Date <span class="text-danger">*</span> </label>
                                    <div class="col-md-9">
                                        <input type="date" name="date" value="{{ $report->date }}" class="form-control form-control-success" required>
                                        @if ($errors->has('date'))
                                            <span class="text-danger">{{ $errors->first('date') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Streams <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="number" name="streams" value="{{ $report->streams }}" class="form-control form-control-success" required>
                                        @if ($errors->has('streams'))
                                            <span class="text-danger">{{ $errors->first('streams') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                        <label class="col-md-3 form-control-label">Store <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <select name="store" id="store" class="form-control form-control-success" required>
                                                <option selected hidden value="{{$report->store_id}}">{{$report->store->title}}</option>
                                                @foreach ($store as $st)
                                                    <option value="{{$st->id}}">{{$st->title}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('store'))
                                                <span class="text-danger">{{ $errors->first('store') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Money <span class="text-danger">*</span> </label>
                                    <div class="col-md-9">
                                        <input type="number" name="money" value="{{ $report->money}}" class="form-control form-control-success" required>
                                        @if ($errors->has('money'))
                                            <span class="text-danger">{{ $errors->first('money') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Attach CSV <span class="text-danger">*</span> </label>
                                    <div class="col-md-9">
                                    <input type="file" class="form-control form-control-success" id="file" name="file" placeholder="Attach CSV>">
                                    <span class="text-danger">If you want to update csv file upload it.</span>
                                        @if ($errors->has('file'))
                                            <span class="text-danger">{{ $errors->first('file') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-9 ml-auto">
                                        <input type="submit" value="Submit" class="btn btn-primary">
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

