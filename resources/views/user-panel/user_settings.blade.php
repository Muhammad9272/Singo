@extends('layouts.app')
@section('content')
    <div class="container-fluid settings">
        <div class="row">
            <div class="col-md-12 mb-20">
                <ul class="nav mlk-dtb-nav  d-flex justify-content-around"
                    id="profile-settings-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="personal-info-tab" data-toggle="tab"
                           href="#personal-info"
                           role="tab" aria-controls="personal-info" aria-selected="true">
                            Personal Information
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="payout-methods-tab" data-toggle="tab" href="#payout-methods"
                           role="tab" aria-controls="payout-methods" aria-selected="false">
                            Payout methods
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="change-password-tab" data-toggle="tab" href="#change-password"
                           role="tab" aria-controls="change-password" aria-selected="false">
                            Change Password
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#payment-settings">Payment Settings</a>
                    </li>

                    @if(auth()->user()->type == 3)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#subscription-tab">Subscription</a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="col-md-12">
                <div class="card" style="background:none!important">
                   
                    <div class="card-body">
                        @if(session('success'))
                            <p class="alert alert-success text-center">
                                {{ session('success') }}
                            </p>
                        @elseif(session('error'))
                            <p class="alert alert-danger text-center">
                                {{ session('error') }}
                            </p>
                        @endif

                        <div class="row">
                            <div class="col-md-12">

                                <div class="tab-content" id="profile-settings-tabs-content">
                                    <div class="tab-pane py-5 px-4 fade show active" id="personal-info" role="tabpanel"
                                         aria-labelledby="personal-info-tab">
                                        @include('user-panel.tabs.profile-information')
                                    </div>
                                    <div class="tab-pane py-5 px-4 fade" id="payout-methods" role="tabpanel"
                                         aria-labelledby="payout-methods-tab">
                                        @include('user-panel.tabs.payment-methods')
                                    </div>
                                    <div class="tab-pane py-5 px-4 fade" id="change-password" role="tabpanel"
                                         aria-labelledby="change-password-tab">
                                        @include('user-panel.tabs.change-password')
                                    </div>

                                    <div class="tab-pane py-5 px-4 fade" id="payment-settings" role="tabpanel"
                                         aria-labelledby="payment-settings-tab">
                                        @include('user-panel.tabs.payment-settings')
                                    </div>

                                    @if(auth()->user()->type == 3)
                                        <div class="tab-pane py-5 px-4 fade" id="subscription-tab" role="tabpanel"
                                             aria-labelledby="subscription-tab">
                                            @include('user-panel.tabs.subscription')
                                        </div>
                                    @endif
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
    <script>
        $(document).ready(function () {
            $("#wizard-picture").change(function () {
                readURL(this);
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


    </script>
    <script>
        var password = document.getElementById("new_password")
            , confirm_password = document.getElementById("confirm_new_password");

        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>
@endpush
