<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard Finance') }}
    </h2>
</x-slot>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-x-4">
    @foreach(['User' => ['totalUser', 'fa-users', 'primary'], 'Item' => ['totalItem', 'fa-hand-holding-dollar', 'info'], 'Supplier' => ['totalSupplier', 'fa-truck', 'warning'], 'PR' => ['totalRequisitionMaster', 'fa-file-invoice', 'danger']] as $label => $data)
        <div>
            <div class="box pull-up">
                <div class="box-body">
                    <div class="flex justify-between items-center">
                        <div class="bs-5 ps-10 border-{{ $data[2] }}">
                            <p class="text-fade mb-10">{{ $label }}</p>
                            <h2 class="my-0 fw-700 text-3xl">{{ ${$data[0]} }}</h2>
                        </div>
                        <div class="icon">
                            <i class="fa-solid {{ $data[1] }} bg-{{ $data[2] }}-light me-0 fs-24 rounded-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="box">
    <div class="box-header with-header">
        <div class="text-xl font-medium">Data PR</div>
    </div>
    <div class="my-4 mx-20">
        <label class="block font-medium text-sm text-gray-700">Pilih Tahun:</label>
        <select id="selectYear" class="mt-1 block w-36 p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @foreach($availableYears as $year)
                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
    </div>
    <div class="box-body">
        <div id="chart"></div>
    </div>
</div>

@push('scripts')
    <script>
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                x: 'x',
                columns: [
                    ['x', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    ['Jumlah Dibuat'],
                    ['Jumlah Approved'],
                    ['Jumlah Unapproved']
                ],
                type: 'bar',
                types: {
                    'Jumlah Approved': 'line',
                    'Jumlah Unapproved': 'line'
                },
                groups: [
                    ['Jumlah Dibuat', 'Jumlah Approved', 'Jumlah Unapproved']
                ]
            },
            axis: {
                x: {
                    type: 'category'
                }
            },
            bar: {
                width: {
                    ratio: 0.5
                }
            }
        });

        function updateChart(year) {
            $.ajax({
                url: '/api/requisitions/' + year,
                type: 'GET',
                success: function(response) {
                    chart.load({
                        columns: [
                            response.x,
                            ['Jumlah Dibuat'].concat(response.jumlahDibuat),
                            ['Jumlah Approved'].concat(response.jumlahApproved),
                            ['Jumlah Unapproved'].concat(response.jumlahUnapproved)
                        ]
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        document.getElementById('selectYear').addEventListener('change', function() {
            updateChart(this.value);
        });

        updateChart('{{ $selectedYear }}');
    </script>
@endpush
