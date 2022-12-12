@extends('layouts.app')
@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
    <style>
        .supportPin{
            font-size: 20px;
            font-weight: 500;
            letter-spacing: 2px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid orig-dtb">
        <div class="row content-header mb-2">
            <h1>Support and Ticket</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="float-left">
                            Ticket by Singo.io
                        </span>
                        {{-- <span class="float-right">
                            <a href="{{ route('ticket.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>
                                Create a ticket
                            </a>
                        </span> --}}
                    </div>
                    @if(session('success'))
                        <p class="alert alert-success text-center mt-2">
                            {{ session('success') }}
                        </p>
                    @elseif(session('error'))
                        <p class="alert alert-danger text-center mt-2">
                            {{ session('error') }}
                        </p>
                    @endif

                    <div class="row mt-2 p-2">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Report</h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="myChart" width="100%" height="100%"></canvas>
                                        </div>
                                        <div class="card-footer">

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-history mr-2"></i>Ticket Overview</i></h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class=" mt-2 p-2">
                                                <table class=" table-responsive table table-dark table-striped nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th class = "align-middle text-center" rowspan="2">Name</th>
                                                            <th class = "align-middle text-center" rowspan="1" colspan="4">Action</th>

                                                        </tr>
                                                        <tr>
                                                            <th class = "align-middle text-center" rowspan="1" colspan="1">Open</th>
                                                            <th class = "align-middle text-center" rowspan="1" colspan="1">Answered</th>
                                                            <th class = "align-middle text-center" rowspan="1" colspan="1">Customer Reply</th>
                                                            <th class = "align-middle text-center" rowspan="1" colspan="1">Close</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach($tickets->unique('open') as $ticket)
                                                            @foreach ($ticket->open()->get() as $tk)

                                                                <tr>
                                                                    <td class = "align-middle text-center">
                                                                        {{ $tk->name }}
                                                                    </td>
                                                                    <td class = "align-middle text-center">
                                                                        {{ $tk->openAdmin() }}
                                                                    </td>
                                                                    <td class = "align-middle text-center">
                                                                        {{ $tk->answeredAdmin() }}
                                                                    </td>
                                                                    <td class = "align-middle text-center">
                                                                        {{ $tk->csReplyAdmin() }}
                                                                    </td>
                                                                    <td class = "align-middle text-center">
                                                                        {{ $tk->closeAdmin() }}
                                                                    </td>

                                                                </tr>
                                                             @endforeach

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
                    <div class="row mt-2 p-2">
                        <div class="col-md-12">
                            <div class="card card-outline card-danger">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-pencil-alt mr-2"></i>Open Tickets</i></h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class=" mt-2 p-2">
                                        <table class="table table-dark table-striped nowrap table-responsive" id = "tableOne">
                                            <thead>
                                                <tr>
                                                    <th class = "align-middle text-center">Priority</th>
                                                    <th class = "align-middle text-center">Type</th>
                                                    <th class = "align-middle text-center">Subject</th>
                                                    <th class = "align-middle text-center">Status</th>
                                                    <th class = "align-middle text-center">Open by</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse($tickets as $key_2 => $ticket)
                                                @if ($ticket->status == 3)
                                                    <tr>
                                                        <td class = "align-middle text-center">
                                                           <button class="btn btn-{{ $ticket->getTicketPriorityColor() }}">{{ $ticket->getTicketPriority() }}</button>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            <button class="btn btn-{{ $ticket->getTicketTypeColor() }}">{{ $ticket->getTicketType() }}</button>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <a href="{{ route('ticket.open.create', $ticket->id) }}">{{ $ticket->subject }}</a>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            <button class="btn btn-{{ $ticket->getTicketStatusColor() }}">{{ $ticket->getTicketStatus() }}</button>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            {{ $ticket->open()->first()->name ?? 'Not opened yet' }}
                                                        </td>
                                                     </tr>
                                                @endif
                                                @empty
                                                     <tr><td class = "align-middle text-center" colspan="6"> No Data Found</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 p-2">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-reply mr-2"></i>Answered</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class=" mt-2 p-2">
                                        <table class="table table-dark table-striped nowrap table-responsive" id = "tableTwo">
                                            <thead>
                                                <tr>
                                                    <th class = "align-middle text-center">Priority</th>
                                                    <th class = "align-middle text-center">Type</th>
                                                    <th class = "align-middle text-center">Subject</th>
                                                    <th class = "align-middle text-center">Status</th>
                                                    <th class = "align-middle text-center">Open by</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse($tickets as $key_2 => $ticket)
                                                @if ($ticket->status == 2)
                                                    <tr>
                                                        <td class = "align-middle text-center">
                                                           <button class="btn btn-{{ $ticket->getTicketPriorityColor() }}">{{ $ticket->getTicketPriority() }}</button>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            <button class="btn btn-{{ $ticket->getTicketTypeColor() }}">{{ $ticket->getTicketType() }}</button>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <a href="{{ route('ticket.open.create', $ticket->id) }}">{{ $ticket->subject }}</a>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            <button class="btn btn-{{ $ticket->getTicketStatusColor() }}">{{ $ticket->getTicketStatus() }}</button>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            {{ $ticket->open()->first()->name ?? 'Not opened yet' }}
                                                        </td>
                                                     </tr>
                                                @endif
                                                @empty
                                                     <tr><td class = "align-middle text-center" colspan="6"> No Data Found</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 p-2">
                        <div class="col-md-12">
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-user-clock mr-2"></i>Waiting for User Reply</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class=" mt-2 p-2">
                                        <table class="table table-dark table-striped nowrap table-responsive" id = "tableThree">
                                            <thead>
                                                <tr>
                                                    <th class = "align-middle text-center">Priority</th>
                                                    <th class = "align-middle text-center">Type</th>
                                                    <th class = "align-middle text-center">Subject</th>
                                                    <th class = "align-middle text-center">Status</th>
                                                    <th class = "align-middle text-center">Open by</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse($tickets as $key_2 => $ticket)
                                                @if ($ticket->status == 1)
                                                    <tr>
                                                        <td class = "align-middle text-center">
                                                           <button class="btn btn-{{ $ticket->getTicketPriorityColor() }}">{{ $ticket->getTicketPriority() }}</button>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            <button class="btn btn-{{ $ticket->getTicketTypeColor() }}">{{ $ticket->getTicketType() }}</button>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <a href="{{ route('ticket.open.create', $ticket->id) }}">{{ $ticket->subject }}</a>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            <button class="btn btn-{{ $ticket->getTicketStatusColor() }}">{{ $ticket->getTicketStatus() }}</button>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            {{ $ticket->open()->first()->name ?? 'Not opened yet' }}
                                                        </td>
                                                     </tr>
                                                @endif
                                                @empty
                                                     <tr><td class = "align-middle text-center" colspan="6"> No Data Found</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 p-2">
                        <div class="col-md-12">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-check-double mr-2"></i>Closed</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class=" mt-2 p-2">
                                        <table class="table table-dark table-striped nowrap table-responsive" id = "tableFour">
                                            <thead>
                                                <tr>
                                                    <th class = "align-middle text-center">Priority</th>
                                                    <th class = "align-middle text-center">Type</th>
                                                    <th class = "align-middle text-center">Subject</th>
                                                    <th class = "align-middle text-center">Status</th>
                                                    <th class = "align-middle text-center">Open by</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse($tickets as $key_2 => $ticket)
                                                @if ($ticket->status == 0)
                                                    <tr>
                                                        <td class = "align-middle text-center">
                                                           <button class="btn btn-{{ $ticket->getTicketPriorityColor() }}">{{ $ticket->getTicketPriority() }}</button>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            <button class="btn btn-{{ $ticket->getTicketTypeColor() }}">{{ $ticket->getTicketType() }}</button>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <a href="{{ route('ticket.open.create', $ticket->id) }}">{{ $ticket->subject }}</a>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            <button class="btn btn-{{ $ticket->getTicketStatusColor() }}">{{ $ticket->getTicketStatus() }}</button>
                                                        </td>
                                                        <td class = "align-middle text-center">
                                                            {{ $ticket->open()->first()->name ?? 'Not opened yet' }}
                                                        </td>
                                                     </tr>
                                                @endif
                                                @empty
                                                     <tr><td class = "align-middle text-center" colspan="6"> No Data Found</td></tr>
                                                @endforelse
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
@endsection
@push('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
<script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/buttons.html5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#tableOne').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                 'pageLength',
            ]
        } );
        $('#tableTwo').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                 'pageLength',
            ]
        } );
        $('#tableThree').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                 'pageLength',
            ]
        } );
        $('#tableFour').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                 'pageLength',
            ]
        } );

        const ctx = $('#myChart');
        const myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Open', 'User reply', 'Answered', 'Closed'],
                datasets: [{
                    label: '# of Votes',
                    data: [{{ $open }}, {{ $csReply }}, {{ $answere }}, {{ $close }}],
                    backgroundColor: [
                        'rgba(245, 10, 182, 0.8)',
                        'rgba(18, 224, 86, 0.8)',
                        'rgba(0, 88, 0, 0.8)',
                        'rgba(255, 111, 0, 0.8)',
                    ],
                    borderColor: [
                        'rgba(71, 0, 52, 1)',
                        'rgba(18, 224, 187, 1)',
                        'rgba(0, 88, 83, 1)',
                        'rgba(255, 53, 0, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {

            }
        });
    });
</script>
@endpush
