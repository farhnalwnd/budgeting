<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/sinarmeadow.png') }}">

    <link rel="manifest" href="{{ asset('/manifest.json') }}"> --}}
    <link rel="icon" href="{{ url('assets/images/sinarmeadow.png') }}">

    <title>{{ 'INTRA SMII' }} - @yield('title')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/vendors_css.css">


    {{-- <script src="{{ asset('assets') }}/3.4.3"></script> --}}

    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/tailwind.min.css">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/horizontal-menu.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/style.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/skin_color.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/custom.css">

    @stack('css')

<style>
 body {
    zoom: 0.8;  /* Mengatur skala zoom ke 80% */
}
</style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="layout-top-nav light-skin theme-primary fixed-manu">

    <div class="wrapper">
        <div id="loader"></div>
        @include('layouts.partials.header')

        @include('layouts.partials.sidebar')
        <!-- Content Wrapper. Contains page content -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="px-4 md:px-0">
                @include('sweetalert::alert')
                {{ $slot   }}
            </div>
        </div>
        <!-- /.content-wrapper -->


        @include('layouts.partials.footer')
        <!-- Side panel -->


        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>

    </div>




    <script type="text/javascript" src="{{ asset('assets') }}/ajax/libs/jQuery-slimScroll/1.3.8/jquery-3.7.1.min.js">
    </script>
    <script type="text/javascript" src="{{ asset('assets') }}/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js">
    </script>
    <!-- Vendor JS -->
    <script src="{{ asset('assets') }}/src/js/vendors.min.js"></script>
    <script src="{{ asset('assets') }}/icons/feather-icons/feather.min.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/echarts/dist/echarts-en.min.js"></script>

    <script src="{{ asset('assets') }}/src/js/tailwind.min.js"></script>

    <script src="{{ asset('assets') }}/vendor_components/Flot/jquery.flot.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/Flot/jquery.flot.resize.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/Flot/jquery.flot.pie.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/Flot/jquery.flot.categories.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/datatable/datatables.min.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/echarts/dist/echarts-en.min.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/jquery-toast-plugin-master/src/jquery.toast.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/sweetalert/jquery.sweet-alert.custom.js"></script>

    <script src="{{ asset('assets') }}/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/c3/d3.min.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/c3/c3.min.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/raphael/raphael.min.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/morris.js/morris.min.js"></script>


    <!-- Warehouse App -->
    <script src="{{ asset('assets') }}/src/js/demo.js"></script>
    <script src="{{ asset('assets') }}/src/js/jquery.smartmenus.js"></script>
    <script src="{{ asset('assets') }}/src/js/menus.js"></script>
    <script src="{{ asset('assets') }}/src/js/template.js"></script>
    <script src="{{ asset('assets') }}/src/js/pages/dashboard2.js"></script>
    <script src="{{ asset('assets') }}/src/js/pages/calendar.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/jquery-steps-master/build/jquery.steps.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/jquery-validation-1.17.0/dist/jquery.validate.min.js"></script>
    <script src="{{ asset('assets') }}/src/js/pages/steps.js"></script>
    <script src="{{ asset('assets') }}/vendor_components/sweetalert/sweetalert.min.js"></script>
    <script src="{{ asset('assets') }}/src/js/pages/data-table.js"></script>

    <script src="{{ asset('assets') }}/src/js/pages/toastr.js"></script>
    <script src="{{ asset('assets') }}/src/js/pages/notification.js"></script>

@stack('scripts')


</body>

</html>
