@extends('layouts.frontend.master')
@section('title', 'Login')

@push('css')
@endpush

@section('content')
    <section class="login-page">
        <div class="container">
            <div class="row">
                <div class="col-md-8 align-self-center">
                    @include('auth.auth-slider')
                </div>
                <div class="col-md-4">
                    <div class="register-box mt-4 mt-md-0">
                        <div class="bg-white-custom radius-14 padding-30">
                            <div class="login-title">
                                <p>{{__('messages.login')}}</p>
                                <h4>{{__('messages.login_title')}}</h4>
                            </div>

                            <form class="login-form" action="{{ route('admin.login') }}" method="post">
                                {{ csrf_field() }}
                                <div class="custom-form-group">
                                    <label for="name">{{__('messages.mobile_number')}} <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="email"
                                        placeholder="01X-XXXXXXXX">
                                </div>
                                <div class="custom-form-group">
                                    <label for="name">{{__('messages.password')}} <span style="color: red">*</span></label>
                                    <input type="password" class="form-control" placeholder="Enter password"
                                        autocomplete="off" name="password">
                                </div>
                                <div class="">
                                    <a class="fs-14 text-dark d-inline-block mt-2" href="#">{{__('messages.forgot_password')}}</a>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn text-light radius-10 custom-bg-blue px-5 mt-4 fs-15 w-100">{{__('messages.login')}}
                                    </button>
                                    <p class="mt-3 text-gray fs-14">{{__('messages.dont_have_account')}} <a
                                            class="custom-color-secondary fw-500"
                                            href="#">{{__('messages.signup')}}</a></p>

                                            <div class="copyright-login">
                                                <p>Copyright {{ date('Y') }} All rights Reserved</p>
                                            </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <x-generic-validation-error-toastr />
    <script>
        const loginForm = $('.login-form');
        loginForm.validate({
            rules: {
                email: {
                    required: true,
                    //email: true
                },
                password: {
                    required: true,
                }
            },
            submitHandler: function(htmlForm) {
                let button = $(loginForm).find('button[type="submit"]');
                button.attr("disabled", true).css("cursor", "default");
                button.html('<span class="submitting"><i class="fas fa-sync-alt"></i> Loading...</span>');
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush
