@extends('layouts.frontend.master')
@section('title', 'Login')

@push('css')
<style>
    .auth-page-wrapper {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 150px 0 40px 0;
    }

    .auth-background-video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }

    .auth-background-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.85), rgba(46, 204, 113, 0.85));
        z-index: -1;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        padding: 25px;
        position: relative;
        z-index: 1;
    }

    .auth-logo {
        text-align: center;
        margin-bottom: 15px;
    }

    .auth-logo img {
        max-height: 50px;
        width: auto;
        margin-bottom: 8px;
    }

    .auth-title {
        text-align: center;
        margin-bottom: 20px;
    }

    .auth-title h4 {
        color: #2c3e50;
        font-weight: 700;
        font-size: 22px;
        margin: 0;
    }

    .auth-form-group {
        margin-bottom: 15px;
    }

    .auth-form-group label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 6px;
        display: block;
        font-size: 13px;
    }

    .auth-form-group .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .auth-form-group .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .auth-btn-submit {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-size: 15px;
        font-weight: 600;
        width: 100%;
        color: white;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        margin-top: 10px;
    }

    .auth-btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.5);
    }

    .auth-links {
        text-align: center;
        margin-top: 15px;
    }

    .auth-links p {
        margin-bottom: 6px;
        font-size: 13px;
    }

    .auth-links a {
        color: #3498db;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }

    .auth-links a:hover {
        color: #2980b9;
    }

    .auth-forgot-link {
        text-align: right;
        margin-top: 5px;
        margin-bottom: 15px;
    }

    .auth-forgot-link a {
        color: #6c757d;
        font-size: 14px;
        text-decoration: none;
    }

    .auth-forgot-link a:hover {
        color: #3498db;
    }

</style>
@endpush

@section('content')
<div class="auth-page-wrapper">
    <video class="auth-background-video" autoplay muted loop>
        <source src="{{ asset('frontend/video/banner.mp4') }}" type="video/mp4">
    </video>
    <div class="auth-background-overlay"></div>

    <div class="container">
        <div class="row">
            <div class="col-md-5 mx-auto">
                <div class="auth-card">
                    <div class="auth-logo">
                        <img src="{{ $settings->app_logo_url ?? asset('frontend/logo/logo.png') }}" alt="{{ $settings->app_name ?? 'Logo' }}">
                    </div>
                    <div class="auth-title">
                        <h4>{{__('messages.login_title')}}</h4>
                    </div>

                    <form class="login-form" action="{{ route('admin.login') }}" method="post">
                        {{ csrf_field() }}
                        <div class="auth-form-group">
                            <label for="email">Email or Phone Number <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="email" id="email"
                                placeholder="Enter your email or phone number">
                        </div>
                        <div class="auth-form-group">
                            <label for="password">Password <span style="color: red">*</span></label>
                            <input type="password" class="form-control" id="password" placeholder="Enter your password"
                                autocomplete="off" name="password">
                        </div>
                        <div class="auth-forgot-link">
                            <a href="#">{{__('messages.forgot_password')}}</a>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="auth-btn-submit">{{__('messages.login')}}</button>
                            <div class="auth-links">
                                <p class="mt-2 mb-1">{{__('messages.dont_have_account')}} <a href="{{ route('member.show.register') }}">Go to Registration</a></p>
                                <p class="mb-0">
                                    <a href="{{ route('landing') }}">
                                        <i class="fa-solid fa-arrow-left"></i> Back to Home
                                    </a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <x-generic-validation-error-toastr />
    <script>
        $(document).ready(function() {
            const loginForm = $('.login-form');

            loginForm.validate({
                rules: {
                    email: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 1,
                    }
                },
                messages: {
                    email: {
                        required: 'Please enter your phone number or email',
                    },
                    password: {
                        required: 'Please enter your password',
                    }
                },
                errorClass: 'text-danger',
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                submitHandler: function(form) {
                    let button = $(loginForm).find('button[type="submit"]');
                    window.loadingButton(button);
                    form.submit();
                }
            });
        });
    </script>
@endpush
