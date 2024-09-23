<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Production') }}
    </h2>
</x-slot>

<!-- Weight and Quantity Comparison -->
<div class="flex justify-between">
    <!-- Weight Comparison -->
    <div class="box pull-up w-1/2 mr-2">
        <div class="box-body h-36"> <!-- Adjust height as needed -->
            <div class="flex justify-between items-center">
                <div class="bs-5 ps-10 border-info">
                    <p class="text-fade mb-10">Weight Last Month</p>
                    <h2 class="my-0 fw-700 text-3xl">{{ $weightLastMonth }} KG</h2>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-hand-holding-dollar bg-info-light me-0 fs-24 rounded-3"></i>
                </div>
            </div>
            <p class="text-danger mb-0 mt-10"><i class="fa-solid fa-arrow-down"></i> {{ $weightComparison }} since last
                month</p>
        </div>
    </div>
    <!-- Quantity Comparison -->
    <div class="box pull-up w-1/2 ml-2">
        <div class="box-body h-36"> <!-- Adjust height as needed -->
            <div class="flex justify-between items-center">
                <div class="bs-5 ps-10 border-info">
                    <p class="text-fade mb-10">Quantity Last Month</p>
                    <h2 class="my-0 fw-700 text-3xl">{{ $qtyLastMonth }}</h2>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-hand-holding-dollar bg-info-light me-0 fs-24 rounded-3"></i>
                </div>
            </div>
            <p class="text-danger mb-0 mt-10"><i class="fa-solid fa-arrow-down"></i> {{ $qtyComparison }} since last
                month</p>
        </div>
    </div>
</div>



<select id="monthFilterBar" class="mr-2 bg-green-500 text-white">
    <option value="">Select Month</option>
    @for ($i = 1; $i <= 12; $i++)
        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
    @endfor
</select>
<select id="weekFilterBar" class="bg-blue-600 text-white">
    <option value="">Select Week</option>
    <option value="1">Week 1</option>
    <option value="2">Week 2</option>
    <option value="3">Week 3</option>
    <option value="4">Week 4</option>
    <option value="5">Week 5</option>
</select>
<div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 mb-4">
    <!-- Bar Chart -->
    <div class="col-span-2">
        <div class="box">
            <div class="box-body analytics-info">
                <div class="text-xl font-medium">Production Period</div>
                <div id="barChart" style="height:450px;"></div>
            </div>
        </div>
    </div>
    <!-- Table -->
    <div class="col-span-1">
        <div class="card rounded-2xl">
            <div class="box-header flex b-0 justify-between items-center">
                <h4 class="box-title text-2xl">Line</h4>
                <ul class="m-0" style="list-style: none;">
                    <li class="dropdown">
                        <button id="dateDisplay" class="waves-effect waves-light btn btn-outline dropdown-toggle btn-md"
                            data-bs-toggle="dropdown" href="#" aria-expanded="false">
                            {{ date('d F Y') }} <!-- Menampilkan tanggal hari ini -->
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" style="will-change: transform;">
                            <div class="px-3 py-2">
                                <input type="date" id="dateFilterDropdown" class="bg-gray-200 text-black"
                                    value="{{ date('Y-m-d') }}">
                                <button id="applyDateFilterDropdown"
                                    class="bg-blue-500 text-white px-2 py-1 mt-2">Terapkan</button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="card-body pt-0"></div>
            <div class="table-responsive">
                <table class="table mb-0 w-full">
                    <thead>
                        <tr>
                            <th>Line</th>
                            <th class="text-center">Shift 1</th>
                            <th class="text-center">Shift 2</th>
                            <th class="text-center">Shift 3</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($data) && is_array($data) && !empty($data))
                            @php
                                // Inisialisasi array untuk menyimpan total per line
                                $totals = [];
                            @endphp

                            @foreach ($data as $item)
                                @php
                                    // Pastikan $item adalah objek
                                    if (is_object($item)) {
                                        // Menambahkan total_weight ke shift yang sesuai
                                        $totals[$item->line]['shift1'] = ($totals[$item->line]['shift1'] ?? 0) + ($item->shift == 'Shift 1' ? (float)$item->total_weight : 0);
                                        $totals[$item->line]['shift2'] = ($totals[$item->line]['shift2'] ?? 0) + ($item->shift == 'Shift 2' ? (float)$item->total_weight : 0);
                                        $totals[$item->line]['shift3'] = ($totals[$item->line]['shift3'] ?? 0) + ($item->shift == 'Shift 3' ? (float)$item->total_weight : 0);
                                    }
                                @endphp
                            @endforeach

                            @foreach ($totals as $line => $shifts)
                                <tr>
                                    <td class="pt-0 px-0 b-0">
                                        <div class="flex items-center">
                                            <div class="w-10 h-50 rounded {{ $line == 'A' ? 'bg-primary' : ($line == 'B' ? 'bg-success' : ($line == 'C' ? 'bg-info' : ($line == 'D' ? 'bg-danger' : 'bg-warning'))) }}">
                                            </div>
                                            <span class="text-fade text-lg ml-2 font-bold">{{ $line == 'A' ? 'Liquid' : ($line == 'B' ? 'Pastry' : ($line == 'C' ? 'P1' : ($line == 'D' ? 'P2' : ($line == 'E' ? 'P3' : 'Lainnya')))) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center b-0 pt-0 px-0">
                                        <span class="text-fade text-md">{{ number_format($shifts['shift1'] ?? 0, 2, '.', ',') }} KG</span>
                                    </td>
                                    <td class="text-center b-0 pt-0 px-0">
                                        <span class="text-fade text-md">{{ number_format($shifts['shift2'] ?? 0, 2, '.', ',') }} KG</span>
                                    </td>
                                    <td class="text-center b-0 pt-0 px-0">
                                        <span class="text-fade text-md">{{ number_format($shifts['shift3'] ?? 0, 2, '.', ',') }} KG</span>
                                    </td>
                                    <td class="text-center b-0 pt-0 px-0">
                                        <span class="text-fade text-md">{{ number_format(($shifts['shift1'] ?? 0) + ($shifts['shift2'] ?? 0) + ($shifts['shift3'] ?? 0), 2, '.', ',') }} KG</span>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td class="text-right text-xl" colspan="4"><strong>Grand Total</strong></td>
                                <td class="text-center text-xl"><strong>{{ number_format(array_sum(array_column($totals, 'shift1')) + array_sum(array_column($totals, 'shift2')) + array_sum(array_column($totals, 'shift3')), 2, '.', ',') }}</strong></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data untuk ditampilkan.</td>
                            </tr>
                        @endif
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-5 gap-x-4 mb-4">
    <!-- Date -->

    <!-- Line A -->
    <div class="box-body border-2 border-danger rounded-2xl pull-up bg-white">
        <div class="flex justify-start items-center">
            <h3 class="m-0 text-2xl">Liquid</h3>
        </div>
        <canvas id="lineAChart" class="small-chart"></canvas>
    </div>
    <div class="box-body border border-warning rounded-2xl pull-up bg-white">
        <div class="flex justify-start items-center">
            <h3 class="m-0 text-2xl">Pastry</h3>
        </div>
        <canvas id="lineBChart" class="small-chart"></canvas>
    </div>
    <div class="box-body border border-primary rounded-2xl pull-up bg-white">
        <div class="flex justify-start items-center">
            <h3 class="m-0 text-2xl">P1</h3>
        </div>
        <canvas id="lineCChart" class="small-chart"></canvas>
    </div>
    <div class="box-body border border-info rounded-2xl pull-up bg-white">
        <div class="flex justify-start items-center">
            <h3 class="m-0 text-2xl">P2</h3>
        </div>
        <canvas id="lineDChart" class="small-chart"></canvas>
    </div>
    <div class="box-body border border-success rounded-2xl pull-up bg-white">
        <div class="flex justify-start items-center">
            <h3 class="m-0 text-2xl">P3</h3>
        </div>
        <canvas id="lineEChart" class="small-chart"></canvas>
    </div>
</div>



<!-- Import Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>


<!-- Scripts for Charts -->
<script>
    // Doughnut Charts for Lines A, B, C, D, E
    const doughnutData = @json($doughnutData);
    const lines = ['A', 'B', 'C', 'D', 'E'];
    lines.forEach(line => {
        const ctx = document.getElementById(`line${line}Chart`).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Actual', 'Standard'],
                datasets: [{
                    // Menggunakan Blade syntax untuk mendapatkan data per line
                    data: [
                        doughnutData[line].actual,
                        doughnutData[line].standard
                    ],
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false // Ensure the chart does not maintain aspect ratio
            }
        });
    });

    // Inisialisasi ECharts
    var barChart = echarts.init(document.getElementById('barChart'));

    // Fungsi untuk mengatur opsi chart
    function setBarChartOption(labels, actualData, standardData) {
        var option = {
            grid: {
                x: 80, // Meningkatkan nilai x untuk memberikan lebih banyak ruang di sisi kiri
                x2: 60,
                y: 45,
                y2: 50
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            legend: {
                data: ['Actual Production', 'Standard Production']
            },
            color: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
            xAxis: [{
                type: 'category',
                data: labels, // Menggunakan labels dari data
                axisLabel: {
                    interval: 0, // Menampilkan semua label
                    rotate: 0 // Memutar label jika perlu
                }
            }],
            yAxis: [{
                type: 'value',
                min: 0, // Set minimum value
                max: Math.max(...actualData, ...standardData) * 1.1 // Set maximum value
            }],
            series: [{
                    name: 'Actual Production',
                    type: 'bar',
                    data: actualData, // Menggunakan data actual
                    itemStyle: {
                        normal: {
                            label: {
                                show: true,
                                position: 'inside'
                            }
                        }
                    }
                },
                {
                    name: 'Standard Production',
                    type: 'bar',
                    data: standardData, // Menggunakan data standard
                    itemStyle: {
                        normal: {
                            label: {
                                show: true,
                                position: 'inside'
                            }
                        }
                    }
                }
            ]
        };

        // Menggunakan opsi yang telah ditentukan untuk menampilkan chart
        barChart.setOption(option);
    }

    // Update Bar Chart berdasarkan bulan dan minggu yang dipilih
    document.getElementById('monthFilterBar').addEventListener('change', updateBarChart);
    document.getElementById('weekFilterBar').addEventListener('change', updateBarChart);

    // Set filter default ke hari ini
    document.addEventListener('DOMContentLoaded', function() {
        updateBarChart();
    });

    function updateBarChart() {
        const month = document.getElementById('monthFilterBar').value;
        const week = document.getElementById('weekFilterBar').value;

        // Fetch dan update data bar chart berdasarkan bulan dan minggu yang dipilih
        fetch(`/bar-data?month=${month}&week=${week}`)
            .then(response => response.json())
            .then(data => {
                console.log('Bar Chart Data:', data); // Log data respons

                // Memastikan data yang diterima tidak undefined
                if (data && data.labels && data.actual_qty && data.standard_qty) {
                    setBarChartOption(data.labels, data.actual_qty.map(Number), data.standard_qty.map(
                        Number)); // Mengatur opsi chart
                } else {
                    console.error('Data tidak valid:', data);
                }
            })
            .catch(error => console.error('Error fetching bar chart data:', error));
    }

    // Event listener untuk tombol filter tanggal di dropdown
    document.getElementById('applyDateFilterDropdown').addEventListener('click', function() {
        const selectedDate = document.getElementById('dateFilterDropdown').value;
        // Mengubah teks tombol untuk menampilkan tanggal yang dipilih
        const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
        document.getElementById('dateDisplay').innerText = formattedDate;

        // Fetch dan update data berdasarkan tanggal yang dipilih
        fetch(`/data-filter?date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                console.log('Data Filtered:', data);
                // Pastikan data memiliki struktur yang benar
                if (data && data.data && Array.isArray(data.data)) {
                    // Kosongkan tabel sebelum menambahkan data baru
                    const tbody = document.querySelector('table tbody');
                    tbody.innerHTML = ''; // Menghapus isi tabel

                    // Inisialisasi objek untuk menyimpan total per line
                    const totals = {};

                    // Tambahkan data baru ke objek totals
                    data.data.forEach(item => {
                        if (!totals[item.line]) {
                            totals[item.line] = {
                                shift1: 0,
                                shift2: 0,
                                shift3: 0,
                                total: 0
                            };
                        }
                        // Menambahkan total_weight ke shift yang sesuai
                        if (item.shift === 'Shift 1') {
                            totals[item.line].shift1 += parseFloat(item.total_weight);
                        } else if (item.shift === 'Shift 2') {
                            totals[item.line].shift2 += parseFloat(item.total_weight);
                        } else if (item.shift === 'Shift 3') {
                            totals[item.line].shift3 += parseFloat(item.total_weight);
                        }
                    });

                    // Menampilkan data ke dalam tabel
                    for (const line in totals) {
                        const shifts = totals[line];
                        const totalWeight = shifts.shift1 + shifts.shift2 + shifts.shift3;

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="pt-0 px-0 b-0">
                                <div class="flex items-center">
                                    <div class="w-10 h-50 rounded ${line == 'A' ? 'bg-primary' : (line == 'B' ? 'bg-success' : (line == 'C' ? 'bg-info' : (line == 'D' ? 'bg-danger' : 'bg-warning')))}"></div>
                                            <span class="text-fade text-lg ml-2 font-semibold">${line == 'A' ? 'Liquid' : (line == 'B' ? 'Pastry' : (line == 'C' ? 'P1' : (line == 'D' ? 'P2' : (line == 'E' ? 'P3' : 'Lainnya'))))}</span>
                                </div>
                            </td>
                            <td class="text-center b-0 pt-0 px-0">
                                <span class="text-fade text-sm">${shifts.shift1.toFixed(2)} KG</span>
                            </td>
                            <td class="text-center b-0 pt-0 px-0">
                                <span class="text-fade text-sm">${shifts.shift2.toFixed(2)} KG</span>
                            </td>
                            <td class="text-center b-0 pt-0 px-0">
                                <span class="text-fade text-sm">${shifts.shift3.toFixed(2)} KG</span>
                            </td>
                            <td class="text-center b-0 pt-0 px-0">
                                <span class="text-fade text-sm">${totalWeight.toFixed(2)} KG</span>
                            </td>
                        `;
                        tbody.appendChild(row);
                    }

                    // Tambahkan baris untuk grand total jika diperlukan
                    const grandTotalRow = document.createElement('tr');
                    const grandTotal = Object.values(totals).reduce((acc, curr) => acc + curr.shift1 + curr.shift2 + curr.shift3, 0);
                    grandTotalRow.innerHTML = `
                        <td class="text-right text-xl" colspan="4"><strong>Grand Total</strong></td>
                        <td class="text-left text-2xl"><strong>${grandTotal.toFixed(2)} KG</strong></td>
                    `;
                    tbody.appendChild(grandTotalRow);
                } else {
                    console.error('Data tidak valid:', data);
                }
            })
            .catch(error => console.error('Error fetching filtered data:', error));
    });
</script>

<style>
    .small-chart {
        height: 200px !important;
        /* Adjust the height as needed */
    }
</style>
</x-app-layout>

