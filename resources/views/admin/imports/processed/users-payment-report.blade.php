@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row content-header mb-2">
        <div class="col-md-12">
            <h1>
                Processed Report
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Report Summary
                        @if($import->rate == 0)
                        <span class="text-sm text-danger">Error: Rate 0, default rate 1 is set</span>
                        @endif
                    </h4>
                    <div class="card-tools">
                        <form action="{{ route('admin.imports.apply', $import->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to deposit this report?')">
                                Save Processed
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>
                                    Total Streams
                                </th>
                                <td>
                                    {{ $summary['total_streams'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Total Earnings
                                </th>
                                <td>
                                    ${{ $summary['total_earnings'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <a href="#unassigned-upc">Unassigned UPC Earnings <small class="text-success">(-)</small></a>
                                </th>
                                <td>
                                    ${{ $summary['unassigned_upc_earnings'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Total User Depositable Earnings
                                </th>
                                <td>
                                    ${{ $summary['depositable_total_earnings'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Singo Deduction <small class="text-success">(-20%)</small>
                                </th>
                                <td>
                                    ${{ $summary['deducted_amount'] }}
                                </td>
                            </tr>
                            <tr class="bg-gray-dark">
                                <th>
                                    <a href="#depositable-earnings">Total User Deposits</a>
                                </th>
                                <td>
                                    ${{ $summary['total_earnings_after_deduction'] }}
                                </td>
                            </tr>
                            <tr class="bg-teal">
                                <th>
                                    Left For Singo
                                </th>
                                <td>
                                    ${{ $summary['left_for_singo'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-gray-dark" id="depositable-earnings">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Depositable Earnings
                        <br>
                        <small class="text-info">
                            Report generated after {{ \App\Imports\Processors\OrchardReportProcessor::SINGO_AMOUNT_DEDUCTION_RATE }}% deduction of total earnings.
                        </small>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Orchard UPC</th>
                            <th>Artist Name</th>
                            <th data-breakpoints="xs">DSP</th>
                            <th data-breakpoints="xs">Total Streams</th>
                            <th data-breakpoints="xs">Total Earnings</th>
                            <th data-breakpoints="xs">Total Earnings<small class="text-success">(Depositable)</small></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($artists as $dsps)
                            @foreach($dsps as $dsp)
                                <tr style="{{ $loop->last ? 'border-bottom: solid 2px #527083;' : '' }}">
                                    <td>{{ $dsp['upc'] }}</td>
                                    <td><a href="/users?searchQuery={{ str_replace(" ", "+", $dsp['artist_name']) }}" target="_blank">{{ $dsp['artist_name'] }}</a></td>
                                    <td>{{ $dsp['dsp'] }}</td>
                                    <td>{{ $dsp['total_streams'] }}</td>
                                    <td>${{ number_format($dsp['total_earning'], 2) }}</td>
                                    <td>${{ number_format($dsp['total_earning_after_deduction'], 2) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-danger" id="unassigned-upc">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Unassigned UPC
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Orchard UPC</th>
                            <th>Artist Name</th>
                            <th data-breakpoints="xs">DSP</th>
                            <th data-breakpoints="xs">Total Streams</th>
                            <th data-breakpoints="xs">Total Earnings</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($missing_upc as $dsps)
                            @foreach($dsps as $dsp)
                                <tr style="{{ $loop->last ? 'border-bottom: solid 2px #527083;' : '' }}">
                                    <td>{{ $dsp['upc'] }}</td>
                                    <td><a href="/users?searchQuery={{ str_replace(" ", "+", $dsp['artist_name']) }}" target="_blank">{{ $dsp['artist_name'] }}</a></td>
                                    <td>{{ $dsp['dsp'] }}</td>
                                    <td>{{ $dsp['total_streams'] }}</td>
                                    <td>${{ number_format($dsp['total_earning'], 2) }}</td>
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
@endsection

@include('components.libs.footable')

@push('page_scripts')
    <script>
        $('.table').footable();

        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endpush
