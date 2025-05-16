<x-app-layout>
    @section('title')
        Report
    @endsection
    
    @push('css')
        <style>
            
        </style>
    @endpush

    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-lg"></h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1">Dashboard</li>
                        <li class="breadcrumb-item active">Report</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">Report</h1>
                
                
                @hasanyrole('super-admin|admin')
                {{-- Select department hanya untuk admin --}}
                <select id="departmentFilter" class="form-control">
                    <option value="">All Department</option>
                    <!-- tambah department lainnya sesuai kebutuhan -->
                </select>
                @endhasanyrole
            </div>
            
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="reportTable" class="table table-striped w-full text-left rtl:text-right table-bordered" style="width: 100%;">
                        <thead class="uppercase border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-lg">#</th>
                                <th scope="col" class="px-6 py-3 text-lg">Purchase No</th>
                                <th scope="col" class="px-6 py-3 text-lg">Item Name</th>
                                <th scope="col" class="px-6 py-3 text-lg">Harga</th>
                                <th scope="col" class="px-6 py-3 text-lg">Jumlah</th>
                                <th scope="col" class="px-6 py-3 text-lg">Total</th>
                                <th scope="col" class="px-6 py-3 text-lg">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        @hasanyrole('super-admin|admin')
        // Ambil data department hanya untuk admin
        document.addEventListener('DOMContentLoaded', function() {
            // get department list
            $.ajax({
                url: '{{ route('get.department.data') }}',
                method: 'GET',
                success: function(response) {
                    departments = response;

                    var departmentSelect = document.getElementById('departmentFilter');
                    departments.forEach(department => {
                        var option = document.createElement('option');
                        option.value = department.department_name;
                        option.textContent = department.department_name;
                        departmentSelect.appendChild(option);
                    });
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data department.');
                }
            });
        });
        @endhasanyrole
        var table = $('#reportTable').DataTable({
            dom: 'Bfrtip',
            paging: false,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ajax: {
                url: '{{ route('get.report.data') }}',
                type: 'GET',
                data: function(d){
                    // Menambahkan parameter department_id ke ajax request
                    d.department_name = $('#departmentFilter').val();
                },
                dataSrc: function(response) {
                    console.log(response);
                    // response.purchases.push({
                    //     purchase_no: 'GRAND TOTAL',
                    //     item_name: '',
                    //     amount: response.grand_total_amount,
                    //     quanitity: response.grand_total_quantity,
                    //     total_amount: response.grand_total_total,
                    //     remarks: ''
                    // }); 
                    // return response.purchases;
                    return response;
                }
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        // Kosongkan nomor untuk baris subtotal
                        
                        return null;
                        return row.is_subtotal ? '' : meta.row + 1;
                    }
                },
                {
                    data: 'purchase_no',
                    render: function (data, type, row) {
                        return row.is_subtotal ? `<strong>${data}</strong>` : data;
                    }
                },
                {
                    data: 'item_name',
                    render: function (data, type, row) {
                        return row.is_subtotal ? '' : data;
                    }
                },
                {
                    data: 'amount',
                    render: function (data, type, row) {
                        return row.is_subtotal ? '' : Number(data).toLocaleString();
                    }
                },
                {
                    data: 'quantity',
                    render: function (data, type, row) {
                        return row.is_subtotal ? `<strong>${data}</strong>` : data;
                    }
                },
                {
                    data: 'total_amount',
                    render: function (data, type, row) {
                        if (row.is_subtotal) {
                            if(!row.purchase_no.startsWith('Subtotal') && row.purchase_no !== 'GRAND TOTAL')
                            {
                                return '';
                            }
                            return `<strong>${Number(data).toLocaleString()}</strong>`;
                        }
                        return Number(data).toLocaleString();
                    }
                },
                {
                    data: 'remarks',
                    render: function (data, type, row) {
                        return row.is_subtotal ? '' : data;
                    }
                }
            ],
            rowCallback: function (row, data) {
                if (data.is_subtotal && !data.purchase_no.startsWith('Subtotal')) {
                    $(row).css({
                        'font-weight': 'bold',
                        'background-color': '#f0f0f0'
                    });
                }
            },
            columnDefs: [
                { targets: 0, orderable: false } // asumsi kolom nomor di posisi 0
            ]
            // rowGroup: {
            //     dataSrc: 'master.department.department_name',
            //     endRender: function(rows, group) {
            //         console.log(group);
            //         let amount = 0
            //         let totalQty = 0;
            //         let totalAmount = 0;

            //         rows.data().each(function(row) {
            //             amount += parseFloat(row.amount);
            //             totalQty += parseFloat(row.quanitity);
            //             totalAmount += parseFloat(row.total_amount);
            //         });

            //         return $('<tr/>')
            //             .append('<td colspan="3" style="text-align:center;"><b>Total for ' + group + '</b></td>')
            //             .append('<td><b>' + amount.toLocaleString() + '</b></td>')
            //             .append('<td><b>' + totalQty + '</b></td>')
            //             .append('<td><b>' + totalAmount.toLocaleString() + '</b></td>')
            //             .append('<td></td>');
            //     }
            // }
        });
       
        $('#departmentFilter').on('change', function() {
            table.ajax.reload();  // Reload data table dengan filter department
        });

    </script>
    @endpush

</x-app-layout>