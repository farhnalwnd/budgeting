<x-app-layout>
    @section('title', 'Purchase Request')
    @push('css')
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

    <section class="content">
        <div class="card rounded-b-2xl">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">dashboard statistik</h1>
                <div>
                    @hasanyrole('super-admin|budgeting-admin')
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
                <div class="grid grid-cols-3 gap-8 p-5 h-fit font-sans w-full">
                    <div class="min-h-[200px] bg-green-500 rounded-xl shadow-md flex items-center p-4 hover:scale-105 transition delay-100 duration-200 ease-out">
                        <div class="flex w-full items-center justify-between">
                            <div class="">
                                <div class="font-semibold text-slate-200 text-3xl">Remaining</div>
                                <div class="font-bold">Rp. 1239188914</div>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
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
                        <div class="flex w-full items-center justify-between">
                            <div>
                                <div class="font-semibold text-slate-200 text-3xl">Remaining</div>
                                <div class="font-bold">Rp. 1239188914</div>
                            </div>
                            <div>
                                <i class="fa-solid fa-file-invoice-dollar text-[40px]" style="color: #ff9595;"></i>
                            </div>
                        </div>
                    </div>
                
                    <div class="min-h-[200px] bg-yellow-500 rounded-xl shadow-md flex items-center p-4 hover:scale-105 transition delay-100 duration-200 ease-out">
                        <div class="flex w-full items-center justify-between">
                            <div>
                                <div class="font-semibold text-slate-200 text-3xl">Remaining</div>
                                <div class="font-bold">Rp. 1239188914</div>
                            </div>
                            <div>
                                <i class="fa-solid fa-basket-shopping text-[40px]" style="color: #fefe7e;"></i>
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
        
            <div class="w-1/3 bg-white rounded-2xl shadow-md flex items-center justify-center p-3">
                <canvas id="myPieChart"></canvas>
            </div>
        </div> 

        <div class="card rounded-2xl">
            <div class="card-body">
                <!-- ! purchase table -->
                <div>
                    <table id="purchase" class="table table-bordered w-fit">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>purchase NO:</th>
                                <th>PO number:</th>
                                <th>category:</th>
                                <th>grand total:</th>
                                <th>created:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- * data table -->
                        </tbody>
                    </table>
                </div>
                <!-- ! request table -->
                <div>
                    <table id="request" class="table table-bordered w-fit">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>request NO:</th>
                                <th>peminjam:</th>
                                <th>pemberi:</th>
                                <th>amount:</th>
                                <th>status:</th>
                                <th>created:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- * data table -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let purchaseTable = null;
        let requestTable = null;
        let purchases = null;
        let requests = null;
        let years = null;
        let departments = null;

        function toRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }
        document.addEventListener('DOMContentLoaded', function (){
            //ambil filter year
            $.ajax({
                url: "{{route ('get.chart.year')}}",
                method: 'GET',
                success:function(response){
                    years=response;
                    console.log('coba data tahun', years);
                    
                    var yearSelected = document.getElementById('yearFilter');
                    years.forEach(year => {
                        var option = document.createElement('option');
                        option.value = year;
                        option.textContent = year;
                        yearSelected.appendChild(option);
                    });
                },
                error:function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal mengambil data tahun.',
                        confirmButtonText: 'OK'
                    });
                }
            })

            //ambil filter department
            $.ajax({
                url: "{{route ('get.chart.department')}}",
                method: 'GET',
                success:function(response){
                    departments=response;
                    console.log('coba data department', departments);
                    
                    var departmentSelected = document.getElementById('departmentFilter');
                    departments.forEach(department => {
                        var option = document.createElement('option');
                        option.value = department.id;
                        option.textContent = department.department_name;
                        departmentSelected.appendChild(option);
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal mengambil data department.',
                        confirmButtonText: 'OK'
                    });
                }
            })

            //ambil pie chart
            $.ajax({
                url: "{{ route('get.pie.chart') }}",
                type: 'GET',
                data: {
                    department_id: $('#departmentFilter').val() || null // optional
                },
                success: function (response) {
                    const labels = response.map(item => item.label);
                    const data = response.map(item => item.value);

                    const ctx = document.getElementById('myPieChart').getContext('2d');
                    const myPieChart = new Chart(ctx, {
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
                                    'rgb(255, 159, 64)'
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

            //ambil bar chart
            $.ajax({
                url: "{{ route('get.bar.chart') }}",
                type: 'GET',
                data: {
                    department_id: $('#departmentFilter').val() || null
                },
                success: function (data) {
                    const labels = data.map(item => item.category_name);
                    const values = data.map(item => item.total_spending);
                    const maxValue = Math.max(...values); 
                    if (!isFinite(maxValue)) {
                        maxValue = 10000000; 
                    }

                    const ctx = document.getElementById('categoryChart').getContext('2d');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Pengeluaran per Kategori (Rp)',
                                data: values,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(255, 205, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(201, 203, 207, 0.2)'
                                ],
                                borderColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(255, 159, 64)',
                                    'rgb(255, 205, 86)',
                                    'rgb(75, 192, 192)',
                                    'rgb(54, 162, 235)',
                                    'rgb(153, 102, 255)',
                                    'rgb(201, 203, 207)'
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

            //purchase table
            purchaseTable = $('#purchase').DataTable({
                searching: false,
                lengthChange: false,
                info: false,
                paging: false, 
                ajax:{
                    url: "{{route('get.chart.purchase')}}",
                    type: 'GET',
                    data: function (d) {
                        d.year = $('#yearFilter').val();
                        d.department_id = $('#departmentFilter').val();
                    },
                    dataSrc: function(response){
                        purchases = response;
                        console.log(purchases);
                        return response;
                    }
                },
                columns:[
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
                    { orderable: false, data: 'grand_total',
                        render: function (data, type, row) {
                                return toRupiah(data);
                        }
                    },
                    { orderable: false, data: 'created_at',
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
            })

            //request table
            requestTable = $('#request').DataTable({
                searching: false,
                lengthChange: false,
                info: false,
                paging: false, 
                ajax:{
                    url: "{{route('get.chart.request')}}",
                    type: 'GET',
                    data: function (d) {
                        d.year = $('#yearFilter').val();
                        d.department_id = $('#departmentFilter').val();
                    },
                    dataSrc: function(response){
                        requests = response;
                        return response;
                    }
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
                    { orderable: false, data: 'amount',
                        render: function (data, type, row) {
                            return toRupiah(data);
                        }
                    },
                    { orderable: false, data: 'status' },
                    { orderable: false, data: 'created_at',
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
            })

            $('#yearFilter').on('click', function () {
            purchaseTable.ajax.reload();
            requestTable.ajax.reload();
            });

            $('#departmentFilter').on('click', function () {
            purchaseTable.ajax.reload();
            requestTable.ajax.reload();
            });
        })
    </script>
    @endpush
</x-app-layout>
