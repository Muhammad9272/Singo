@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-4 text-center">
                <div class="row">
                    <div class="col-sm-12 mt-5 bgWhite">
                        <small>We have sent you a verification mail, you must verify your email to continue.</small>
                        <form action="{{ route('verification.resend') }}" method="POST">
                            @csrf
                            <p>Didn't receive a code? <button class="btn btn-link">Send again</button></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
