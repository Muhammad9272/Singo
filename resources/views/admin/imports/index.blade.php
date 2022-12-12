@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <div class="col-md-12">
                <h1>Imports</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">All Imports</h4>

                        <div class="card-tools">
                            <a href="{{ route('admin.imports.create') }}" class="btn btn-success">Create Import</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark  table-striped">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>Name</td>
                                <td>Type</td>
                                <td data-breakpoints="xs">Created By</td>
                                <td>Status</td>
                                <td data-breakpoints="xs">Created At</td>
                                <td data-breakpoints="xs">Actions</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($imports as $import)
                                <tr>
                                    <td>{{ $import->id }}</td>
                                    <td>{{ $import->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($import->type == \App\Models\Import::IMPORT_TYPE_USERS_PAYMENT_REPORT)
                                            User Payment Report - Orchard
                                        @endif
                                        @if($import->type == \App\Models\Import::IMPORT_TYPE_USERS_PAYMENT_REPORT_FUGA)
                                            User Payment Report - Fuga
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.user', $import->user_id) }}">{{ $import->user->name }}</a>
                                    </td>
                                    <td>
                                        @if($import->status == \App\Models\Import::IMPORT_STATUS_PENDING)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($import->status == \App\Models\Import::IMPORT_STATUS_QUEUED)
                                            <span class="badge badge-info">Queued</span>
                                        @elseif($import->status == \App\Models\Import::IMPORT_STATUS_PROCESSED)
                                            <span class="badge badge-success">Processed</span>
                                        @endif
                                    </td>
                                    <td>{{ $import->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="d-flex">
                                            @if($import->status == \App\Models\Import::IMPORT_STATUS_PENDING)
                                                <a class="btn btn-sm btn-success mr-1" href="{{ route('admin.imports.process', $import->id) }}">Process</a>
                                            @elseif($import->status == \App\Models\Import::IMPORT_STATUS_PROCESSED)
                                                <a class="btn btn-sm btn-info mr-1" href="{{ route('admin.imports.log', $import->id) }}">Download Log</a>
                                            @endif

                                            <form action="{{ route('admin.imports.destroy', $import->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn link btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this report?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $imports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('components.libs.footable')

@push('page_scripts')
    <script>
        $('.table').footable();
    </script>
@endpush
