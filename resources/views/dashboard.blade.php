<x-app-layout>
    @section('title')
Dashboard
    @endsection
    @can('view dashboard Finance')
        @include('dashboard.dashboardFinance')
    @endcan

    @can('view dashboard Sales & Marketing')
        @include('dashboard.dashboardSales')
    @endcan

    @can('view dashboard R&D')
        @include('dashboard.dashboardR&D')
    @endcan



</x-app-layout>
