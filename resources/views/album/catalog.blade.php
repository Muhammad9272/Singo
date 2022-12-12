@extends('layouts.app')
@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                    <div class="d-flex11 mt-30">
                        <h4 class="d-block float-left">
                            Your albums
                        </h4>
                        
                        <ul class="mlk-dtb-nav d-block float-right nav"
                            role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-all">All
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-pending">Pending
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" data-toggle="tab" href="#tab-approved">Approved
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-delivered">Delivered
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-needEdit">Need Edit
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-declined">Declined
                                </a>
                            </li>
                        </ul>
                    </div>



                {{-- <div class="card">
                    <div class="card-header">Your albums


                    </div>
                    <div class="card-body table-responsive">
                        @if (session('success'))
                            <p class="alert alert-success text-center">
                                {{ session('success') }}
                            </p>
                        @elseif(session('error'))
                            <p class="alert alert-danger text-center">
                                {{ session('error') }}
                            </p>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">

                                    <div class="">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                                                <div class="mt-2 table-responsive">
                                                    @include('album.datatables.all-albums')
                                                </div>
                                            </div>

                                            <div class="tab-pane fade show" id="tab-pending" role="tabpanel">
                                                <div class="mt-2 table-responsive">
                                                    @include('album.datatables.pending')
                                                </div>
                                            </div>

                                            <div class="tab-pane fade show" id="tab-pending" role="tabpanel">
                                                <div class="mt-2 table-responsive">
                                                    @include('album.datatables.pending')
                                                </div>
                                            </div>

                                            <div class="tab-pane fade show" id="tab-approved" role="tabpanel">
                                                <div class="mt-2 table-responsive">
                                                    @include('album.datatables.pending')
                                                </div>
                                            </div>

                                            <div class="tab-pane fade show" id="tab-delivered" role="tabpanel">
                                                <div class="mt-2 table-responsive">
                                                    @include('album.datatables.pending')
                                                </div>
                                            </div>

                                            <div class="tab-pane fade show" id="tab-needEdit" role="tabpanel">
                                                <div class="mt-2 table-responsive">
                                                    @include('album.datatables.need-edit')
                                                </div>
                                            </div>

                                            <div class="tab-pane fade show" id="tab-declined" role="tabpanel">
                                                <div class="mt-2 table-responsive">
                                                    @include('album.datatables.declined')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="col-md-12">
                <div class="card12">

                    <div class="">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                                <div class="mt-2 table-responsive">
                                    @include('album.datatables.all-albums')
                                </div>
                                

                            </div>

                            <div class="tab-pane fade show" id="tab-pending" role="tabpanel">
                                <div class="mt-2 table-responsive">
                                    @include('album.datatables.pending')
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="tab-pending" role="tabpanel">
                                <div class="mt-2 table-responsive">
                                    @include('album.datatables.pending')
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="tab-approved" role="tabpanel">
                                <div class="mt-2 table-responsive">
                                    @include('album.datatables.pending')
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="tab-delivered" role="tabpanel">
                                <div class="mt-2 table-responsive">
                                    @include('album.datatables.pending')
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="tab-needEdit" role="tabpanel">
                                <div class="mt-2 table-responsive">
                                    @include('album.datatables.need-edit')
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="tab-declined" role="tabpanel">
                                <div class="mt-2 table-responsive">
                                    @include('album.datatables.declined')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('page_scripts')
    <script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/buttons.html5.min.js') }}"></script>
    <script>
        // $(document).ready(function () {
        //     $('#tableOne').DataTable({
        //         responsive: true,
        //         searching: false,
        //         bPaginate: false,
        //         buttons: [],

        //     });
        //     $('#tableTwo').DataTable({
        //         searching: false,
        //         dom: 'Bfrtip',
        //         bPaginate: false,
        //         buttons: []
        //     });
        //     $('#tableThree').DataTable({
        //         searching: false,
        //         dom: 'Bfrtip',
        //         bPaginate: false,
        //         buttons: []
        //     });
        //     $('#tableFour').DataTable({
        //         searching: false,
        //         dom: 'Bfrtip',
        //         bPaginate: false,
        //         buttons: []
        //     });
        //     $('#tableFive').DataTable({
        //         searching: false,
        //         dom: 'Bfrtip',
        //         bPaginate: false,
        //         buttons: []
        //     });
        //     $('#tableSix').DataTable({
        //         searching: false,
        //         dom: 'Bfrtip',
        //         bPaginate: false,
        //         buttons: []
        //     });
        // });
    </script>
@endpush
