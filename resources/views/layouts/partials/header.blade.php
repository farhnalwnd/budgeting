<header class="main-header">
    <div class="inside-header">
        <div class="flex items-center logo-box justify-start">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="logo">
                <!-- logo-->
                <div class="logo-lg">
                    <span class="light-logo"><img src="{{ asset('assets') }}/images/logohitam.png" width="150"
                            alt="logo"></span>
                    <span class="dark-logo"><img src="{{ asset('assets') }}/images/logohitam.png" width="150"
                            alt="logo"></span>
                </div>
            </a>
        </div>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <div class="float-left">
                {{-- <ul class="header-megamenu nav">
                    <li class="btn-group inline-flex max-[991px]:hidden min-[992px]:inline-flex">
                        <div class="app-menu">
                            <div class="search-bx mx-5">
                                <form>
                                    <div class="flex input-group">
                                        <input type="search" class="form-control" placeholder="Search">
                                        <div class="input-group-append">
                                            <button class="btn" type="submit" id="button-addon3"><i
                                                    class="icon-Search"><span class="path1"></span><span
                                                        class="path2"></span></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul> --}}
            </div>

            <div class="navbar-custom-menu r-side inline-flex items-center float-right">
                <ul class="nav navbar-nav inline-flex items-center">
                    <li class="dropdown notifications-menu inline-flex rounded-md">
                        <label class="switch">
                            <a class="waves-effect waves-light btn-primary-light svg-bt-icon">
                                <input type="checkbox" data-mainsidebarskin="toggle" id="toggle_left_sidebar_skin">
                                <span class="switch-on"><i data-feather="moon"></i></span>
                                <span class="switch-off"><i data-feather="sun"></i></span>
                            </a>
                        </label>
                    </li>
                    <li class="dropdown notifications-menu btn-group ">
                        <a id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                            class="btn-primary-light svg-bt-icon hover:text-white hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-3 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            title="Notifications" type="button">
                            <div
                                class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full top-[0.7rem] end-[0.7rem] dark:border-gray-900">
                                0</div><i data-feather="bell"></i>
                            <div class="pulse-wave"></div>
                        </a>

                        <!-- Dropdown menu -->
                        <div id="dropdown"
                            class="dropdown-menu z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow !w-max dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownDefaultButton">
                                <li class="header">
                                    <div class="p-20 border-b">
                                        <div class="flexbox">
                                            <div>
                                                <div class="text-xl mb-0 mt-0">Notifications</div>
                                            </div>
                                            <div>
                                                <a href="#" class="text-danger">Clear All</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <!-- inner menu: contains the actual data -->

                                </li>
                                <li class="footer p-3 text-center border-t">
                                    <a href="component_notification.html">View all</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="inline-flex rounded-md nav-item max-[1199px]:hidden min-[1200px]:inline-flex">
                        <a href="#" data-provide="fullscreen"
                            class="waves-effect waves-light nav-link btn-primary-light svg-bt-icon" title="Full Screen">
                            <i data-feather="maximize"></i>
                        </a>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li class="inline-flex rounded-md nav-item max-[1199px]:hidden min-[1200px]:inline-flex">
                        <a href="#" data-toggle="control-sidebar" title="Setting"
                            class="waves-effect waves-light nav-link btn-primary-light svg-bt-icon">
                            <i data-feather="sliders"></i>
                        </a>
                    </li>

                    <!-- User Account-->
                    <li class="btn-group d-xl-inline-flex d-none">
                        <a href="#" id="dropdownDividerButton" data-dropdown-toggle="dropdownDivider-2"
                            class="justify-center btn-primary-light hover:text-white svg-bt-icon hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm !px-px !py-px text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="button"><img src="{{ asset('assets') }}/images/avatar/avatar-6.png"
                                class="avatar rounded-full !h-11 !w-11 mt-1" alt=""></a>

                        <!-- Dropdown menu -->
                        <div id="dropdownDivider-2"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 drop-shadow-lg"
                                aria-labelledby="dropdownDividerButton">
                                <li>
                                    <a href="{{ route('profile.edit') }}"
                                        class="items-center m-0 text-base flex px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i
                                            class="fa fa-user-circle-o me-3 text-xl" aria-hidden="true"> </i>
                                        My Profile</a>
                                </li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <li>
                                        <a :href="route('logout')"
                                            onclick="event.preventDefault();
                                                            this.closest('form').submit();"
                                            class="items-center m-0 text-base flex px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer"><i
                                                class=" fa fa-sign-out  me-3 text-xl"> </i>
                                            {{ __('Log Out') }}
                                        </a>

                                    </li>
                                </form>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
