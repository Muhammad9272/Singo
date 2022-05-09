@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-12">
            <div class="card mt-5">
                <div class="card-header">
                    <span class="float-left">
                        <h4>Release New Music</h4>
                    </span>
                    <span class="float-right">
                        <a href="{{ route('home') }}" class="btn btn-info btn-sm">Back</a>
                    </span>
                </div>

                <div class="card-body">
                @if (session('resent'))
                            <div class="alert alert-success" role="alert">A new verification link has been sent to
                                your email address
                            </div>
                        @endif
                    
                    <div class="row">
                        <div class="col-md-10 offset-md-2">
                            <span class = "">
                                <span class="font-weight-bold text-danger">Your Email is not verified.</span>
                                <span class="font-weight-bold text-danger">Please verify mail to release new album.</span><br>
                                <div class=" mt-3 mb-2">If you didn't receive an email</div>
                                    <form class="d-inline mt-2" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf

                                    <button type="submit" class="btn btn-primary">
                                        {{ __('click here to request a new link') }}
                                    </button>
                                </form>
                            </span>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

