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
                <li><a href="{{ route('report.index') }}"
                    class="{{ request()->is('dashboard/report') ? 'current' : '' }}"><i class="icon-Commit"><span
                            class="path1"></span><span class="path2"></span></i>Report </a></li>
            </ul>
        </li>
        <li><a href="#" style="font-size: 18px;" class="{{ request()->is('management/*') ? 'current' : '' }}"><i
                    data-feather="database" style="width: 18px; height: 18px;"></i>Data Management</a>
            <ul>
                <li><a href="{{ route('budget-list.index') }}"
                    class="{{ request()->is('management/budget-list') ? 'current' : '' }}"><i class="icon-Commit"><span
                            class="path1"></span><span class="path2"></span></i>Budget List </a></li>
                <li><a href="{{ route('purchase-request.index') }}"
                    class="{{ request()->is('management/PurchaseRequest') ? 'current' : '' }}"><i class="icon-Commit"><span
                            class="path1"></span><span class="path2"></span></i>Purchase Request</a></li>
                <li><a href="{{ route('budget-allocation.index') }}"
                        class="{{ request()->is('management/budget-allocation') ? 'current' : '' }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Budget Allocation</a></li>
                <li><a href="{{ route('budget-request.index') }}"
                    class="{{ request()->is('management/budget-request') ? 'current' : '' }}"><i class="icon-Commit"><span
                            class="path1"></span><span class="path2"></span></i>Budget Request</a></li>
                <li><a href="{{ route('budget-request.approval') }}"
                    class="{{ request()->is('management/budget-approval') ? 'current' : '' }}"><i class="icon-Commit"><span
                            class="path1"></span><span class="path2"></span></i>Budget Request Approval</a></li>
                <li><a href="{{ route('category.index') }}"
                        class="{{ request()->is('management/category') ? 'current' : '' }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Category </a></li>
                <li><a href="{{ route('activity.index') }}"
                        class="{{ request()->is('management/activity') ? 'current' : '' }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Activity Log</a></li>
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
                        <li><a href="{{ route('approver.index') }}" class="{{ request()->is('approver.index*') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Approver</a></li>
                        
                </ul>
            </li>
    </ul>
</nav>
