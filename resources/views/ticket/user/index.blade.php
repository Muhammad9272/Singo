@extends('layouts.app')
@push('page_css')

    <x-styles.datatable />

    <style>
        .supportPin {
            font-size: 20px;
            font-weight: 500;
            letter-spacing: 2px;
        }

        .table-support th {
            border: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid orig-dtb">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-0">
                        <span class="float-left">
                            Ticket by Singo.io
                        </span>
                        <span class="float-right">
                            <a href="{{ route('ticket.create') }}" class="btn btn-success">
                                <i class="fas fa-plus mr-2"></i>
                                Create a ticket
                            </a>
                        </span>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <p class="alert alert-success text-center mt-2">
                                {{ session('success') }}
                            </p>
                        @elseif(session('error'))
                            <p class="alert alert-danger text-center mt-2">
                                {{ session('error') }}
                            </p>
                        @endif

                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title m-0">
                                                    <i class="fas fa-cogs mr-2"></i>Support Pin
                                                </h3>
                                            </div>

                                            <div class="card-body py-5">

                                                <div class="text-center text-xl text-white supportPin pb-3">
                                                    {{ $user->support_pin }}
                                                </div>

                                                <div class="py-3">
                                                    <a href="{{ route('support.pin.new', $user->id) }}" class="btn btn-success w-100">
                                                        <i class="fas fa-plus mr-2"></i>Generate new pin
                                                    </a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title m-0">
                                                    <i class="fas fa-ticket-alt mr-2"></i>My Support Ticket
                                                </h3>
                                            </div>
                                            <div class="card-body p-0">
                                                <ul class="nav nav-pills flex-column">
                                                    <li class="nav-item active">
                                                        <a href="javascript:void(0);" id="openBtn" class="d-flex justify-content-between align-items-center nav-link">
                                                            <div>
                                                                Open
                                                            </div>

                                                            <span class="text-xl text-success">{{ $user->openCount() }}</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" id="answeredBtn" class="d-flex justify-content-between align-items-center nav-link">
                                                            <div>
                                                                Answered
                                                            </div>

                                                            <span class="text-xl text-success">{{ $user->answeredCount() }}</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" id="csReplyBtn" class="d-flex justify-content-between align-items-center nav-link">
                                                            <div>
                                                                User Reply
                                                            </div>

                                                            <span class="text-xl text-success">{{ $user->csReplyCount() }}</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" id="closeBtn" class="d-flex justify-content-between align-items-center nav-link">
                                                            <div>
                                                                Closed
                                                            </div>

                                                            <span class="text-xl text-success">{{ $user->closeCount() }}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title m-0">
                                                    <i class="fas fa-history mr-2"></i>Ticket History
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="py-2">
                                                    <table class="table table-dark table-striped table-support shadow-sm" id="tableOne">
                                                        <thead>
                                                            <tr>
                                                                <th class="align-middle text-center">Ticket Type</th>
                                                                <th class="align-middle text-center">Subject</th>
                                                                <th class="align-middle text-center">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($tickets as $key_2 => $ticket)
                                                            <tr>
                                                                <td class="align-middle text-center">
                                                                    {{ $ticket->getTicketType() }}
                                                                </td>
                                                                <td class="align-middle text-center">
                                                                    <span class="ticketId">
                                                                        <a href="{{ route('ticket.show', $ticket->id) }}">#{{ $ticket->id }}</a>
                                                                    </span>

                                                                    <br>

                                                                    <span class="ticketSub">
                                                                        <a href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->subject }}</a>
                                                                    </span>
                                                                </td>
                                                                <td class="align-middle text-center">{{ $ticket->getTicketStatus() }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
        $(document).ready(function () {
            var dataTable = $('#tableOne').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                ]
            });

            $('#openBtn').click(function () {
                dataTable.search('Open').draw();
            });
            $('#answeredBtn').click(function () {
                dataTable.search('Answered').draw();
            });
            $('#csReplyBtn').click(function () {
                dataTable.search('User reply').draw();
            });
            $('#closeBtn').click(function () {
                dataTable.search('Close').draw();
            });
        });
    </script>
@endpush
