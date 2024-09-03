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
                        <li class="breadcrumb-item active" aria-current="page"> Initiator Approval PCR</li>
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
                        <h4 class="page-title text-2xl font-medium">List Initiator Approval PCR</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table mb-0 w-full" id="tableInitiatorApproval">
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
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#tableInitiatorApproval').DataTable({

                    "scrollCollapse": true,
                    "paging": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            });
        </script>
    @endpush


</x-app-layout>
