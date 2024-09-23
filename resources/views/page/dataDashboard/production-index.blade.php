<x-app-layout>
    @section('title')
        Production Output
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
                        <li class="breadcrumb-item active" aria-current="page"> Production</li>
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
                        <h4 class="page-title text-2xl font-medium">Production</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="tableProduction"
                                class="!border-separate table text-fade table-bordered w-full ">
                                <thead>
                                    <tr class="text-dark text-center" >
                                        <th class="text-lg">Transaction Number</th>
                                        <th class="text-lg">Effective Date</th>
                                        <th class="text-lg">Transaction Type</th>
                                        <th class="text-lg">Production Line</th>
                                        <th class="text-lg">Part Number</th>
                                        <th class="text-lg">Description</th>
                                        <th class="text-lg">Quantity in Location</th>
                                        <th class="text-lg">Weight in KG</th>
                                        <th class="text-lg">Line</th>
                                        <th class="text-lg">Part Drawing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productions as $item)
                                        <tr class="text-dark">
                                            <td class="text-lg">{{ $item->tr_nbr }}</td>
                                            <td class="text-lg">{{ $item->tr_effdate }}</td>
                                            <td class="text-lg">{{ $item->tr_type }}</td>
                                            <td class="text-lg">{{ $item->tr_prod_line }}</td>
                                            <td class="text-lg">{{ $item->tr_part }}</td>
                                            <td class="text-lg">{{ $item->pt_desc1 }}</td>
                                            <td class="text-lg">{{ $item->tr_qty_loc }}</td>
                                            <td class="text-lg">{{ $item->Weight_in_KG }}</td>
                                            <td class="text-lg">{{ $item->Line }}</td>
                                            <td class="text-lg">{{ $item->pt_draw }}</td>
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
                var table = new DataTable('#tableProduction', {
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
                            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    ],
                });
            });
            $('#tableProduction').css('width', '100%');


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
