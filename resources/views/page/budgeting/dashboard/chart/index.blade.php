<x-app-layout>
    @section('title', 'Purchase Request')
    @push('css')
    <style>
        #purchase th,
        #purchase td {
            text-align: center;
        }
        #request th,
        #request td {
            text-align: center;
        }
    </style>
    @endpush

    <!-- ! header content -->
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-lg"></h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1">Budgeting</li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content font-montserrat">
        <div class="card rounded-b-2xl">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">dashboard statistik</h1>
                <div>
                    @hasanyrole('super-admin|budgeting-admin')
                    {{-- Select department hanya untuk admin --}}
                    <select id="departmentFilter" aria-label="departmentFilter" class="form-control">
                        <option value="">All Department</option>
                        <!-- tambah department lainnya sesuai kebutuhan -->
                    </select>
                    @endhasanyrole
                    <select id="yearFilter" aria-label="yearFilter" class="form-control">
                        <!-- tambah department lainnya sesuai kebutuhan -->
                    </select>
                </div>
            </div>

            <div class="card-body">
                <div class="grid grid-cols-3 gap-8 p-5 h-fit font-sans w-full">
                    <div class="min-h-[200px] bg-green-500 rounded-xl shadow-md flex items-center p-4 hover:scale-105 transition delay-100 duration-200 ease-out">
                        <div class="flex w-full items-center justify-between overflow-clip">
                            <div class="">
                                <div class="pl-10 pb-4 font-semibold text-slate-200 text-3xl">Active Balance</div>
                                <span id="summary-balance" class="font-bold pl-7  text-5xl">memuat..</span>
                            </div>
                            <div class="pr-12">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                                    stroke="#a2ffa2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-wallet-icon lucide-wallet">
                                    <path
                                        d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1" />
                                    <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                
                    <div class="min-h-[200px] bg-red-500 rounded-xl shadow-md flex items-center p-4 hover:scale-105 transition delay-100 duration-200 ease-out">
                        <div class="flex w-full items-center justify-between overflow-clip">
                            <div>
                                <div class="pl-10 pb-4 font-semibold text-slate-200 text-3xl">Speended</div>
                                <span id="summary-expense" class="font-bold pl-7  text-5xl">memuat..</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-file-invoice-dollar text-[80px] pr-12" style="color: #ff9595;"></i>
                            </div>
                        </div>
                    </div>
                
                    <div class="min-h-[200px] bg-yellow-500 rounded-xl shadow-md flex items-center p-4 hover:scale-105 transition delay-100 duration-200 ease-out">
                        <div class="flex w-full items-center justify-between overflow-clip">
                            <div>
                                <div class="pl-10 pb-4 font-semibold text-slate-200 text-3xl">Purchase</div>
                                <span id="summary-purchase" class="font-bold pl-7  text-5xl">memuat..</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-basket-shopping text-[80px] pr-12" style="color: #fefe7e;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex my-8 gap-8 h-screen">
            <div class="w-2/3 bg-white rounded-2xl shadow-md flex items-center justify-center p-3">
                <canvas id="categoryChart"></canvas>
            </div>
        
            <div class="w-1/3 bg-white rounded-2xl shadow-md flex items-center justify-center p-4">
                <canvas id="myPieChart"></canvas>
            </div>
        </div> 

        <div class="mb-8 bg-white rounded-2xl w-full h-screen flex justify-center items-center p-3">
            <canvas id="purchaseChart"></canvas>
        </div>

        <div class="card rounded-2xl p-3 overflow-x-scroll">
            <div class="w-full flex pt-8 justify-center">
                <p class="font-bold text-4xl text-center">10 data purchase terbaru</p>
            </div>
            <div class="card-body">
                <!-- ! purchase table -->
                <table id="purchase" class="table table-striped table-bordered table-hover font-sans text-xl font-medium" style="width:100%">
                    <thead class="font-semibold text-2xl">
                        <tr>
                            <th>#</th>
                            <th>Purchase No:</th>
                            <th>Po Number:</th>
                            <th>Category:</th>
                            <th>Grand Total:</th>
                            <th>Created:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- * data table -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card rounded-t-2xl p-3 overflow-x-scroll">
            <div class="w-full flex pt-8 justify-center">
                <p class="font-montserrat font-bold text-4xl text-center">10 data budget request terbaru</p>
            </div>
            <div class="card-body">
                <!-- ! request table -->
                <table id="request" class="table table-striped table-bordered table-hover font-sans text-xl font-medium" style="width:100%">
                    <thead class="font-semibold text-2xl">
                        <tr>
                            <th>#</th>
                            <th>Request No:</th>
                            <th>Peminjam:</th>
                            <th>Pemberi:</th>
                            <th>Amount:</th>
                            <th>Status:</th>
                            <th>Created:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- * data table -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let purchaseTable, requestTable, categoryChart, purchaseChart, myPieChart;

        function toRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        document.addEventListener('DOMContentLoaded', function () {
            initializeDashboard();
        });

        function initializeDashboard() {
            $.when(
                $.ajax({ url: "{{ route('get.chart.year') }}", method: 'GET' }),
                $.ajax({ url: "{{ route('get.chart.department') }}", method: 'GET' })
            ).done(function (yearsResponse, departmentsResponse) {

                const years = yearsResponse[0];
                const departments = departmentsResponse[0];

                const yearSelect = $('#yearFilter');
                $.each(years, (i, year) => yearSelect.append(new Option(year, year)));

                const deptSelect = $('#departmentFilter');
                if (deptSelect.length) {
                    $.each(departments, (i, dept) => deptSelect.append(new Option(dept.department_name, dept.id)));
                }

                const currentYear = new Date().getFullYear();
                yearSelect.val(currentYear);
                deptSelect.val('');

                initDataTables();
                loadFinancialSummary();
                loadbarchart();
                loadlinechart();
                loadpiechart();

                $('#yearFilter, #departmentFilter').on('change', function () {
                    updateAllDashboardData();
                });

            }).fail(function () {
                Swal.fire('Gagal', 'Gagal memuat data filter awal.', 'error');
            });
        }

        function initDataTables() {
            purchaseTable = $('#purchase').DataTable({
                searching: false,
                lengthChange: false,
                info: false,
                paging: false,
                ajax: {
                    url: "{{ route('get.chart.purchase') }}",
                    type: 'GET',
                    data: function (d) {
                        d.year = $('#yearFilter').val();
                        d.department_id = $('#departmentFilter').val();
                    },
                    dataSrc: ''
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { orderable: false, data: 'purchase_no' },
                    {
                        orderable: false, data: 'PO',
                        render: function (data, type, row) {
                            return data ?? '-';
                        }
                    },
                    {
                        orderable: false, data: 'category.name',
                        render: function (data, type, row) {
                            return row.category && row.category.name ? row.category.name : '-';
                        }
                    },
                    {
                        orderable: false, data: 'grand_total',
                        render: function (data, type, row) {
                            return toRupiah(data);
                        }
                    },
                    {
                        orderable: false, data: 'created_at',
                        render: function (data, type, row) {
                            if (!data) return '-';
                            const date = new Date(data);
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${day}-${month}-${year}`;
                        }
                    }
                ],
            });

            requestTable = $('#request').DataTable({
                searching: false,
                lengthChange: false,
                info: false,
                paging: false,
                ajax: {
                    url: "{{ route('get.chart.request') }}",
                    type: 'GET',
                    data: function (d) {
                        d.year = $('#yearFilter').val();
                        d.department_id = $('#departmentFilter').val();
                    },
                    dataSrc: ''
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { orderable: false, data: 'budget_req_no' },
                    { orderable: false, data: 'from_department.department_name' },
                    { orderable: false, data: 'to_department.department_name' },
                    {
                        orderable: false, data: 'amount',
                        render: function (data, type, row) {
                            return toRupiah(data);
                        }
                    },
                    {
                        orderable: false, data: 'status',
                        render: function (data, type, row) {
                            let statusClass = '';
                            switch (data.toLowerCase()) {
                                case 'pending':
                                    statusClass = 'font-semibold uppercase text-xl text-yellow-800 border-yellow-400';
                                    break;
                                case 'rejected':
                                    statusClass = 'font-semibold uppercase text-xl text-red-800 border-red-400';
                                    break;
                                default:
                                    statusClass = 'font-semibold uppercase text-xl text-green-800 border-green-400';
                                    break;
                            }
                            return `<span class="px-3 py-1 font-semibold text-sm ${statusClass}">${data}</span>`;
                        }
                    },
                    {
                        orderable: false, data: 'created_at',
                        render: function (data, type, row) {
                            if (!data) return '-';
                            const date = new Date(data);
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${day}-${month}-${year}`;
                        }
                    }
                ],
            });
        }

        function updateAllDashboardData() {
            loadFinancialSummary();
            loadbarchart();
            loadlinechart();
            loadpiechart();

            if (purchaseTable) purchaseTable.ajax.reload();
            if (requestTable) requestTable.ajax.reload();
        }

        function loadFinancialSummary() {
            const year = $('#yearFilter').val();
            const departmentId = $('#departmentFilter').val();

            $.ajax({
                url: "{{ route('getbalanceTracking') }}",
                type: 'GET',
                data: {
                    year: year,
                    department_id: departmentId
                },
                success: function (response) {
                    $('#summary-balance').text(toRupiah(response.total_balance));
                    $('#summary-expense').text(toRupiah(response.total_expense));
                    $('#summary-purchase').text(response.total_purchase + 'x aktivitas');
                },
                error: function () {
                    $('#summary-balance').text('Gagal memuat');
                    $('#summary-expense').text('Gagal memuat');
                    $('#summary-purchase').text('Gagal memuat');
                }
            });
        }

        function loadpiechart() {
            $.ajax({
                url: "{{ route('get.pie.chart') }}",
                type: 'GET',
                data: {
                    year: $('#yearFilter').val()
                },
                success: function (response) {
                    if (myPieChart) {
                        myPieChart.destroy();
                    }
                    const labels = response.map(item => item.label);
                    const data = response.map(item => item.value);

                    const ctx = document.getElementById('myPieChart').getContext('2d');
                    myPieChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Saldo per Department',
                                data: data,
                                backgroundColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(54, 162, 235)',
                                    'rgb(255, 205, 86)',
                                    'rgb(75, 192, 192)',
                                    'rgb(153, 102, 255)',
                                    'rgb(255, 159, 64)',
                                    'rgb(46, 204, 113)',
                                    'rgb(231, 76, 60)',
                                    'rgb(149, 165, 166)',
                                    'rgb(241, 196, 15)',
                                    'rgb(26, 188, 156)',
                                    'rgb(52, 73, 94)'
                                ],
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Total Pengeluaran setiap department.',
                                    padding: {
                                        top: 5
                                    },
                                    font: {
                                        size: 16,
                                        weight: 'bold'
                                    }
                                },
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Pie chart gagal dimuat.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function loadbarchart() {
            $.ajax({
                url: "{{ route('get.bar.chart') }}",
                type: 'GET',
                data: {
                    year: $('#yearFilter').val(),
                    department_id: $('#departmentFilter').val()
                },
                success: function (data) {
                    if (categoryChart) {
                        categoryChart.destroy();
                    }
                    const labels = data.map(item => item.name);
                    const values = data.map(item => item.category_sum_grand_total);
                    const maxValue = Math.max(...values);
                    if (!isFinite(maxValue) || values.length === 0) {
                        maxValue = undefined;
                    }

                    const ctx = document.getElementById('categoryChart').getContext('2d');

                    categoryChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Pengeluaran per Kategori (Rp)',
                                data: values,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 205, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(46, 204, 113, 0.2)',
                                    'rgba(231, 76, 60, 0.2)',
                                    'rgba(149, 165, 166, 0.2)',
                                    'rgba(241, 196, 15, 0.2)',
                                    'rgba(26, 188, 156, 0.2)',
                                    'rgba(52, 73, 94, 0.2)'
                                ],
                                borderColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(54, 162, 235)',
                                    'rgb(255, 205, 86)',
                                    'rgb(75, 192, 192)',
                                    'rgb(153, 102, 255)',
                                    'rgb(255, 159, 64)',
                                    'rgb(46, 204, 113)',
                                    'rgb(231, 76, 60)',
                                    'rgb(149, 165, 166)',
                                    'rgb(241, 196, 15)',
                                    'rgb(26, 188, 156)',
                                    'rgb(52, 73, 94)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    max: maxValue,
                                    ticks: {
                                        maxTicksLimit: 10,
                                        callback: function (value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Total Pengeluaran per Kategori',
                                    margin: {
                                        buttom: 35
                                    },
                                    font: {
                                        size: 16,
                                        weight: 'bold'
                                    }
                                },
                                legend: {
                                    position: 'bottom',
                                    margin: {
                                        top: 35
                                    },
                                }
                            }
                        }
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Bar chart gagal dimuat',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function loadlinechart() {
            $.ajax({
                url: "{{ route('get.line.chart') }}",
                type: 'GET',
                data: {
                    year: $('#yearFilter').val(),
                    department_id: $('#departmentFilter').val()
                },
                success: function (response) {
                    if (purchaseChart) {
                        purchaseChart.destroy();
                    }

                    const chartData = {
                        labels: response.labels,
                        datasets: response.datasets
                    };

                    const ctx = document.getElementById('purchaseChart').getContext('2d');
                    console.log('3. Membuat chart baru...');
                    purchaseChart = new Chart(ctx, {
                        type: 'line',
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Jumlah Pembelian per Departemen',
                                    font: {
                                        size: 16,
                                        weight: 'bold'
                                    }
                                },
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        title: function (tooltipItems) {
                                            if (tooltipItems.length > 0) {
                                                return tooltipItems[0].dataset.label;
                                            }
                                            return '';
                                        },
                                        label: function (tooltipItem) {
                                            const count = tooltipItem.raw;
                                            const month = tooltipItem.label;
                                            if (month && count !== undefined && count !== null) {
                                                return ` ${month}: ${count} Pembelian`;
                                            }
                                            return '';
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Chart',
                        text: 'Terjadi kesalahan saat mengambil data dari server.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

    </script>
    @endpush

</x-app-layout>
