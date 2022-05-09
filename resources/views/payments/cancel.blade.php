@extends('layouts.app')

@section('content')
<div class="container-fluid ">

    <div class="row justify-content-center align-content-center">
        <div class="col-md-4 col-sm-12">
            <div class="card text-center mt-5">
                <div class="card-header">Payment canceled</div>
                <div class="card-body">
                    <h1 class="text-center text-danger mb-2"><i class="fa-3x fas fa-times-circle"></i></h1>
                    <p>Your payment has been canceled. If this happened unintentional, please create a new payment. If this happened while paying please contact the support.</p>
                    <a href = "{{ route('home') }}" class = "btn btn-success" >Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
