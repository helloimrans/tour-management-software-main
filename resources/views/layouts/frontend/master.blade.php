<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') | {{ $settings->app_name ?? 'Tour Management' }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/landing-responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    @stack('css')
</head>
<body>
    @include('layouts.frontend.includes.navbar')

    <main>
        @yield('content')
    </main>

    @include('layouts.frontend.includes.footer')

    <script>
        @if (\Illuminate\Support\Facades\Session::has('message'))
            let alertType = {!! json_encode(\Illuminate\Support\Facades\Session::get('alert-type', 'info')) !!};
            let alertMessage = {!! json_encode(\Illuminate\Support\Facades\Session::get('message')) !!};
            let alerter = toastr[alertType];
            alerter ? alerter(alertMessage) : toastr.error("toastr alert-type " + alertType + " is unknown");
        @endif

        window.loadingButton = (button) => {
            button.attr("disabled", true).css("cursor", "default");
            button.html('<span class="submitting"><i class="fas fa-sync-alt fa-spin"></i> Loading...</span>');
        }

        window.revertLoadingButton = (button, prevHtml) => {
            button.removeAttr("disabled").css("cursor", "pointer");
            button.html(prevHtml);
        }
    </script>

    @stack('js')
</body>
</html>
