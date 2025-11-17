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
                <x-nav-item routeName="admin.dashboard" permissionKey="dashboard-menu" iconClass="bx bxs-dashboard"
                    label="Dashboard" />

                <x-nav-item routeName="" iconClass="fa-users" permissionKey="user-management-menu"
                    label="User Management" :submenu="[
                        [
                            'route' => 'admin.user.index',
                            'icon' => 'fa-user-circle',
                            'permissionKey' => 'admin-user-menu',
                            'label' => 'Admin Users',
                        ],
                        [
                            'route' => 'general.user.index',
                            'icon' => 'fa-user-tag',
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
                            'icon' => 'fa-tasks',
                            'permissionKey' => 'roles-menu',
                            'label' => 'Roles',
                        ],
                    ]" />

                <x-nav-item routeName="radio.stations.index" permissionKey="radio-stations-menu"
                    iconClass="bx bxs-radio" label="Radio Stations" />

                <x-nav-item routeName="tour.index" permissionKey="tour-menu"
                    iconClass="fa-map-marked-alt" label="Tours" />

                <x-nav-item routeName="setting" iconClass="fa-cog" permissionKey="settings-menu" label="Settings" />
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
