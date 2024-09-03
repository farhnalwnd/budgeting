<x-app-layout>
    @section('title')
        Requisition Approval
    @endsection
    @push('css')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.3/css/select.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
    @endpush

    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium"></h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page"> Requisition</li>
                        <li class="breadcrumb-item active" aria-current="page"> Requisition Approval</li>
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
                        <h4 class="page-title text-2xl font-medium">Requisition Approval</h4>
                    </div>
                    <div class="box-body">
                        <button id="bulkApprovedBtn" class="btn btn-success mb-3">Bulk Approved</button>
                        <div class="table-responsive">
                            <table id="tableApproval"
                                class="!border-separate table text-fade table-bordered w-full display nowrap">
                                <thead>
                                    <tr class="text-dark" role="row">
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="filled-in chk-col-danger"
                                                    id="checkAllMaster">
                                                <label class="custom-control-label" for="checkAllMaster"></label>
                                            </div>
                                        </th>
                                        <th>PR Number</th>
                                        <th>Req By</th>
                                        <th>Entered Date</th>
                                        <th>Need Date</th>
                                        <th>Due Date</th>
                                        <th>Route To</th>
                                        <th>Supplier</th>
                                        <th>Buyer</th>
                                        <th>Appr Status</th>
                                        <th>NonPO</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rqmapprovals as $item)
                                        <tr data-modal-target="modal-{{ $item->rqmNbr }}" class="open-modal">
                                            <td>
                                                <input type="checkbox" id="md_checkbox_{{ $loop->iteration }}"
                                                    class="filled-in chk-col-danger bulk-approved-checkbox"
                                                    data-rqmNbr="{{ $item->rqmNbr }}">
                                                <label for="md_checkbox_{{ $loop->iteration }}"></label>
                                            </td>
                                            <td>{{ $item->rqmNbr }}</td>
                                            <td>{{ $item->enterby }}</td>
                                            <td>{{ $item->rqmReqDate }}</td>
                                            <td>{{ $item->rqmNeedDate }}</td>
                                            <td>{{ $item->rqmDueDate }}</td>
                                            <td>{{ $item->routeToApr }}</td>
                                            <td>{{ $item->rqmVend }}</td>
                                            <td>{{ $item->routeToBuyer }}</td>
                                            <td>{{ $item->rqmAprvStat }}</td>
                                            <td>{{ $item->rqm__log01 == 'true' ? 'Yes' : 'No' }}</td>
                                            <td class="flex gap-2">
                                                <a href="{{ route('rqm.approve', $item->rqmNbr) }}"
                                                    class="btn btn-success btn-sm approved">Approve</a>
                                                <a href="{{ route('rqm.decline', $item->rqmNbr) }}"
                                                    class="btn btn-danger btn-sm rejected">Reject</a>
                                            </td>
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

    @foreach ($rqmapprovals as $item)
        <div id="modal-{{ $item->rqmNbr }}" tabindex="-1"
            class="fixed inset-0 m-auto z-50 p-4 overflow-x-hidden overflow-y-auto bg-black bg-opacity-50 flex items-start justify-center hidden"
            aria-modal="true" role="dialog">
            <div class="relative w-full max-w-7xl max-h-full overflow-y-auto">
                <div class="relative bg-white dark:bg-gray-200 text-black rounded-lg shadow" style="margin-top: 10%;">
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
                    <div class="p-4 md:p-5 space-y-4 text-sm overflow-y-auto max-h-[calc(100vh-10rem)]">
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
                                    <p class="text-sm font-medium">Sinar Meadow International Indonesia</p>
                                    <p class="text-sm font-medium">Kawasan Industri Pulogadung No 6</p>
                                    <p class="text-sm font-medium">Blok III.5.16-18</p>
                                    <p class="text-sm font-medium">Pulo Ayang I No.6, RW.9</p>
                                    <p class="text-sm font-medium">Jakarta 13920</p>
                                    <p class="text-sm font-medium">INDONESIA</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm font-medium">Rqstn Date:
                                        {{ \Carbon\Carbon::parse($item->rqmReqDate)->format('d/m/Y') }}</p>
                                    <p class="text-sm font-medium">Need Date:
                                        {{ \Carbon\Carbon::parse($item->rqmNeedDate)->format('d/m/Y') }}</p>
                                    <p class="text-sm font-medium">Due Date:
                                        {{ \Carbon\Carbon::parse($item->rqmDueDate)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Entered By: {{ $item->enterby }}</p>
                                    <p class="text-sm font-medium">End User: {{ $item->rqmEndUserid }}</p>
                                    <p class="text-sm font-medium">PR Non PO: {{ $item->rqm__log01 }}</p>
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
                                        <th
                                            class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center ">
                                            Line</th>
                                        <th
                                            class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center ">
                                            Site</th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-left ">
                                            Item Number</th>
                                        <th
                                            class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                            Supplier</th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">
                                            Req Qty</th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-left">
                                            UM</th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">
                                            Unit Cost</th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">
                                            Disc%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $extCostTotal = 0;
                                        $maxExtCostTotal = 0;
                                    @endphp
                                    @foreach ($item->rqdDets as $detail)
                                        @php
                                            $reqQty = floatval($detail->rqdReqQty);
                                            $purCost = floatval($detail->rqdPurCost);
                                            $extCost = $reqQty * $purCost;
                                            $maxExtCost = $reqQty * $purCost;
                                            $extCostTotal += $extCost;
                                            $maxExtCostTotal += $maxExtCost;
                                        @endphp
                                        <tr>
                                            <td
                                                class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                                {{ $detail->rqdLine }}</td>
                                            <td
                                                class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                                {{ $item->rqmSite }}</td>
                                            <td
                                                class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-left">
                                                {{ $detail->rqdPart }}</td>
                                            <td
                                                class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                                {{ $detail->rqdVend }}</td>
                                            <td
                                                class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">
                                                {{ number_format(floatval($detail->rqdReqQty), 0, ',', '.') }}</td>
                                            <td
                                                class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-left">
                                                {{ $detail->rqdUm ? $detail->rqdUm : '' }}</td>
                                            <td
                                                class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">
                                                {{ number_format(floatval($detail->rqdPurCost), 2, ',', '.') }}</td>
                                            <td
                                                class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">
                                                0.00%</td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                @if (strlen($detail->rqdCmt) > 50)
                                                    @php
                                                        $comment = $detail->rqdCmt;
                                                        while (strlen($comment) > 50) {
                                                            echo '<p class="text-xs text-left">' .
                                                                substr($comment, 0, 50) .
                                                                '</p>';
                                                            $comment = substr($comment, 50);
                                                        }
                                                    @endphp
                                                    <p class="text-xs text-left">{{ $comment }}</p>
                                                @else
                                                    <p class="text-xs text-left">{{ $detail->rqdCmt }}</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="grid grid-cols-3 md:grid-cols-4 gap-4 mt-5">
                                <div class="text-sm font-medium">
                                    <p>Requisition Totals</p>
                                    <p>Ext Cost Total:</p>
                                    <p>Max Ext Cost Total:</p>
                                </div>
                                <div class="text-sm font-medium mt-5">
                                    <p>{{ number_format($extCostTotal, 2, ',', '.') }} {{ $item->rqmCurr }}</p>
                                    <p>{{ number_format($maxExtCostTotal, 2, ',', '.') }} {{ $item->rqmCurr }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-between p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 dark:border-gray-600 rounded-b">

                        <div>
                            <a href="{{ route('rqm.approve', $item->rqmNbr) }}"
                                class="btn btn-success btn-md approved">Approved</a>
                            <a href="{{ route('rqm.decline', $item->rqmNbr) }}"
                                class="btn btn-danger btn-md rejected">Rejected</a>
                            <button data-modal-hide="modal-{{ $item->rqmNbr }}" type="button"
                                class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-3 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                        </div>


                        @can('resend requisition')
                        <div class="flex justify-end">
                                @if ($maxExtCostTotal > 5000001)
                                    <div>
                                        <label for="resendTo-{{ $item->rqmNbr }}"
                                            class="text-sm font-medium text-gray-700">Resend To:</label>
                                        <select id="resendTo-{{ $item->rqmNbr }}" name="resendTo"
                                            class="ml-2 w-32 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            @if ($item->routeToApr != 'melvin')
                                                <option value="{{ $item->routeToApr }}">{{ $item->routeToApr }}</option>
                                            @endif
                                            <option value="melvin">Melvin</option>
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" id="resendTo-{{ $item->rqmNbr }}" name="resendTo"
                                        value="{{ $item->routeToApr }}">
                                @endif

                                <button type="button"
                                    class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                    onclick="resendRequisition('{{ $item->rqmNbr }}')">Resend</button>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script type="text/javascript" src="{{ asset('assets') }}/ajax/libs/jQuery-slimScroll/1.3.8/jquery-3.7.1.min.js">
    </script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js">
        < /> <
        script src = "https://cdn.datatables.net/2.0.8/js/dataTables.jqueryui.js" >
    </script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/dataTables.searchPanes.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/searchPanes.jqueryui.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.3/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.3/js/select.jqueryui.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function resendRequisition(rqmNbr) {
                var resendTo = document.getElementById('resendTo-' + rqmNbr).value;
                var url = '{{ route('rqm.resend', [':rqmNbr', ':resendTo']) }}'.replace(':rqmNbr', rqmNbr).replace(':resendTo',
                    resendTo);
                window.location.href = url;
            }

            document.addEventListener('DOMContentLoaded', function() {
                var table = new DataTable('#tableApproval', {
                    lengthMenu: [10, 25, 50, {
                        label: 'All',
                        value: -1
                    }],
                    layout: {
                        top1: {
                            searchPanes: {
                                layout: 'columns-11'
                            },
                            searchBuilder: {
                                layout: 'columns-11'
                            }
                        }
                    },
                    columnDefs: [{
                            searchPanes: {
                                show: true,
                                initCollapsed: true
                            },
                            targets: [1, 2, 3, 4, 5, 6, 7, 8, 9,10]
                        },
                        {
                            searchPanes: {
                                show: false
                            },
                            targets: [0, 11]
                        },
                        {
                            orderable: false,
                            targets: [0, 11]
                        }
                    ]
                });

                $('#checkAllMaster').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.bulk-approved-checkbox').each(function() {
                            $(this).prop('checked', true);
                        });
                    } else {
                        $('.bulk-approved-checkbox').prop('checked', false);
                    }
                });

                $('#bulkApprovedBtn').on('click', function() {
                    var selectedItems = [];
                    $('.bulk-approved-checkbox:checked').each(function() {
                        var rqmNbr = $(this).data('rqmnbr');
                        selectedItems.push(rqmNbr);
                    });

                    var countSelected = selectedItems.length;

                    if (countSelected === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No items selected',
                            text: 'Please select at least one item to approve.',
                            confirmButtonText: '<button style="color: green;">OK</button>'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Are you sure?',
                        html: `You will approve <strong>${countSelected}</strong> PR.<br><br> You will not be able to undo this!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, approve!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('rqm.bulkApproved') }}',
                                data: {
                                    rqmNbrs: selectedItems
                                },
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire('Approved!',
                                            'Selected items have been approved.', 'success')
                                        .then((result) => {
                                            window.location.reload();
                                        });
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                    Swal.fire('Error!',
                                        'An error occurred while approving items.',
                                        'error');
                                }
                            });
                        }
                    });
                });

                // Event listener untuk membuka modal
                $('#tableApproval tbody').on('click', 'tr', function(e) {
                    if ($(e.target).closest(
                            '.btn-success, .btn-danger, .btn-success *, .btn-danger *, :checkbox, label')
                        .length) {
                        return;
                    }
                    var modalId = $(this).data('modal-target');
                    $('#' + modalId).removeClass('hidden').addClass('flex');
                });

                // Event listener untuk menutup modal
                $('button[data-modal-hide]').on('click', function() {
                    var modalId = $(this).data('modal-hide');
                    $('#' + modalId).removeClass('flex').addClass('hidden');
                });

                // Event listener untuk tombol Approve
                $('.btn.approved').on('click', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to approve this PR?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, approve!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });

                // Event listener untuk tombol Reject
                $('.btn.rejected').on('click', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to reject this PR?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, reject!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });

            // Penanganan pesan sukses
            @if (session()->has('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session()->get('success') }}',
                    text: '{{ session()->get('message') }}',
                });
            @endif
        </script>
    @endpush
</x-app-layout>
