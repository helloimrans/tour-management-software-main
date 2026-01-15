<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-2">
                    @if($settings->app_logo_url ?? null)
                        <img src="{{ $settings->app_logo_url }}" alt="{{ $settings->app_name ?? 'Logo' }}" style="max-height: 50px; width: auto;">
                    @else
                        <i class="fas fa-plane-departure" style="font-size: 2rem;"></i>
                    @endif
                </div>
                <p>{{ $settings->app_slogan ?? 'Your trusted partner for amazing travel experiences.' }}</p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('landing') }}" class="text-white-50">Home</a></li>
                    <li><a href="{{ route('tours.listing') }}" class="text-white-50">Tours</a></li>
                    @auth
                        <li><a href="{{ auth()->user()->user_type == \App\Models\User::ADMIN_USER_CODE ? route('admin.dashboard') : route('member.dashboard') }}" class="text-white-50">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('member.show.register') }}" class="text-white-50">Register</a></li>
                        <li><a href="{{ route('login') }}" class="text-white-50">Login</a></li>
                    @endauth
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact</h5>
                <p><i class="fas fa-envelope"></i> {{ $settings->app_email ?? 'info@tourmanagement.com' }}</p>
                <p><i class="fas fa-phone"></i> {{ $settings->app_phone ?? '01755430927' }}</p>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center">
            <p class="mb-0">&copy; {{ date('Y') }} {{ $settings->app_name ?? 'Tour Management' }}. All rights reserved.</p>
        </div>
    </div>
</footer>
