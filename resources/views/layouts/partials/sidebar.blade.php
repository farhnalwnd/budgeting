<nav class="main-nav" role="navigation">

    <!-- Mobile menu toggle button (hamburger/x icon) -->
    <input id="main-menu-state" type="checkbox">
    <label class="main-menu-btn" for="main-menu-state">
        <span class="main-menu-btn-icon"></span> Toggle main menu visibility
    </label>

    <!-- Sample menu definition -->
    <ul id="main-menu" class="sm sm-blue">
        <li class="{{ request()->is('dashboard') ? 'current' : '' }}"><a href="{{ route('dashboard') }}"  style="font-size: 18px;"><i data-feather="home" style="width: 18px; height: 18px;"><span class="path1"></span><span class="path2"></span></i>Dashboard</a>
        </li>
        @canany(['view user', 'view department', 'view position', 'view level', 'view role', 'view permission', 'get data master'])
        <li class="{{ request()->is('users*') || request()->is('department*') || request()->is('position*') || request()->is('level*') || request()->is('roles*') || request()->is('permissions*') || request()->is('get.master*') ? 'current' : '' }}">
            <a href="#" style="font-size: 18px;">
                <i data-feather="users" style="width: 18px; height: 18px;"></i>
                User Management
            </a>
            <ul>
                @can('view user')
                <li><a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Users</a></li>
                @endcan
                @can('view department')
                <li><a href="{{ route('department.index') }}" class="{{ request()->is('department*') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Departments</a></li>
                @endcan
                @can('view position')
                <li><a href="{{ route('position.index') }}" class="{{ request()->is('position*') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Positions</a></li>
                @endcan
                @can('view level')
                <li><a href="{{ route('level.index') }}" class="{{ request()->is('level*') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Levels</a></li>
                @endcan
                @can('view role')
                <li><a href="{{ route('roles.index') }}" class="{{ request()->is('roles*') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Roles</a></li>
                @endcan
                @can('view permission')
                <li><a href="{{ route('permissions.index') }}" class="{{ request()->is('permissions*') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Permission</a></li>
                @endcan
                @can('get data master')
                <li><a href="{{ route('get.master') }}" class="{{ request()->is('get.master*') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Get Data Master </a></li>
                @endcan
            </ul>
        </li>
        @endcanany
        @canany(['view requisition', 'view browse requisition','view report requisition'])
        <li><a href="#" style="font-size: 18px;" class="{{ request()->is('rqm*') ? 'current' : '' }}"><i data-feather="printer" style="width: 18px; height: 18px;"></i>Requisition</a>
            <ul>
                @can('view requisition')
                <li><a href="{{ route('rqm.index') }}" class="{{ request()->is('rqm-maintenance') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Requisition Maintenance</a></li>
                @endcan
                @can('view browse requisition')
                <li><a href="{{ route('rqm.browser') }}" class="{{ request()->is('rqm-browser') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Requisition Browse</a></li>
                @endcan
                @can('view report requisition')
                <li><a href="{{ route('rqm.report') }}" class="{{ request()->is('rqm-report') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Requisition Report</a></li>
                @endcan
                @can('view approval requisition')
                <li><a href="{{ route('rqm.approval') }}" class="{{ request()->is('rqm-approval') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Requisition Approval </a></li>
                @endcan
            </ul>
        </li>
        @endcanany
        @canany(['view pcr', 'view browse pcr'])
        <li class="{{ request()->is('pcr*') ? 'current' : '' }}"><a href="#" style="font-size: 18px;" ><i data-feather="check-circle" style="width: 18px; height: 18px;"></i>PCR</a>
            <ul>
                @can('view browse pcr')
                <li class="{{ request()->is('pcr') ? 'current' : '' }}"><a href="{{ route('pcr.index') }}" ><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List PCR</a></li>
                @endcan
                @can('view approval pcr')
                <li class="{{ request()->is('pcr/initiator-approvals') || request()->is('pcr/committee-approvals') ? 'current' : '' }}"><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Approvals</a>
                    <ul>
                        @can('view initiator approval pcr')
                        <li class="{{ request()->is('pcr/initiator-approvals') ? 'current' : '' }}"><a href="{{ route('pcr.initiator.approval') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Initiator Approvals</a></li>
                        @endcan
                        @can('view committee approval pcr')
                        <li  class="{{ request()->is('pcr/committee-approvals') ? 'current' : '' }}"><a href="{{ route('pcr.committee.approval') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Committee Approvals</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('view master data pcr')
                <li class="{{ request()->is('pcr.master*') ? 'current' : '' }}"><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Master Data</i></a>
                    <ul>
                        @can('view initiators pcr')
                        <li><a href="{{ route('pcr.initiators') }}" class="{{ request()->is('pcr/initiators') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Initiators</a></li>
                        @endcan
                        @can('view nature of changes pcr')
                        <li><a href="{{ route('pcr.nature.of.changes') }}" class="{{ request()->is('pcr/nature-of-changes') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Nature Of Changes</a></li>
                        @endcan
                        @can('view pcc pcr')
                        <li><a href="{{ route('pcr.pcc') }}" class="{{ request()->is('pcr/pcc') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>PCC</a></li>
                        @endcan
                        @can('view products pcr')
                        <li><a href="{{ route('pcr.products') }}" class="{{ request()->is('pcr/products') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Products</a></li>
                        @endcan
                        @can('view revision pcr')
                        <li><a href="{{ route('pcr.revision') }}" class="{{ request()->is('pcr/revision') ? 'current' : '' }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Revision PCR</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany


    </ul>
</nav>
