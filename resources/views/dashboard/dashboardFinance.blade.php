<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard Finance') }}
    </h2>
</x-slot>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-x-4">
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-primary">
                        <p class="text-fade mb-10">User</p>
                        <h2 class="my-0 fw-700 text-3xl">{{ $totalUser }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-users bg-primary-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-info">
                        <p class="text-fade mb-10">Item</p>
                        <h2 class="my-0 fw-700 text-3xl">{{ $totalItem }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-hand-holding-dollar bg-info-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-warning">
                        <p class="text-fade mb-10">Supplier</p>
                        <h2 class="my-0 fw-700 text-3xl">{{ $totalSupplier }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fa-sack-dollar bg-danger-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-danger">
                        <p class="text-fade mb-10">PR</p>
                        <h2 class="my-0 fw-700 text-3xl">{{ $totalRequisitionMaster }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-file-invoice bg-warning-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="box">
    <div class="box-header with-header">
        <div class="text-xl font-medium">Data PR </div>
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
                    ['Jumlah Dibuat', 30, 200, 100, 400, 150, 250, 30, 200, 100, 400, 150, 250],
                    ['Jumlah Approved', 130, 100, 140, 200, 250, 150, 230, 200, 300, 250, 350, 250],
                    ['Jumlah Unapproved', 50, 80, 120, 180, 100, 200, 50, 80, 120, 180, 100, 200]
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
    </script>
@endpush
