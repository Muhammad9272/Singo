@extends('layouts.app')

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-12">
                <x-home.stat-card
                    value="{{ auth()->user()->albums()->count() }}"
                    label="Total albums"
                    icon="{{ asset('image/icons/album.svg') }}"
                />
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-12">
                <x-home.stat-card
                    value="{{ auth()->user()->albums()->where('status', 2)->count() }}"
                    label="Distributed albums"
                />
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-12">
                <x-home.stat-card
                    value="{{ auth()->user()->albums()->where('status', -1)->count() }}"
                    label="Declined albums"
                />
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-12 balnce-bg" >
                <x-home.stat-card
                    value="{{$pcurrency_symbol}}{{ auth()->user()->balance }}"
                    label="Balance"
                    icon="{{ asset('image/icons/dollar.svg') }}"
                    
                />
            </div>
            <!-- ./col -->
        </div>
        <div class="row">
            <div class="col-md-8">
                  <div class="card-body h-chart-card-body">
                    <x-charts.artist-stream-home />
                  </div>
            </div>
            <div class="col-md-4">
                <div class="card-body h-chart-card-body" style="padding: 35px 12px;">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <h5 class="text-white" style="font-size:14px">All Time Earnings</h5>
                            <h3 id="stream-count">{{$totalearnings}}</h3>
                        </div>
                        <div class="d-flex c-streams">
                            <a href="#" style="font-size:14px" class="secondarycolor" data-toggle="modal" data-target="#monthlyreportmodal">
                                  Show More
                            </a>
                            
                        </div>
                    </div>
                    <div>
                        @foreach ($monthlyreport as $key=>$earning)
                            @if($key<6)
                                <div class="singo-prog-bar">
                                    <div class="d-flex justify-content-between cont">
                                            <p class="text-grey1 mb-0 fz-12">{{$earning['month']}}, {{$earning['year']}}</p>
                                            <p class="mb-0">{{$pcurrency_symbol}}{{number_format($earning['price'],2)}}</p>
                                    </div>
                                    <div class="progress" style="height: 3px;"> 
                                      <div class="progress-bar {{$earning['price']==$highest?'bg-success':'bg-warning'}}" role="progressbar" style="width: {{$highest>0?(($earning['price']/$highest)*100):0}}%" aria-valuenow="{{$earning['price']}}" aria-valuemin="0" aria-valuemax="{{$highest}}"></div>
                                    </div>
                                </div>
                            @endif
                            
                        @endforeach                        

                    </div>
                </div>

            </div>
        </div>
        <div class="row home-planss" >
            @foreach($plans as $plan)
                <div class="col-12  col-md-6 col-lg-4">
                    <x-home.plan-card
                        :plan="$plan"
                    />
                </div>
            @endforeach
        </div>
    </div>


    @if ($alert == 1)


        Button trigger modal
        <button type="button" id="btnShowAlert" class="btn btn-primary d-none" data-toggle="modal"
                data-target="#showAlert"></button>

        <!-- Modal -->
        <div class="modal fade" id="showAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showModelLabel">{{ $welcomeAlert->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="showDiv">
                            {!!html_entity_decode($welcomeAlert->content)!!}
                        </div>
                        <div>
                            <div class="mt-2">
                                <button class="btn btn-danger" style="width: 49%;" data-dismiss="modal">Close</button>
                                <a href="{{ route('welcome.dnd', $welcomeAlert->id) }}" class="btn btn-primary"
                                   style="width: 49%;">Don't show this again</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif



<!-- Modal Monthly earning -->
<div class="modal fade" id="monthlyreportmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">All Time Earnings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                        @foreach ($monthlyreport as $key=>$earning)
                            <div class="singo-prog-bar">
                                <div class="d-flex justify-content-between cont">
                                        <p class="text-grey1 mb-0 fz-12">{{$earning['month']}}, {{$earning['year']}}</p>
                                        <p class="mb-0">{{$pcurrency_symbol}}{{number_format($earning['price'],2)}}</p>
                                </div>
                                <div class="progress" style="height: 3px;"> 
                                      <div class="progress-bar {{$earning['price']==$highest?'bg-success':'bg-warning'}}" role="progressbar" style="width: {{$highest>0?(($earning['price']/$highest)*100):0}}%" aria-valuenow="{{$earning['price']}}" aria-valuemin="0" aria-valuemax="{{$highest}}"></div>
                                </div>
                            </div>
                            
                        @endforeach
      </div>
      
    </div>
  </div>
</div>



@endsection



@push('page_scripts')
    <script>
        $(document).ready(function () {
            $('#btnShowAlert').click();
        })
        
    </script>

@endpush

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="https://unpkg.com/chart.js@^2.9.3/dist/Chart.min.js"></script>
    <script src="{{ asset('js/chartjs.js') }}"></script>
    
    {{-- <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script> --}}
@endsection
