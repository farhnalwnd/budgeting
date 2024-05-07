<x-app-layout>
    @can('view dashboard Finance')
        @include('dashboard.dashboardFinance')
    @endcan

    @can('view dashboard Sales & Marketing')
        @include('dashboard.dashboardSales')
    @endcan
</x-app-layout>
