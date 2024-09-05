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
    </ul>
</nav>
