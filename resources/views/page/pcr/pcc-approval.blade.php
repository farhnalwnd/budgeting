<x-app-layout>
    @section('title')
        Initiator Approval
    @endsection

    <div class="content-header">
        <div class="flex items-center justify-between">

            <h4 class="page-title text-2xl font-medium"></h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page"> PCR</li>
                        <li class="breadcrumb-item active" aria-current="page"> PCC Approval PCR</li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>

    <section class="content">
        <div class="row">

            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="page-title text-2xl font-medium">List PCC Approval PCR</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table mb-0 w-full" id="tablePCCApproval">
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
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#tablePCCApproval').DataTable({

                });
            });
        </script>
    @endpush


</x-app-layout>
