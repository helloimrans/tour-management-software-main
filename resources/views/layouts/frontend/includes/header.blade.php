<section class="header-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="logo-box">
                    <img src="{{ asset('frontend/logo/logo.svg') }}" alt="Logo">
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div class="d-flex ml-auto justify-content-end">
                    @if (auth()->check())
                        <a href="{{ route('admin.logout') }}"
                            class="btn bg-white text-dark btn-sm px-2 py-0 ml-2 fw-600"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    @endif
                </div>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</section>

@push('js')

@endpush
