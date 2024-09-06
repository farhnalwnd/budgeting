<nav class="main-nav" role="navigation">

    <!-- Mobile menu toggle button (hamburger/x icon) -->
    <input id="main-menu-state" type="checkbox">
    <label class="main-menu-btn" for="main-menu-state">
        <span class="main-menu-btn-icon"></span> Toggle main menu visibility
    </label>

    <!-- Sample menu definition -->
    <ul id="main-menu" class="sm sm-blue">
        <li class="{{ request()->is('dashboard') ? 'current' : '' }}"><a href="{{ route('dashboard') }}"
                style="font-size: 18px;"><i data-feather="home" style="width: 18px; height: 18px;"><span
                        class="path1"></span><span class="path2"></span></i>Dashboard</a>
            <ul>
                <li><a href="{{ route('dashboard.dashboardProduction') }}"
                        class="{{ request()->is('dashboard/dashboard-production') ? 'current' : '' }}"><i
                            class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dashboard
                        Produksi</a></li>
                <li><a href="{{ route('dashboard.dashboardSales') }}"
                        class="{{ request()->is('dashboard/dashboard-sales') ? 'current' : '' }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Dashboard Pengiriman</a></li>
                <li><a href="{{ route('dashboard.dashboardWarehouse') }}"
                        class="{{ request()->is('dashboard/dashboard-warehouse') ? 'current' : '' }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Dashboard Gudang</a></li>
            </ul>
        </li>
        <li><a href="#" style="font-size: 18px;" class="{{ request()->is('dashboard/*') ? 'current' : '' }}"><i
                    data-feather="database" style="width: 18px; height: 18px;"></i>Data Dashboard</a>
            <ul>
                <li><a href="{{ route('dashboard.sales') }}"
                        class="{{ request()->is('dashboard/sales') ? 'current' : '' }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Sales Dashboard </a></li>
                <li><a href="{{ route('dashboard.inventory') }}"
                        class="{{ request()->is('dashboard/inventory') ? 'current' : '' }}"><i
                            class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Inventory
                        Dashboard</a></li>
                <li><a href="{{ route('dashboard.production') }}"
                        class="{{ request()->is('dashboard.production') ? 'current' : '' }}"><i
                            class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Production
                        Dashboard</a></li>
                <li><a href="{{ route('dashboard.production.standard') }}"
                        class="{{ request()->is('dashboard/standard-production/') ? 'current' : '' }}"><i
                            class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Standard
                        Production</a></li>
                <li><a href="{{ route('dashboard.warehouseindex') }}"
                        class="{{ request()->is('dashboard/standard-warehouse') ? 'current' : '' }}"><i
                            class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Standard
                        Warehouse</a></li>
                <li><a href="{{ route('dashboard.shipmentindex') }}"
                        class="{{ request()->is('dashboard/standard-shipment') ? 'current' : '' }}"><i
                            class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Standard
                        Shipment</a></li>
            </ul>
        </li>
            <li
                class="{{ request()->is('users*') || request()->is('department*') || request()->is('position*') || request()->is('level*') || request()->is('roles*') || request()->is('permissions*') || request()->is('get.master*') ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="users" style="width: 18px; height: 18px;"></i>
                    User Management
                </a>
                <ul>

                        <li><a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Users</a>
                        </li>


                        <li><a href="{{ route('department.index') }}"
                                class="{{ request()->is('department*') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Departments</a></li>


                        <li><a href="{{ route('position.index') }}" class="{{ request()->is('position*') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Positions</a></li>


                        <li><a href="{{ route('level.index') }}" class="{{ request()->is('level*') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Levels</a>
                        </li>

                        <li><a href="{{ route('roles.index') }}" class="{{ request()->is('roles*') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Roles</a>
                        </li>
                        <li><a href="{{ route('permissions.index') }}"
                                class="{{ request()->is('permissions*') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Permission</a></li>
                        <li><a href="{{ route('get.master') }}" class="{{ request()->is('get.master*') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Get Data
                                Master </a></li>
                        
                </ul>
            </li>
    </ul>
</nav>
