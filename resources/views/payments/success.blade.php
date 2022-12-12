@extends('layouts.app')

@section('content')
    <div class="container-fluid ">

        <div class="row justify-content-center align-content-center">
            <div class="col-md-4 col-sm-12">
                <div class="card text-center mt-5">
                    <div class="card-header">Payment completed</div>
                    <div class="card-body">
                        <h1 class="text-center text-success mb-2"><i class="fa-3x fas fa-check-circle"></i></h1>
                        <p>Your payment has been processed. Your rank should appear within a few minutes. Please contact the support if you don't receive your rank within the next ten minutes</p>
                        <a href = "{{ route('home') }}" class = "btn btn-success" >Back to Home</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
