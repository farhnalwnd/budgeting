<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard R&D') }}
    </h2>
</x-slot>

<div>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>R&D</span>
        </li>
    </ul>
</div>

@php
    $totalPCR = 150;
    $inProgressPCR = 50;
    $doneChangePCR = 70;
    $doneNotChangePCR = 30;
    $latestInitiatorApprove = ['Initiator 1', 'Initiator 2', 'Initiator 3', 'Initiator 4', 'Initiator 5'];
    $latestPCCApprove = ['PCC 1', 'PCC 2', 'PCC 3', 'PCC 4', 'PCC 5', 'PCC 6', 'PCC 7', 'PCC 8'];
    $data = [
        'totalPCR' => $totalPCR,
        'inProgressPCR' => $inProgressPCR,
        'doneChangePCR' => $doneChangePCR,
        'doneNotChangePCR' => $doneNotChangePCR
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-x-4">
    @foreach (['Total PCR Created' => ['totalPCR', 'fa-vials', 'primary'], 'In Progress' => ['inProgressPCR', 'fa-spinner', 'info'], 'Done (Change)' => ['doneChangePCR', 'fa-check-circle', 'success'], 'Done (Not Change)' => ['doneNotChangePCR', 'fa-times-circle', 'danger']] as $label => $data)
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

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <div class="box">
            <div class="box-body analytics-info">
                <div class="text-xl font-medium ">DATA PCR CHART</div>
                <div id="pcr-doughnut" style="height:350px;"></div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="box">
            <div class="box-header with-header">
                <div class="text-xl font-medium">Latest Initiator Approve</div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table mb-0 w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">Initiator Name</th>
                                <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">No Registrasi PCR</th>
                                <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">Approval Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $latestInitiatorApprove = [
                                    ['name' => 'Initiator 1', 'email' => 'initiator1@example.com', 'no_reg' => '24PCR0001', 'product_name' => 'Product A', 'approval_status' => 'Approved'],
                                    ['name' => 'Initiator 2', 'email' => 'initiator2@example.com', 'no_reg' => '24PCR0002', 'product_name' => 'Product B', 'approval_status' => 'Approved'],
                                    ['name' => 'Initiator 3', 'email' => 'initiator3@example.com', 'no_reg' => '24PCR0003', 'product_name' => 'Product C', 'approval_status' => 'Approved'],
                                    ['name' => 'Initiator 4', 'email' => 'initiator4@example.com', 'no_reg' => '24PCR0004', 'product_name' => 'Product D', 'approval_status' => 'Approved'],
                                    ['name' => 'Initiator 5', 'email' => 'initiator5@example.com', 'no_reg' => '24PCR0005', 'product_name' => 'Product E', 'approval_status' => 'Approved'],
                                ];
                            @endphp
                            @foreach ($latestInitiatorApprove as $approve)
                                <tr class="border-b">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $approve['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $approve['email'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $approve['no_reg'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $approve['product_name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $approve['approval_status'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box">
    <div class="box-header with-header">
        <div class="text-xl font-medium">Latest PCC Approve</div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table mb-0 w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">No Registrasi PCR</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">PCC Name</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">Date Approval</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-500 uppercase tracking-wider">Approval Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $latestPCCApprove = [
                            ['no_reg' => '24PCR0001', 'product_name' => 'Product A', 'pcc_name' => 'R&D Manager', 'email' => 'rdmanager@example.com', 'date_approval' => '2023-01-01', 'approval_status' => 'Approved'],
                            ['no_reg' => '24PCR0002', 'product_name' => 'Product B', 'pcc_name' => 'Product Service', 'email' => 'productservice@example.com', 'date_approval' => '2023-01-02', 'approval_status' => 'Approved'],
                            ['no_reg' => '24PCR0003', 'product_name' => 'Product C', 'pcc_name' => 'Marketing Manager', 'email' => 'marketingmanager@example.com', 'date_approval' => '2023-01-03', 'approval_status' => 'Approved'],
                            ['no_reg' => '24PCR0004', 'product_name' => 'Product D', 'pcc_name' => 'Sales and Marketing', 'email' => 'salesmarketing@example.com', 'date_approval' => '2023-01-04', 'approval_status' => 'Approved'],
                            ['no_reg' => '24PCR0005', 'product_name' => 'Product E', 'pcc_name' => 'Manufacture Manager', 'email' => 'manufacturemanager@example.com', 'date_approval' => '2023-01-05', 'approval_status' => 'Approved'],
                            ['no_reg' => '24PCR0006', 'product_name' => 'Product F', 'pcc_name' => 'Quality Manager', 'email' => 'qualitymanager@example.com', 'date_approval' => '2023-01-06', 'approval_status' => 'Approved'],
                            ['no_reg' => '24PCR0007', 'product_name' => 'Product G', 'pcc_name' => 'Supply Chain Dep', 'email' => 'supplychain@example.com', 'date_approval' => '2023-01-07', 'approval_status' => 'Approved'],
                            ['no_reg' => '24PCR0008', 'product_name' => 'Product H', 'pcc_name' => 'General Manager', 'email' => 'generalmanager@example.com', 'date_approval' => '2023-01-08', 'approval_status' => 'Approved'],
                        ];
                    @endphp
                    @foreach ($latestPCCApprove as $approve)
                        <tr class="border-b">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $approve['no_reg'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $approve['product_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $approve['pcc_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $approve['email'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $approve['date_approval'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $approve['approval_status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@push('scripts')
<script src="{{ asset('assets') }}/vendor_components/echarts/dist/echarts-en.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var chartDom = document.getElementById('pcr-doughnut');
        if (chartDom) {
            var myChart = echarts.init(chartDom);
            var option;

            option = {
                title: {
                    text: 'Data PCR',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['Done Change', 'Done Not Change', 'In Progress', 'Total PCR']
                },
                color: ['#689f38', '#38649f', '#389f99', '#ee1044'],
                // Enable drag recalculate
                calculable: true,
                series: [
                    {
                        name: 'PCR Status',
                        type: 'pie',
                        radius: '50%',
                        center: ['50%', '50%'],
                        data: [
                            { value: {{ $doneChangePCR }}, name: 'Done Change' },
                            { value: {{ $doneNotChangePCR }}, name: 'Done Not Change' },
                            { value: {{ $inProgressPCR }}, name: 'In Progress' },
                            { value: {{ $totalPCR }}, name: 'Total PCR' }
                        ],
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };

            option && myChart.setOption(option);
        }
    });
</script>

@endpush


