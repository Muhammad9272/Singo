@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <x-home.stat-card
                    value="{{ auth()->user()->albums()->count() }}"
                    label="Total albums"
                />
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <x-home.stat-card
                    value="{{ auth()->user()->albums()->where('status', 2)->count() }}"
                    label="Distributed albums"
                />
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <x-home.stat-card
                    value="{{ auth()->user()->albums()->where('status', -1)->count() }}"
                    label="Declined albums"
                />
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <x-home.stat-card
                    value="{{ auth()->user()->balance }}€"
                    label="Balance"
                />
            </div>
            <!-- ./col -->
        </div>
        <div class="row mt-3">
            @foreach($plans as $plan)
                <div class="col-12 col-md-4 d-flex my-2 align-items-stretch flex-column">
                    <x-home.plan-card
                        :plan="$plan"
                    />
                </div>
            @endforeach
        </div>
    </div>


    @if ($alert == 1)


        <!-- Button trigger modal -->
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

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function () {
            $('#btnShowAlert').click();
        })
    </script>
@endpush
