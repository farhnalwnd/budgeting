<x-app-layout>
    @section('title')
        Requisition Browser
    @endsection
    @push('css')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.3/css/select.jqueryui.css">
    @endpush

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
                                        <th>Appr Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rqmbrowsers as $item)
                                        <tr data-modal-target="modal-{{ $item->rqmNbr }}" class="open-modal">
                                            <td>
                                                <input type="checkbox" id="md_checkbox_{{ $loop->iteration }}"
                                                    class="filled-in chk-col-danger bulk-delete-checkbox"
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
                                            <td>{{ $item->rqmClsDate }}</td>
                                            <td>{{ $item->rqmAprvStat }}</td>
                                            <td>{{ $item->approved_date }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button id="dropdownMenuIconButton{{ $item->rqmNbr }}"
                                                        data-dropdown-toggle="dropdownDots{{ $item->rqmNbr }}"
                                                        class="dropdownMenuIconButton inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                                        type="button">
                                                        <svg class="w-5 h-5" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                            viewbox="0 0 4 15">
                                                            <path
                                                                d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z">
                                                            </path>
                                                        </svg>
                                                    </button>

                                                    <!-- Dropdown menu -->
                                                    <div id="dropdownDots{{ $item->rqmNbr }}"
                                                        class="dropdownDots z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-22 dark:bg-gray-700 dark:divide-gray-600">
                                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                                            aria-labelledby="dropdownMenuIconButton{{ $item->rqmNbr }}">
                                                            <li>
                                                                <a href="{{ route('rqm.edit', ['rqmNbr' => $item->rqmNbr]) }}"
                                                                    class="edit-btn block px-4 py-2 hover:bg-gray-100">
                                                                    <i class="fa fa-pencil fa-2x text-warning"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('rqm.delete', $item->rqmNbr) }}"
                                                                    method="POST" class="inline-block"
                                                                    id="deleteForm{{ $item->rqmNbr }}">
                                                                    @csrf
                                                                    <input type="hidden" name="rqmNbr"
                                                                        value="{{ $item->rqmNbr }}">
                                                                    <button type="button"
                                                                        onclick="confirmDelete('{{ $item->rqmNbr }}')"
                                                                        class="delete-btn block px-4 py-2 hover:bg-gray-100">
                                                                        <i class="fa fa-trash fa-2x text-danger"></i>
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
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
                <div class="relative bg-white dark:bg-gray-200 text-black rounded-lg shadow" style="margin-top: 5%;">
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
                                                {{ $detail->rqdLine }}</td>
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

    <script>
        // Fungsi untuk mencetak requisition
        function printRequisition(rqmNbr) {
            var url = '{{ route('rqm.print', ':rqmNbr') }}'.replace(':rqmNbr', rqmNbr);
            window.open(url, '_blank');
        }

        // Fungsi untuk mengkonfirmasi penghapusan
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

        document.addEventListener('DOMContentLoaded', function() {
            new DataTable('#tableBrowse', {
                layout: {
                    top1: {
                        searchPanes: {
                            layout: 'columns-12'
                        }
                    },
                },
                columnDefs: [{
                    searchPanes: {
                        show: true,
                        initCollapsed: true
                    },
                    targets: [1, 2, 3, 4, 5, 6, 7, 8, 10]
                }, {
                    orderable: false,
                    targets: [0, 12]
                }]
            });

            $('#tableBrowse').css('width', '100%');

            $(document).ready(function() {
                // Dropdown toggle functionality
                $('[id^=dropdownMenuIconButton]').on('click', function(e) {
                    e.stopPropagation(); // Prevent click from propagating to the row
                    var dropdownId = $(this).data('dropdown-toggle');
                    $('#' + dropdownId).toggleClass('hidden');
                });

                // Close dropdown when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('[id^=dropdownMenuIconButton]').length && !$(e.target)
                        .closest('.dropdownDots').length) {
                        $('.dropdownDots').addClass('hidden');
                    }
                });

                // Event handler for table row clicks
                $('#tableBrowse tbody').on('click', 'tr', function(e) {
                    // Prevent modal from opening when clicking on edit/delete buttons, their children, checkboxes, labels, the dropdown button, dropdown menu, or its children
                    if ($(e.target).is(
                            '.edit-btn, .delete-btn, .edit-btn *, .delete-btn *, :checkbox, label, .dropdownMenuIconButton *, .dropdownDots, .dropdownDots *'
                        )) {
                        return;
                    }
                    var modalId = $(this).data('modal-target');
                    $('#' + modalId).removeClass('hidden').addClass('flex');
                });

                // Event handler untuk checkbox di dalam tbody
                $('#tableBrowse tbody').on('click', ':checkbox', function(e) {
                    e.stopPropagation(); // Mencegah penyebaran event ke atas saat checkbox diklik
                });

                $('button[data-modal-hide]').on('click', function() {
                    var modalId = $(this).data('modal-hide');
                    $('#' + modalId).removeClass('flex').addClass('hidden');
                });

                $('#tableBrowse tbody').on('show.bs.modal', 'tr', function() {
                    var itemNbr = $(this).find('td:first').text().trim();
                    var modalId = $(this).data('modal-target');

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('rqm.edit', ':rqmNbr') }}'.replace(':rqmNbr',
                            itemNbr),
                        success: function(data) {
                            $('#' + modalId).find('#show-data' + itemNbr).html(data);
                        }
                    });
                });

                $('#checkAllMaster').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.bulk-delete-checkbox').each(function() {
                            var rqmAprvStat = $(this).closest('tr').find('td:eq(10)').text()
                                .trim();
                            if (rqmAprvStat !== 'Approved') {
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
                        console.log(`Item yang dipilih: ${rqmNbr}`);
                    });

                    var countSelected = selectedItems.length;

                    if (countSelected === 0) {
                        alert('Silakan pilih setidaknya satu item untuk dihapus.');
                        return;
                    }
                    // Console log untuk semua item yang dipilih
                    console.log('Data yang dipilih:', selectedItems);

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
                            // Kirim permintaan hapus jika dikonfirmasi
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('rqm.bulk-delete') }}', // Sesuaikan dengan route untuk bulk delete
                                data: {
                                    rqmNbrs: selectedItems
                                },
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    // Handle success, misalnya refresh tabel
                                    Swal.fire(
                                        'Deleted!',
                                        'Item terpilih telah dihapus.',
                                        'success'
                                    ).then((result) => {
                                        // Redirect to previous page
                                        window.location.reload();
                                    });
                                },
                                error: function(xhr, status, error) {
                                    // Handle error
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

                // Disable action untuk rqmAprvStat = Approved
                $('#tableBrowse tbody tr').each(function() {
                    var rqmAprvStat = $(this).find('td:eq(10)').text()
                        .trim(); // Sesuaikan dengan kolom yang berisi status approval
                    if (rqmAprvStat === 'Approved') {
                        $(this).find('.edit-btn, .delete-btn, .dropdownMenuIconButton').prop(
                            'disabled', true);
                        $(this).find(':checkbox').prop('disabled', true);
                    }
                });
            });
        });
    </script>



</x-app-layout>
