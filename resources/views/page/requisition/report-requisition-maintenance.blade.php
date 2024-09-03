<x-app-layout>
    @section('title')
        Requisition Report
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
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page"> Requisition</li>
                        <li class="breadcrumb-item active" aria-current="page"> Requisition Report</li>
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
                        <h4 class="page-title text-2xl font-medium">Requisition Report</h4>
                    </div>
                    <div class="box-body">
                        <button id="bulkPrintBtn" class="btn btn-danger mb-3">Bulk Print</button>
                        <div class="table-responsive">
                            <table id="tableReport" class="!border-separate table text-fade table-bordered w-full display nowrap">
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
                                        <th>Direct</th>
                                        <th>NonPO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rqmreports as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="md_checkbox_{{ $loop->iteration }}" class="filled-in chk-col-danger bulk-print-checkbox" data-rqmNbr="{{ $item->rqmNbr }}">
                                                <label for="md_checkbox_{{ $loop->iteration }}"></label>
                                            </td>
                                            <td>{{ $item->rqmNbr }}</td>
                                            <td>{{ $item->rqmEndUserid }}</td>
                                            <td>{{ $item->rqmReqDate }}</td>
                                            <td>{{ $item->rqmNeedDate }}</td>
                                            <td>{{ $item->rqmDueDate }}</td>
                                            <td>{{ $item->routeToApr }}</td>
                                            <td>{{ $item->rqmVend }}</td>
                                            <td>{{ $item->routeToBuyer }}</td>
                                            <td>{{ $item->rqmClsDate }}</td>
                                            <td>{{ $item->rqmAprvStat }}</td>
                                            <td>{{ $item->rqmDirect == 'true' ? 'Yes' : 'No' }}</td>
                                            <td>{{ $item->rqm__log01 == 'true' ? 'Yes' : 'No' }}</td>
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var table = new DataTable('#tableReport', {
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
                            targets: [1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12]
                        },
                        {
                            searchPanes: {
                                show: false
                            },
                            targets: [0, 9]
                        },
                        {
                            orderable: false,
                            targets: [0]
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

                $('#checkAllMaster').on('change', function() {
                    $('.bulk-print-checkbox').prop('checked', $(this).is(':checked'));
                });

                $('#bulkPrintBtn').on('click', function() {
                    const selectedItems = [];
                    $('.bulk-print-checkbox:checked').each(function() {
                        selectedItems.push($(this).data('rqmnbr'));
                    });

                    if (selectedItems.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No items selected',
                            text: 'Please select at least one item to print.',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    const form = $('<form>', {
                        method: 'POST',
                        action: "{{ route('rqm.bulkPrint') }}",
                        target: '_blank'
                    });

                    selectedItems.forEach(function(item) {
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'rqmNbrs[]',
                            value: item
                        }));
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();
                });
            });
        </script>
</x-app-layout>
