@extends('layouts.app')

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">Total Streams</h4>
                </div>
                <div class="card-body">
                    <x-charts.artist-streams />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">Total Downloads</h4>
                </div>
                <div class="card-body">
                    <x-charts.artist-downloads />
                </div>
            </div>
        </div>
        <div class="col-md-12">

            <div>
                <i class="text-blue fas fa-exclamation-circle"></i>
                "DSP_30" = Streams under 30 seconds
            </div>
        </div>
    </div>
@endsection


@section('third_party_scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="https://unpkg.com/chart.js@^2.9.3/dist/Chart.min.js"></script>
    <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>
@endsection
