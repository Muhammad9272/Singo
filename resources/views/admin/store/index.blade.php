@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-12">
                <div class="card">
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
                </div>

                <div class="card mt-5">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Stores</h4>
                        <div class="card-tools">
                            <a href="{{ route('admin.stores.create') }}" class="btn btn-primary btn-sm">Add Stores</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <x-datatable.footable>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Fuga Store ID</th>
                                    <th>Added By</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($stores as $key => $store)
                                    <tr>
                                        <td> {{ $store->id }} </td>
                                        <td> {{ $store->title }} </td>
                                        <td> {{ $store->fuga_store_id }} </td>
                                        <td> {{ $store->creator->name }} </td>
                                        <td> {{ $store->created_at->diffForHumans() }} </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.stores.show',$store->id) }}"
                                                    class="btn btn-sm btn-dark">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.stores.edit',$store->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </x-datatable.footable>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        {{ $stores->Oneachside(2)->links('pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
