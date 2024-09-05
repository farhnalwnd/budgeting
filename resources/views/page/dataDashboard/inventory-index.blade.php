<x-app-layout>
    @section('title')
        Inventory
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
                        <li class="breadcrumb-item pr-1" aria-current="page"> Data Dashboard</li>
                        <li class="breadcrumb-item active" aria-current="page"> Inventory</li>
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
                        <h4 class="page-title text-2xl font-medium">Inventory</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="tableInventory"
                                class="!border-separate table text-fade table-bordered w-full ">
                                <thead>
                                    <tr class="text-dark text-center" >
                                        <th>Part Number</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Quantity</th>
                                        <th>UM</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Lot Number</th>
                                        <th>Aging Days</th>
                                        <th>Expiration Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventory as $item)
                                        <tr class="text-dark">
                                            <td >{{ $item->ld_part }}</td>
                                            <td >{{ $item->pt_desc1 }}</td>
                                            <td >{{ $item->ld_status }}</td>
                                            <td >{{ $item->ld_qty_oh }}</td>
                                            <td >{{ strtoupper($item->pt_um) }}</td>
                                            <td >{{ $item->ld_date }}</td>
                                            <td >{{ $item->ld_loc }}</td>
                                            <td >{{ $item->ld_lot }}</td>
                                            <td >{{ $item->aging_days }} Days</td>
                                            <td >{{ $item->ld_expire }}</td>
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
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function showSuccessMessage(message) {
                Swal.fire({
                    title: 'Success!',
                    text: message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                var table = new DataTable('#tableInventory', {
                    lengthMenu: [10, 25, 50, {
                        label: 'All',
                        value: -1
                    }],
                    layout: {
                        top1: {
                            searchPanes: {
                                layout: 'columns-10'
                            },
                            searchBuilder: {
                                layout: 'columns-10'
                            }
                        }
                    },
                    columnDefs: [{
                            searchPanes: {
                                show: true,
                                initCollapsed: true
                            },
                            targets: [0, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    ],
                });
            });
            $('#tableInventory').css('width', '100%');


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
