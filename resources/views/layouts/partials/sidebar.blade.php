<nav class="main-nav" role="navigation">

    <!-- Mobile menu toggle button (hamburger/x icon) -->
    <input id="main-menu-state" type="checkbox">
    <label class="main-menu-btn" for="main-menu-state">
        <span class="main-menu-btn-icon"></span> Toggle main menu visibility
    </label>

    <!-- Sample menu definition -->
    <ul id="main-menu" class="sm sm-blue">
        <li><a href="{{ route('dashboard') }}"><i data-feather="home"><span class="path1"></span><span
                        class="path2"></span></i>Dashboard</a>
            {{-- <ul>
                <li><a href="index.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Dashboard</a></li>
                <li><a href="index-2.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Dashboard 2</a></li>
                <li><a href="index-3.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Dashboard 3</a></li>
            </ul> --}}
        </li>
        <li><a href="#"><i data-feather="users"></i>User Management</a>
            <ul>
                <li><a href="{{ route('users.index') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Users</a></li>
                <li><a href="{{ route('department.index') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Departments</a></li>
                <li><a href="{{ route('position.index') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Positions</a></li>
                <li><a href="{{ route('level.index') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Levels</a></li>
                <li><a href="{{ route('roles.index') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Roles</a></li>
                <li><a href="{{ route('permissions.index') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Permission</a></li>
            </ul>
        </li>
        <li><a href="#"><i data-feather="printer"></i>Requisition</a>
            <ul>
                <li><a href="{{ route('rqm.index') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Requisition Maintenance</a></li>
                <li><a href="{{ route('get.master') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Get Data Master </a></li>
                <li><a href="{{ route('rqm.browser') }}"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Requisition Browse</a></li>
                {{-- <li><a href="widgets_social.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Social</a></li> --}}
                {{-- <li><a href="widgets_statistic.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Statistic</a></li> --}}
                {{-- <li><a href="widgets_weather.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Weather</a></li> --}}
                {{-- <li><a href="widgets.html"><i class="icon-Commit"><span class="path1"></span><span
                                class="path2"></span></i>Widgets</a></li> --}}
            </ul>
        </li>
    </ul>
</nav>
