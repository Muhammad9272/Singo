@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7" style="margin-top: 2%">
                <div class="box">
                    <h3 class="box-title">Please Verify Your Email Address</h3>

                    <div class="box-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">A new verification link has been sent to
                                your email address
                            </div>
                        @endif
                        <p>
                            Before proceeding, please check your email for a verification link.
                            <br><br>
                            If you did not receive the email
                        </p>

                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf

                            <button type="submit" class="btn btn-primary">
                                {{ __('Click here to request a new link') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
