@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-12">
                <div class="card mt-5">
                    <div class="card-header">
                    <span class="float-left">
                        <h4>Edit Store</h4>
                    </span>
                        <span class="float-right">
                        <a href="{{ route('admin.stores.index') }}" class="btn btn-info btn-sm">Back</a>
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
                                <form action="{{ route('admin.stores.update', $store->id)}}" method="POST" class="form-horizontal">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="id" value="{{ $store->id }}">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label">
                                            Title
                                            <span class="text-danger">*</span> </label>
                                        <div class="col-md-9">
                                            <input type="text" name="title" value="{{ $store->title }}" class="form-control form-control-success" required>
                                            @if ($errors->has('title'))
                                                <span class="text-danger">{{ $errors->first('title') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="fuga_store_id" class="col-md-3 form-control-label">Fuga Store ID</label>
                                        <div class="col-md-9">
                                            <input id="fuga_store_id" type="number" name="fuga_store_id" value="{{ $store->fuga_store_id }}" class="form-control form-control-success">

                                            @if ($errors->has('fuga_store_id'))
                                                <span class="text-danger">{{ $errors->first('fuga_store_id') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-9 ml-auto">
                                            <input type="submit" value="Update" class="btn btn-primary">
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

