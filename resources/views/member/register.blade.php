@extends('layouts.frontend.master')
@section('title', 'Member Registration')

@push('css')
<style>
    .auth-page-wrapper {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 0;
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


    #profile_preview {
        border: 3px solid #3498db;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
            <div class="col-md-10 mx-auto">
                <div class="auth-card">
                    <div class="auth-logo">
                        <img src="{{ $settings->app_logo_url ?? asset('frontend/logo/logo.png') }}" alt="{{ $settings->app_name ?? 'Logo' }}">
                    </div>
                    <div class="auth-title">
                        <h4>Create Your Account</h4>
                    </div>

                    <form id="memberRegisterForm" class="login-form" action="{{ route('member.register') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="auth-form-group">
                                    <label for="first_name">First Name <span style="color: red">*</span></label>
                                    <input type="text" name="first_name" class="form-control" id="first_name"
                                           placeholder="Enter First Name" value="{{ old('first_name') }}">
                                    @error('first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="auth-form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" id="last_name"
                                           placeholder="Enter Last Name" value="{{ old('last_name') }}">
                                    @error('last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="auth-form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                           placeholder="example@gmail.com" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="auth-form-group">
                                    <label for="phone">Phone Number <span style="color: red">*</span></label>
                                    <input type="text" name="phone" class="form-control" id="phone"
                                           placeholder="01XXXXXXXXX" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="auth-form-group">
                                    <label for="password">Password <span style="color: red">*</span></label>
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="Enter Password">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="auth-form-group">
                                    <label for="password_confirmation">Confirm Password <span style="color: red">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                                           placeholder="Confirm Password">
                                </div>
                            </div>
                        </div>

                        <div class="auth-form-group">
                            <label for="address">Address</label>
                            <textarea name="address" class="form-control" id="address" rows="3"
                                      placeholder="Enter Address">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="auth-form-group">
                            <label for="profile_pic">Profile Photo</label>
                            <input type="file" name="profile_pic" class="form-control" id="profile_pic"
                                   accept="image/*">
                            @error('profile_pic')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="mt-2 text-center">
                                <img id="profile_preview" src="" alt="Profile Preview"
                                     style="max-width: 200px; max-height: 200px; display: none; border-radius: 10px;">
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="auth-btn-submit">
                                <i class="fa-solid fa-user-plus"></i> Register
                            </button>
                            <div class="auth-links">
                                <p class="mt-2 mb-1">Already have an account? <a href="{{ route('login') }}">Login Here</a></p>
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
            $('#profile_pic').on('change', function(event) {
                if (event.target.files && event.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile_preview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }
            });

            const registerForm = $('#memberRegisterForm');

            registerForm.validate({
                rules: {
                    first_name: {
                        required: true,
                        maxlength: 191,
                    },
                    last_name: {
                        maxlength: 191,
                    },
                    email: {
                        email: true,
                        maxlength: 191,
                    },
                    phone: {
                        required: true,
                        pattern: /^(01[3-9]\d{8})$/,
                    },
                    password: {
                        required: true,
                        minlength: 5,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: '#password',
                    },
                    address: {
                        maxlength: 500,
                    },
                    profile_pic: {
                        accept: 'image/*',
                    }
                },
                messages: {
                    first_name: {
                        required: 'Please enter your first name',
                        maxlength: 'First name cannot exceed 191 characters',
                    },
                    last_name: {
                        maxlength: 'Last name cannot exceed 191 characters',
                    },
                    email: {
                        email: 'Please enter a valid email address',
                        maxlength: 'Email cannot exceed 191 characters',
                    },
                    phone: {
                        required: 'Please enter your phone number',
                        pattern: 'Please enter a valid phone number (01XXXXXXXXX)',
                    },
                    password: {
                        required: 'Please enter a password',
                        minlength: 'Password must be at least 5 characters long',
                    },
                    password_confirmation: {
                        required: 'Please confirm your password',
                        equalTo: 'Passwords do not match',
                    },
                    address: {
                        maxlength: 'Address cannot exceed 500 characters',
                    },
                    profile_pic: {
                        accept: 'Please select a valid image file',
                    }
                },
                errorClass: 'text-danger',
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    if (element.closest('.row').length) {
                        error.insertAfter(element.closest('.auth-form-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                submitHandler: function(form) {
                    let button = $(registerForm).find('button[type="submit"]');
                    window.loadingButton(button);
                    form.submit();
                }
            });
        });
    </script>
@endpush
