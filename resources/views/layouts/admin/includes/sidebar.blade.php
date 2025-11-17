<aside class="main-sidebar sidebar-dark-primary custom-bg-blue elevation-4">
    <a href="{{ url('/') }}" class="brand-link logo-switch pb-4 border-0">
        @auth
            <span class="logo-xl">
                <img src="{{ asset('frontend/logo/logo.svg') }}" style="height: 55px; margin-left: 55px" alt="logo" />
            </span>
            <span class="logo-xs">
                <img src="{{ asset('frontend/logo/logo.svg') }}" width="90%" alt="logo" />
            </span>
        @endauth
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="overflow-y: auto;">
        <!-- Sidebar Menu -->
        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                @if(auth()->check() && auth()->user()->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE)
                    {{-- Travel Agency Menu --}}
                    <x-nav-item routeName="travel.agency.dashboard" permissionKey="dashboard-menu" iconClass="fa-gauge-high" label="Dashboard" />
                    <x-nav-item routeName="tour.index" permissionKey="tour-menu" iconClass="fa-map-location-dot" label="Tours" />
                    <x-nav-item routeName="travel.agency.members" permissionKey="member-management-menu" iconClass="fa-users" label="Members" />
                    <x-nav-item routeName="expense.index" permissionKey="expense-menu" iconClass="fa-money-bill" label="Expenses" />
                    <x-nav-item routeName="payment.index" permissionKey="payment-menu" iconClass="fa-credit-card" label="Payments" />
                @else
                    {{-- Platform Admin Menu --}}
                    <x-nav-item routeName="admin.dashboard" permissionKey="dashboard-menu" iconClass="fa-gauge-high"
                        label="Dashboard" />

                    <x-nav-item routeName="" iconClass="fa-users" permissionKey="user-management-menu"
                        label="User Management" :submenu="[
                            [
                                'route' => 'admin.user.index',
                                'icon' => 'fa-user-shield',
                                'permissionKey' => 'admin-user-menu',
                                'label' => 'Admin Users',
                            ],
                            [
                                'route' => 'general.user.index',
                                'icon' => 'fa-users-gear',
                                'permissionKey' => 'general-user-menu',
                                'label' => 'General Users',
                            ],
                            [
                                'route' => 'permissions.index',
                                'icon' => 'fa-lock',
                                'permissionKey' => 'permissions-menu',
                                'label' => 'Permissions',
                            ],
                            [
                                'route' => 'roles.index',
                                'icon' => 'fa-user-tag',
                                'permissionKey' => 'roles-menu',
                                'label' => 'Roles',
                            ],
                        ]" />

                    <x-nav-item routeName="tour.index" permissionKey="tour-menu"
                        iconClass="fa-map-location-dot" label="Tours" />

                    <x-nav-item routeName="travel.agency.index" permissionKey="travel-agency-menu"
                        iconClass="fa-building" label="Travel Agencies" />

                    <x-nav-item routeName="setting" iconClass="fa-gear" permissionKey="settings-menu" label="Settings" />
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
