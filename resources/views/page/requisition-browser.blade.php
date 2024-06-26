<x-app-layout>
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium">Requisition Browse</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page"> Requisition</li>
                        <li class="breadcrumb-item active" aria-current="page"> Requisition Browse</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-3">
                <button type="button"
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-2 py-1.5 text-center me-2 mb-2 float-right"
                    onclick="location.href='{{ route('rqm.index') }}'">Make New PR</button>
            </div>
            <div class="col-12">
                <div class="box">
                    <div class="box-body">
                        <table id="browserTable" class=" row-border hover">
                            <thead>
                                <tr class="text-dark" role="row">
                                    <th>Requisition Number</th>
                                    <th>Requested By</th>
                                    <th>Entered Date</th>
                                    <th>Need Date</th>
                                    <th>Due Date</th>
                                    <th>Route To</th>
                                    <th>Supplier</th>
                                    <th>Buyer</th>
                                    <th>Close Date</th>
                                    <th>Approval Status</th>
                                    <th>Approved Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rqmbrowsers as $item)
                                    <tr data-modal-target="modal-{{ $item->rqmNbr }}" class="open-modal">
                                        <td>{{ $item->rqmNbr }}</td>
                                        <td>{{ $item->enterby }}</td>
                                        <td>{{ $item->rqmReqDate }}</td>
                                        <td>{{ $item->rqmNeedDate }}</td>
                                        <td>{{ $item->rqmDueDate }}</td>
                                        <td>{{ $item->routeToApr }}</td>
                                        <td>{{ $item->rqmVend }}</td>
                                        <td>{{ $item->routeToBuyer }}</td>
                                        <td>{{ $item->rqmClsDate }}</td>
                                        <td>{{ $item->rqmAprvStat }}</td>
                                        <td>{{ $item->approved_date }}</td>
                                        <td>
                                            <div class="flex flex-nowrap">
                                                <a href="{{ route('rqm.edit', ['rqmNbr' => $item->rqmNbr]) }}"
                                                    class="btn btn-sm btn-primary edit-btn">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('rqm.delete', $item->rqmNbr) }}" method="POST"
                                                    class="inline-block" id="deleteForm{{ $item->rqmNbr }}">
                                                    @csrf
                                                    <input type="hidden" name="rqmNbr" value="{{ $item->rqmNbr }}">
                                                    <button type="button"
                                                        onclick="confirmDelete('{{ $item->rqmNbr }}')"
                                                        class="btn btn-sm btn-danger delete-btn ml-2">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
    @foreach ($rqmbrowsers as $item)
        <div id="modal-{{ $item->rqmNbr }}" tabindex="-1"
            class="fixed inset-0 m-auto z-50 p-4 overflow-x-hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center hidden"
            aria-modal="true" role="dialog">
            <div class="relative w-full max-w-7xl max-h-full overflow-y-auto">
                <!-- Modal content -->
                <div class="relative bg-white dark:bg-gray-800 text-black dark:text-white rounded-lg shadow"
                    style="margin-top: 10%;">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-2 md:p-3 border-b border-gray-200 dark:border-gray-600 rounded-t">
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="modal-{{ $item->rqmNbr }}">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                            </svg>
                            <span class="sr-only">Tutup modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class=" p-4 md:p-5 space-y-4 text-sm overflow-y-auto max-h-[calc(100vh-10rem)]">
                        <div id="show-data{{ $item->rqmNbr }}">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <img src="{{ asset('sinarmeadow.png') }}" alt="QAD Logo" class="w-16 h-16">
                                </div>
                                <div class="flex items-center">
                                    <p class="text-lg font-bold">Requisition Report</p><br>
                                    <p class="ml-4 text-lg font-bold">SMII</p>
                                </div>
                                <div class="text-sm font-medium">
                                    <p>{{ $item->created_at }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                                <div>
                                    <p class="text-sm font-medium">Req Nbr: {{ $item->rqmNbr }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Supplier:{{ $item->supplier->vd_addr ?? '' }}</p>
                                    @if ($item->rqmVend)
                                        <p class="text-sm font-medium">{{ $item->supplier->ad_name }}</p>
                                        <p class="text-sm font-medium">{{ $item->supplier->ad_line1 }}</p>
                                        <p class="text-sm font-medium">{{ $item->supplier->ad_line3 }}</p>
                                        <p class="text-sm font-medium">{{ $item->supplier->ad_line3 }}</p>
                                        <p class="text-sm font-medium">{{ $item->supplier->ad_city }}</p>
                                        <p class="text-sm font-medium">INDONESIA</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Sinar Meadow
                                        International Indonesia</p>
                                    <p class="text-sm font-medium">Kawasan Industri
                                        Pulogadung No 6</p>
                                    <p class="text-sm font-medium">Blok III.5.16-18</p>
                                    <p class="text-sm font-medium">Pulo Ayang I No.6, RW.9
                                    </p>
                                    <p class="text-sm font-medium">Jakarta 13920</p>
                                    <p class="text-sm font-medium">INDONESIA</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm font-medium">Rqstn Date: {{ $item->rqmReqDate }}</p>
                                    <p class="text-sm font-medium">Need Date: {{ $item->rqmNeedDate }}</p>
                                    <p class="text-sm font-medium">Due Date: {{ $item->rqmDueDate }}</p>

                                </div>
                                <div>
                                    <p class="text-sm font-medium">Entered By: {{ $item->enterby }}</p>
                                    <p class="text-sm font-medium">Requested By: {{ $item->rqmRqbyUserid }}</p>
                                    <p class="text-sm font-medium">End User: {{ $item->rqmEndUserid }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Cost Center: {{ $item->rqmCc }}</p>
                                    <p class="text-sm font-medium">Currency: {{ $item->rqmCurr }}</p>
                                    <p class="text-sm font-medium">Direct Matls: {{ $item->rqmDirect }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Aprvl Status: {{ $item->rqmAprvStat }}</p>
                                    <p class="text-sm font-medium">Remarks: {{ $item->rqmRmks }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-sm font-medium">
                                    <p>Disc Pct: 0.00%</p>
                                </div>
                                <div class="text-sm font-medium">
                                    <p>Discount Table: 0.00%</p>
                                </div>
                            </div>

                            <table class="w-full text-sm font-medium">
                                <thead>
                                    <tr>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Line</th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Site</th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Item Number
                                        </th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Supplier
                                        </th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Req Qty UM
                                        </th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Unit Cost
                                        </th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Disc%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $extCostTotal = 0;
                                        $maxExtCostTotal = 0;
                                    @endphp
                                    @foreach ($item->rqdDets as $detail)
                                        @php
                                            $extCost = $detail->rqdReqQty * $detail->rqdPurCost;
                                            $maxExtCost = $detail->rqdReqQty * $detail->rqdPurCost;
                                            $extCostTotal += $extCost;
                                            $maxExtCostTotal += $maxExtCost;
                                        @endphp
                                        <tr>
                                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                                {{ $loop->iteration }}</td>
                                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                                {{ $item->rqmSite }}</td>
                                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                                {{ $detail->rqdPart }}</td>
                                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                                {{ $detail->rqdVend }}</td>
                                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                                {{ $detail->rqdReqQty }} {{ $detail->rqdUm }} </td>
                                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                                {{ number_format($detail->rqdPurCost, 2, ',', '.') }}</td>
                                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">0.00%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                @if (strlen($detail->rqdCmt) > 50)
                                                    <p class="text-xs">{{ substr($detail->rqdCmt, 0, 50) }}</p>
                                                    <p class="text-xs">{{ substr($detail->rqdCmt, 50) }}</p>
                                                @else
                                                    <p class="text-xs">{{ $detail->rqdCmt }}</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="text-sm font-medium">
                                    <p>Requisition Totals</p>
                                    <p>Ext Cost Total: {{ number_format($extCostTotal, 2, ',', '.') }} IDR</p>
                                    <p>Max Ext Cost Total: {{ number_format($maxExtCostTotal, 2, ',', '.') }} IDR</p>
                                </div>
                                <div class="text-sm font-medium">
                                    <p>{{ number_format($maxExtCostTotal, 2, ',', '.') }} IDR</p>
                                    <p>{{ number_format($maxExtCostTotal, 2, ',', '.') }} IDR</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 dark:border-gray-600 rounded-b">
                        <button type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            onclick="printRequisition('{{ $item->rqmNbr }}')">Print</button>
                        <button data-modal-hide="modal-{{ $item->rqmNbr }}" type="button"
                            class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(rqmNbr) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form jika konfirmasi di-approve
                    document.getElementById('deleteForm' + rqmNbr).submit();
                }
            });
        }
    </script>

    {{-- <script>
        @if (session('success'))
            swal("Success", "{{ session('success') }}", "success");
        @endif

        @if (session('error'))
            swal("Error", "{{ session('error') }}", "error");
        @endif
    </script> --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var table = $('#browserTable').DataTable({
                    responsive: false,
                    deferRender: true,
                    pagingType: 'simple_numbers',
                });

                $('#browserTable').css('width', '100%');

                $('#browserTable tbody').on('click', 'tr', function(e) {
                    if ($(e.target).is('.edit-btn, .delete-btn, .edit-btn *, .delete-btn *')) {
                        return;
                    }
                    var modalId = $(this).data('modal-target');
                    $('#' + modalId).removeClass('hidden').addClass('flex');
                });

                $('button[data-modal-hide]').on('click', function() {
                    var modalId = $(this).data('modal-hide');
                    $('#' + modalId).removeClass('flex').addClass('hidden');
                });

                $('#browserTable tbody').on('show.bs.modal', 'tr', function() {
                    var itemNbr = $(this).find('td:first').text().trim();
                    var modalId = $(this).data('modal-target');

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('rqm.edit', ':rqmNbr') }}'.replace(':rqmNbr', itemNbr),
                        success: function(data) {
                            $('#' + modalId).find('#show-data' + itemNbr).html(data);
                        }
                    });
                });
            });

            function printRequisition(rqmNbr) {
                var url = '{{ route('rqm.print', ':rqmNbr') }}'.replace(':rqmNbr', rqmNbr);
                window.open(url, '_blank');
            }
        </script>
    @endpush
</x-app-layout>
