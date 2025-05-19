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
                <h1 class="card-title text-2xl font-medium">Report Approved Purchases</h1>
                
                <div>
                    @hasanyrole('super-admin|admin')
                    {{-- Select department hanya untuk admin --}}
                    <select id="departmentFilter" class="form-control">
                        <option value="">All Department</option>
                        <!-- tambah department lainnya sesuai kebutuhan -->
                    </select>
                    @endhasanyrole
                    <select id="yearFilter" class="form-control">
                        <!-- tambah department lainnya sesuai kebutuhan -->
                    </select>
                </div>
            </div>
            
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="reportTable" class="table table-striped w-full text-left rtl:text-right table-bordered" style="width: 100%;">
                        <thead class="uppercase border-b">
                            <tr>
                                {{-- <th scope="col" class="px-6 py-3 text-lg">#</th> --}}
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
        const userDept =  @json(auth()->user()->department->department_name ?? '');
        document.addEventListener('DOMContentLoaded', function() {
            @hasanyrole('super-admin|admin')
            // Ambil data department hanya untuk admin
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
            @endhasanyrole

            $.ajax({
                url: '{{ route('get.report.year') }}',
                method: 'GET',
                success: function(response) {
                    years = response;
                    console.log(response);
                    var yearSelect = document.getElementById('yearFilter');
                    years.forEach(year => {
                        var option = document.createElement('option');
                        option.value = year;
                        option.textContent = year;
                        yearSelect.appendChild(option);
                    });
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data department.');
                }
            });
        });
        var table = $('#reportTable').DataTable({
            dom: 'Bfrtip',
            paging: false,
            ordering:false,
            buttons: [
                {
                    extend: 'copy',
                    title: function () {
                        return getExportTitle();
                    }
                },
                {
                    extend: 'csv',
                    title: function () {
                        return getExportTitle();
                    }
                },
                {
                    extend: 'excel',
                    title: function () {
                        return getExportTitle();
                    }
                },
                {
                    extend: 'pdf',
                    title: function () {
                        return getExportTitle();
                    }
                },
                {
                    extend: 'print',
                    title: function () {
                        return getExportTitle();
                    },
                    customize: function (win) {
                        $(win.document.head).append(`
                            <style>
                                tr.subtotal-row {
                                    font-weight: bold !important;
                                    background-color: #f0f0f0 !important;
                                }
                                tr.sub-title {
                                    font-weight: bold !important;
                                    text-align: center;
                                    background-color: whitesmoke !important;
                                }
                            </style>
                        `);

                        const $rows = $(win.document.body).find('table tbody tr');

                        // Ambil baris pertama
                        var firstRow = $rows.first();
                        var firstCell = firstRow.find('td:first');

                        // Hitung jumlah kolom asli, jika kamu ingin dinamis
                        var colCount = firstRow.find('td').length;

                        // Hapus semua <td> lain selain yang pertama
                        firstRow.find('td:not(:first)').remove();

                        // Tambahkan atribut colspan
                        firstCell.attr('colspan', colCount);
                        // Tambahkan styling jika perlu
                        firstRow.addClass('sub-title');

                        // Ambil baris setelah baris yang berisi "Subtotal"
                        $rows.each(function(index) {
                            const text = $(this).find('td:first').text().toLowerCase();
                            if (text.includes('subtotal') || text.includes('grand total')) {
                                // Hanya ambil baris berikutnya
                                $(this).addClass('subtotal-row');

                                const nextRow = $rows.eq(index + 1);
                                const nextCell = nextRow.find('td:first');

                                if(!nextCell.text().toLowerCase().includes('grand total'))
                                {
                                    var colCount = nextRow.find('td').length;
                                    // Hapus semua <td> lain selain yang pertama
                                    nextRow.find('td:not(:first)').remove();
                                    // Tambahkan atribut colspan
                                    nextCell.attr('colspan', colCount);

                                    nextRow.addClass('sub-title');
                                }
                            }
                        });
                    }
                }
            ],
            ajax: {
                url: '{{ route('get.report.data') }}',
                type: 'GET',
                data: function(d){
                    // Menambahkan parameter department_id ke ajax request
                    d.department_name = $('#departmentFilter').val();
                    d.year = $('#yearFilter').val();
                },
                dataSrc: function(response) {
                    console.log(response);
                    return response;
                }
            },
            columns: [
                // {
                //     data: null,
                //     render: function (data, type, row, meta) {
                //         // Kosongkan nomor untuk baris subtotal
                        
                //         return null;
                //         return row.is_subtotal ? '' : meta.row + 1;
                //     }
                // },
                {
                    data: 'purchase_no',
                    render: function (data, type, row) {
                        return row.is_subtotal ? `<strong>${data}</strong>` : row.is_subcategory ? `<span style="font-weight:600;">${data}</span>` : data;
                    }
                },
                {
                    data: 'item_name',
                    render: function (data, type, row) {
                        return row.is_subtotal ? '' : row.is_subcategory ? '' : data;
                    }
                },
                {
                    data: 'amount',
                    render: function (data, type, row) {
                        return row.is_subtotal ? '' : row.is_subcategory ? '' : Number(data).toLocaleString();
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
                        if (row.is_subcategory)
                        {
                            return '';
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
        });
        
        function getExportTitle() {
            const year = $('#yearFilter').val();

            @hasanyrole('super-admin|admin')
            const dept = $('#departmentFilter').val();
            return `Laporan Pembelian ${dept} - Tahun ${year} `;
            @endhasanyrole
            return `Laporan Pembelian ${userDept} - Tahun ${year} `;
        }

        $('#departmentFilter').on('change', function() {
            table.ajax.reload();  // Reload data table dengan filter department
        });
        
        $('#yearFilter').on('change', function() {
            table.ajax.reload();  // Reload data table dengan filter department
        });

    </script>
    @endpush

</x-app-layout>