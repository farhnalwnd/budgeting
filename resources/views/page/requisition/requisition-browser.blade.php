<x-app-layout>
    @section('title')
        Requisition Browser
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
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right"
                    onclick="location.href='{{ route('rqm.index') }}'">Make New PR</button>
            </div>
            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="page-title text-2xl font-medium">Requisition Browse</h4>
                    </div>
                    <div class="box-body">
                        <button id="bulkDeleteBtn" class="btn btn-danger mb-3">Bulk Delete</button>
                        <div class="table-responsive">
                            <table id="tableBrowse"
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
                                        <th>Close Date</th>
                                        <th>Appr Status</th>
                                        <th>Appr Progress</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rqmbrowsers as $item)
                                        @php
                                            $extCostTotal = 0;
                                            $maxExtCostTotal = 0;
                                            foreach ($item->rqdDets as $detail) {
                                                $reqQty = floatval($detail->rqdReqQty);
                                                $purCost = floatval($detail->rqdPurCost);
                                                if ($item->rqmCurr != 'IDR') {
                                                    $maxExtCostTotal +=
                                                        $reqQty * $purCost * floatval($item->rqmExRate2);
                                                } else {
                                                    $maxExtCostTotal += $reqQty * $purCost;
                                                }
                                            }
                                        @endphp
                                        <tr data-modal-target="modal-{{ $item->rqmNbr }}"
                                            data-rqmdirect="{{ $item->rqmDirect }}" class="open-modal">
                                            <td>
                                                <input type="checkbox" id="md_checkbox_{{ $loop->iteration }}"
                                                    class="filled-in chk-col-danger bulk-delete-checkbox"
                                                    data-rqmNbr="{{ $item->rqmNbr }}">
                                                <label for="md_checkbox_{{ $loop->iteration }}"></label>
                                            </td>
                                            <td>{{ $item->rqmNbr }}</td>
                                            <td>{{ $item->rqmEndUserid }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->rqmReqDate)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->rqmNeedDate)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->rqmDueDate)->format('d/m/Y') }}</td>
                                            <td>{{ $item->routeToApr }}</td>
                                            <td>{{ $item->rqmVend }}</td>
                                            <td>{{ $item->routeToBuyer }}</td>
                                            <td>{{ $item->rqmClsDate }}</td>
                                            <td>
                                                <div class="flex item-center justify-center">{{ $item->rqmAprvStat }}
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="flex item-center justify-center bg-success approval-status"></span>
                                                <span class="max-ext-cost-total"
                                                    style="display:none;">{{ $maxExtCostTotal }}</span>
                                                <!-- Elemen tersembunyi untuk maxExtCostTotal -->

                                            </td>
                                            <td>
                                                <div class="flex items-center space-x-1">
                                                    <a href="{{ route('rqm.edit', ['rqmNbr' => $item->rqmNbr]) }}"
                                                        class="edit-btn inline-flex items-center p-2 text-gray-900 hover:text-white dark:text-white dark:hover:text-gray-400 focus:outline-none bg-yellow-400 hover:bg-yellow-900 rounded-md">
                                                        <i class="fa fa-pencil fa-lg text-white"></i>
                                                    </a>
                                                    <form action="{{ route('rqm.delete', $item->rqmNbr) }}"
                                                        method="POST" class="inline-block"
                                                        id="deleteForm{{ $item->rqmNbr }}">
                                                        @csrf
                                                        <input type="hidden" name="rqmNbr"
                                                            value="{{ $item->rqmNbr }}">
                                                        <button type="button"
                                                            onclick="confirmDelete('{{ $item->rqmNbr }}')"
                                                            class="delete-btn inline-flex items-center p-2 text-gray-900 hover:bg-red-900 dark:hover:text-gray-400 focus:outline-none bg-red-500 rounded-md">
                                                            <i class="fa fa-trash fa-lg text-white"></i>
                                                        </button>
                                                    </form>
                                                    <span class="close-span hidden"></span>
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
        </div>
    </section>
    @foreach ($rqmbrowsers as $item)
        <div id="modal-{{ $item->rqmNbr }}" tabindex="-1"
            class="fixed inset-0 m-auto z-50 p-4 overflow-x-hidden overflow-y-auto bg-black bg-opacity-50 flex items-start justify-center hidden"
            aria-modal="true" role="dialog">
            <div class="relative w-full max-w-7xl max-h-full overflow-y-auto">
                <!-- Modal content -->
                <div class="relative bg-white dark:bg-gray-200 text-black rounded-lg shadow" style="margin-top: 10%;">
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
                                    <p class="text-sm font-medium">Reason: {{ $item->rqmReason }}</p>
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
                                            Item Number
                                        </th>
                                        <th
                                            class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                            Supplier
                                        </th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">
                                            Req Qty
                                        </th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-left">
                                            UM
                                        </th>
                                        <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">
                                            Unit Cost
                                        </th>
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
                                                0.00%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                @if (strlen($detail->rqdCmt) > 120)
                                                    @php
                                                        $comment = $detail->rqdCmt;
                                                        while (strlen($comment) > 120) {
                                                            echo '<pre class="text-sm text-left">' .
                                                                substr($comment, 0, 120) .
                                                                '</pre>';
                                                            $comment = substr($comment, 120);
                                                        }
                                                    @endphp
                                                    <pre class="text-sm text-left">{{ $comment }}</pre>
                                                @else
                                                    <pre class="text-sm text-left">{{ $detail->rqdCmt }}</pre>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-sm font-medium">
                                    <p>Requisition Totals</p>
                                    <p>Ext Cost Total:</p>
                                    <p>Max Ext Cost Total:</p>
                                </div>

                                <div class="text-sm font-medium mt-20 flex justify-end" style="padding-right: 5rem;">
                                    <div class="text-right">
                                        <p>{{ number_format($extCostTotal, 2, ',', '.') }} {{ $item->rqmCurr }}</p>
                                        <p>{{ number_format($maxExtCostTotal, 2, ',', '.') }} {{ $item->rqmCurr }}</p>
                                    </div>
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
        <script>
            function printRequisition(rqmNbr) {
                var url = '{{ route('rqm.print', ':rqmNbr') }}'.replace(':rqmNbr', rqmNbr);
                window.open(url, '_blank');
            }

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
                        document.getElementById('deleteForm' + rqmNbr).submit();
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                var table = new DataTable('#tableBrowse', {
                    lengthMenu: [10, 25, 50, {
                        label: 'All',
                        value: -1
                    }],
                    layout: {
                        top1: {
                            searchPanes: {
                                layout: 'columns-12'
                            },
                            searchBuilder: {
                                layout: 'columns-12'

                            }
                        }
                    },
                    columnDefs: [{
                            searchPanes: {
                                show: true,
                                initCollapsed: true
                            },
                            targets: [1, 2, 3, 4, 5, 6, 7, 8, 10]
                        },
                        {
                            searchPanes: {
                                show: false,
                            },
                            targets: [0, 9, 11]
                        },
                        {
                            orderable: false,
                            targets: [0, 11]
                        },
                        {
                            searchPanes: {
                                show: true,
                                orthogonal: 'display',
                                targets: [10]
                            }
                        }
                    ]
                });

                $('#tableBrowse').css('width', '100%');

                function disableActionsForApproved() {
                    $('#tableBrowse tbody tr').each(function() {
                        var rqmAprvStat = $(this).find('td:eq(10) div').text().trim();
                        if (rqmAprvStat === 'Approved' || rqmAprvStat === 'Rejected') {
                            $(this).find('.edit-btn, .delete-btn').addClass('disabled').prop('disabled', true)
                                .hide();
                            $(this).find('.close-span').show();
                            $(this).find(':checkbox').prop('disabled', true); // Disable individual checkboxes
                        } else if (rqmAprvStat === 'c') {
                            $(this).find('.close-span').show();
                        } else {
                            $(this).find('.close-span').hide();
                        }
                    });
                }

                // Inisialisasi IndexedDB
                async function initDB() {
                    return new Promise((resolve, reject) => {
                        const request = indexedDB.open('ApprovalStatusDB_test', 1);

                        request.onupgradeneeded = (event) => {
                            const db = event.target.result;
                            if (!db.objectStoreNames.contains('approvalStatus')) {
                                db.createObjectStore('approvalStatus', {
                                    keyPath: 'rqmNbr'
                                });
                            }
                        };

                        request.onsuccess = (event) => {
                            resolve(event.target.result);
                        };

                        request.onerror = (event) => {
                            reject(event.target.error);
                        };
                    });
                }

                async function saveApprovalStatus(db, data) {
                    for (const item of data) {
                        if (item.rqmNbr) {
                            const existingData = await getApprovalStatus(db, item.rqmNbr);

                            if (!existingData || existingData.approvalsGiven !== item.approvalsGiven || existingData
                                .approvalsTotal !== item.approvalsTotal) {
                                const transaction = db.transaction(['approvalStatus'], 'readwrite');
                                const store = transaction.objectStore('approvalStatus');
                                store.put({
                                    rqmNbr: item.rqmNbr,
                                    approvalsGiven: item.approvalsGiven,
                                    approvalsTotal: item.approvalsTotal,
                                    timestamp: new Date().getTime()
                                });
                            } else {}
                        } else {}
                    }
                }

                async function getApprovalStatus(db, rqmNbr) {
                    return new Promise((resolve, reject) => {
                        if (!rqmNbr || rqmNbr === 'undefined' || rqmNbr === '') {
                            reject('Invalid rqmNbr');
                            return;
                        }

                        const transaction = db.transaction(['approvalStatus'], 'readonly');
                        const store = transaction.objectStore('approvalStatus');
                        const request = store.get(rqmNbr);

                        request.onsuccess = (event) => {
                            resolve(event.target.result);
                        };

                        request.onerror = (event) => {
                            reject(event.target.error);
                        };
                    });
                }

                function deleteApprovalStatus(db, rqmNbr) {
                    return new Promise((resolve, reject) => {
                        const transaction = db.transaction(['approvalStatus'], 'readwrite');
                        const store = transaction.objectStore('approvalStatus');
                        const request = store.delete(rqmNbr);

                        request.onsuccess = () => {
                            resolve();
                        };

                        request.onerror = (event) => {
                            reject(event.target.error);
                        };
                    });
                }

                async function updateApprovalStatus() {
                    const EXPIRATION_DAYS = 30;
                    const MILLISECONDS_IN_A_DAY = 24 * 60 * 60 * 1000;
                    const db = await initDB();

                    $('#tableBrowse tbody tr').each(async function() {
                        var $row = $(this);
                        var rqmNbr = $row.find('td:eq(1)').text().trim();
                        if (!rqmNbr || rqmNbr === 'undefined' || rqmNbr === '') {
                            return; // Skip rows with invalid rqmNbr
                        }
                        var rqmAprvStat = $row.find('td:eq(10) div').text().trim();
                        var rqmAmountStr = $row.find('.max-ext-cost-total').text().trim();
                        var rqmAmount = parseFloat(rqmAmountStr);

                        if (isNaN(rqmAmount)) {
                            rqmAmount = 0;
                        }

                        var rqmDirect = $row.data('rqmdirect');

                        var approvalsRequired = rqmAmount > 5000000 ? 2 : 1;

                        try {
                            var cachedApprovalStatus = await getApprovalStatus(db, rqmNbr);
                        } catch (error) {
                            return; // Skip this row if there's an error
                        }

                        if (cachedApprovalStatus) {
                            var currentTime = new Date().getTime();
                            var cacheTime = cachedApprovalStatus.timestamp;

                            if (currentTime - cacheTime < EXPIRATION_DAYS * MILLISECONDS_IN_A_DAY) {
                                var approvalsGiven = cachedApprovalStatus.approvalsGiven;
                                $row.find('.approval-status').text(approvalsGiven + "/" +
                                    approvalsRequired);

                                if (approvalsGiven === approvalsRequired || approvalsGiven === 1) {
                                    $row.find('.edit-btn, .delete-btn').addClass('disabled')
                                        .prop('disabled', true).hide();
                                    $row.find('.close-span').show();
                                    $row.find(':checkbox').prop('disabled', true);
                                }
                                return;
                            } else {
                                await deleteApprovalStatus(db, rqmNbr);
                            }
                        }

                        if (rqmDirect) {
                            $row.find('.approval-status').text('1/1');
                        } else {
                            $row.find('.approval-status').text('0/' + approvalsRequired);

                            if (rqmAprvStat === 'Approved' || rqmAprvStat === 'Unapproved') {
                                $.ajax({
                                    type: 'GET',
                                    url: '/requisition-approval-status',
                                    data: {
                                        rqmNbr: rqmNbr
                                    },
                                    success: async function(data) {
                                        var approvalsGiven = data.approvalsGiven;

                                        $row.find('.approval-status').text(approvalsGiven +
                                            "/" +
                                            approvalsRequired);

                                        try {
                                            const existingData = await getApprovalStatus(db,
                                                data.rqmNbr);
                                            if (!existingData || existingData
                                                .approvalsGiven !==
                                                data.approvalsGiven || existingData
                                                .approvalsTotal !== data.approvalsTotal) {
                                                await saveApprovalStatus(db, [data]);
                                            }

                                            if (approvalsGiven === approvalsRequired ||
                                                approvalsGiven === 1) {
                                                $row.find('.edit-btn, .delete-btn')
                                                    .addClass(
                                                        'disabled')
                                                    .prop('disabled', true).hide();
                                                $row.find('.close-span').show();
                                                $row.find(':checkbox').prop('disabled',
                                                    true);
                                            }
                                        } catch (error) {
                                            // Handle error silently
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error silently
                                    }
                                });
                            } else if (rqmAprvStat === 'c') {
                                $row.find('.close-span').show();
                                $row.find('.approval-status').text('0/' +
                                    approvalsRequired);
                            } else {
                                $row.find('.close-span').hide();
                                $row.find('.edit-btn, .delete-btn').removeClass('disabled').prop(
                                        'disabled',
                                        false)
                                    .show();
                                $row.find(':checkbox').prop('disabled', false);
                            }
                        }
                    });
                }

                async function longPolling() {
                    try {
                        const response = await fetch('/long-polling');
                        const data = await response.json();

                        // Update IndexedDB with the received data
                        const db = await initDB();
                        await saveApprovalStatus(db, data);

                        updateApprovalStatus();

                        // Lakukan polling lagi setelah interval tertentu
                        setTimeout(longPolling, 30000); // Interval 30 detik
                    } catch (error) {
                        // Coba polling lagi setelah beberapa detik jika terjadi kesalahan
                        setTimeout(longPolling, 5000);
                    }
                }

                // Mulai long polling
                longPolling();

                updateApprovalStatus();
                disableActionsForApproved();

                table.on('draw', function() {
                    disableActionsForApproved();
                    updateApprovalStatus();
                });

                $('#tableBrowse tbody').on('show.bs.modal', 'tr', function() {
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

                $('#checkAllMaster').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.bulk-delete-checkbox').each(function() {
                            var rqmAprvStat = $(this).closest('tr').find('td:eq(10) div').text().trim();
                            if (rqmAprvStat !== 'Approved' && rqmAprvStat !== 'Rejected') {
                                $(this).prop('checked', true);
                            }
                        });
                    } else {
                        $('.bulk-delete-checkbox').prop('checked', false);
                    }
                });

                $('#bulkDeleteBtn').on('click', function() {
                    var selectedItems = [];
                    $('.bulk-delete-checkbox:checked').each(function() {
                        var rqmNbr = $(this).data('rqmnbr');
                        selectedItems.push(rqmNbr);
                    });

                    var countSelected = selectedItems.length;

                    if (countSelected === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak ada item yang dipilih',
                            text: 'Silakan pilih setidaknya satu item untuk dihapus.',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        html: `Anda akan menghapus <strong>${countSelected}</strong> item.<br><br> Anda tidak akan dapat mengembalikan ini!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('rqm.bulk-delete') }}',
                                data: {
                                    rqmNbrs: selectedItems
                                },
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire(
                                        'Deleted!',
                                        'Item terpilih telah dihapus.',
                                        'success'
                                    ).then((result) => {
                                        window.location.reload();
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                    Swal.fire(
                                        'Error!',
                                        'Terjadi kesalahan saat menghapus item.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });

                $('#tableBrowse tbody').on('click', 'tr', function(e) {
                    if ($(e.target).closest(
                            '.edit-btn, .delete-btn, .edit-btn *, .delete-btn *, :checkbox, label').length) {
                        return;
                    }
                    var modalId = $(this).data('modal-target');
                    $('#' + modalId).removeClass('hidden').addClass('flex');
                });

                $('#tableBrowse tbody').on('click', ':checkbox', function(e) {
                    e.stopPropagation();
                });

                // Modal hide event handler
                $('button[data-modal-hide]').on('click', function() {
                    var modalId = $(this).data('modal-hide');
                    $('#' + modalId).removeClass('flex').addClass('hidden');
                });
            });
        </script>
    @endpush



</x-app-layout>
