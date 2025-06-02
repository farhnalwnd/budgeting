<x-app-layout>
    @section('title')
        List Budget-Request
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
                        <li class="breadcrumb-item pr-1">Budget Request</li>
                        <li class="breadcrumb-item active">List Budget-Request</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section x-data="{open : false}" class="content">
        <!-- Add Budget Request Button -->
        <div class="mb-4 flex justify-end">
            <button type="button" @click="open = ! open"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right">
                Add Budget-Request
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">List Budget-Request</h1>
                <select id="yearFilter" class="form-control">
                    <!-- tambah department lainnya sesuai kebutuhan -->
                </select>
            </div>
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="budgetTable" class="table table-striped w-full text-left rtl:text-right table-bordered" style="width: 100%;">
                        <thead class="uppercase border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-lg">#</th>
                                <th scope="col" class="px-6 py-3 text-lg">No</th>
                                <th scope="col" class="px-6 py-3 text-lg">From</th>
                                <th scope="col" class="px-6 py-3 text-lg">To</th>
                                <th scope="col" class="px-6 py-3 text-lg">Amount</th>
                                <th scope="col" class="px-6 py-3 text-lg">Purchase No</th>
                                <th scope="col" class="px-6 py-3 text-lg">Reason</th>
                                <th scope="col" class="px-6 py-3 text-lg">Status</th>
                                <th scope="col" class="px-6 py-3 text-lg">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="open" x-on:keydown.escape.window="open = false" x-transition.duration.400ms
            class="fixed inset-0 z-[900] flex items-center justify-center bg-black bg-opacity-50">
            <div class="absolute bg-white text-black p-6 rounded-lg shadow-lg w-2/3 max-h-[800px] card">
                <!-- Header -->
                <div class="flex justify-start">
                    <div class="flex items-center">
                        <h1 class="text-6xl font-bold text-yellow-700 font-mono">Create Budget-Request</h1>
                    </div>
                    
                    <div class="w-72 h-32 ml-auto mb-5">
                        <img src="{{ asset('assets/images/logo/logowhite.png')  }}" class="dark-logo" alt="Logo-Dark">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" class="light-logo" alt="Logo-light">
                    </div>
                </div>
                <hr class="my-10 border-t-2 rounded-md border-slate-900 opacity-90">

                <form method="POST" action="{{ route('budget-request.store') }}">
                    <!-- Keterangan -->
                    <div>
                        <div class="flex items-center mt-2">
                            <div class="form-group">
                                <h1 class="form-label font-bold text-lg">From Department</h1>
                                <input type="text" name="from_department" id="from_department" readonly
                                    class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light flex-1"
                                    placeholder="From Department" required>
                            </div>
                            <div class="ml-auto form-group">
                                <h1 class="form-label font-bold text-lg">Budget No</h1>
                                <input type="text" name="no" id="no" readonly
                                    class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light"
                                    placeholder="Auto Fill" required>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="container mt-10">
                        @csrf
                        <div x-data="{ scrolled: false }" @scroll="scrolled = $el.scrollTop > 0 || false"
                            class="overflow-y-auto max-h-[250px] mt-6">
                            <table class="table-auto w-full border-collapse" id="testTable">
                                <thead :class="scrolled ? 'bg-white shadow-md border-none' : ''" class="sticky top-0 z-10">
                                    <tr>
                                        <th class="text-center w-fit">
                                            <h2>To Department</h2>
                                        </th>
                                        <th class="text-center w-fit">
                                            <h2>Amount</h2>
                                        </th>
                                        <th class="text-center w-fit">
                                            <h2>Reason</h2>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="max-h-[50vh] overflow-y-auto">
                                    <tr>
                                        <td>
                                            <select name="to_department" id="to_department" required
                                                class="form-select w-full text-lg text-body bg-secondary-light border" aria-invalid="false" style="padding: 5px;"
                                                placeholder="To Department">
                                                <option value="" selected disabled>Select Department</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="amount" id="amount"
                                            class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                placeholder="Input Number" required>
                                        </td>
                                        <td>
                                            <input type="text" name="reason" id="reason"
                                                class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                placeholder="Input reason" required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-end mx-4 mt-4 gap-2">
                            <button type="submit" class="btn btn-success" onClick="event.preventDefault(); confirmBudgetCreate(this)">Simpan</button>
                            <button @click="open = !open" type="button" class="btn btn-danger">Exit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Modal Edit User -->
    <div id="editModalDiv">
        <div id="modalBackground" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40 hidden"></div>
    </div>

    @push('scripts')
    <script>
        var fromDepartment = null;
        var budgets = null;
        var allocations = null;
        var categories = null;
        var table = null;
        document.addEventListener('DOMContentLoaded', function() {
            
            fromDepartment = @json(Auth::user()->department);

            // get budget-request year list
            $.ajax({
                url: '{{ route('get.budget-request.year') }}',
                method: 'GET',
                success: function(response) {
                    years = response;
                    var yearSelect = document.getElementById('yearFilter');
                    years.forEach(year => {
                        var option = document.createElement('option');
                        option.value = year;
                        option.textContent = year;
                        yearSelect.appendChild(option);
                    });
                    initTable();
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data department.');
                }
            });

            // get new budget request no
            $.ajax({
                url: '{{ route('get.budget.request.no') }}',
                method: 'GET',
                data: {
                    departmentId: fromDepartment.id
                },
                success: function(response) {
                    var no = document.getElementById('no');
                    no.value = response;
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil nomor budget.');
                }
            });

            // get department list
            $.ajax({
                url: '{{ route('get.department.data') }}',
                method: 'GET',
                success: function(response) {
                    departments = response;
                    var fromDept = document.getElementById('from_department');
                    fromDept.value = fromDepartment.department_name;
                    var departmentSelect = document.getElementById('to_department');
                    departments.forEach(department => {
                        if(department.id !== fromDepartment.id)
                        {
                            var option = document.createElement('option');
                            option.value = department.id;
                            option.textContent = department.department_name;
                            departmentSelect.appendChild(option);
                        }
                    });
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data department.');
                }
            });

            function initTable()
            {
                // Init datatable
                table = $('#budgetTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    ajax: {
                        url: '{{ route('get.budget.request.list') }}',
                        type: 'GET',
                        data: function (d) {
                            d.year = $('#yearFilter').val();
                        },
                        dataSrc: function(response) {
                            budgets = response;
                            return response;
                        }
                    },
                    columns: [
                        { 
                            data: null,
                            render: function(data, type, row, meta) {
                                // Menambahkan nomor urut
                                return meta.row + 1; // meta.row berisi index baris
                            }
                        },
                        { data: 'budget_req_no', name: 'no' },
                        { data: 'from_department.department_name', name: 'from_department' },
                        { data: 'to_department.department_name', name: 'to_department' },
                        { data: 'amount', name: 'amount',
                            render: function(data, type, row) {
                                if (data == null) return '-';
                                
                                return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                                }).format(data);
                            }
                        },
                        { data: 'budget_purchase_no', name: 'purchase_no' },
                        { data: 'reason', name: 'reason' },
                        { data: 'status', name: 'status' ,
                            render: function(data, type, row) {
                                if (data === 'approved') 
                                {
                                    return '<span style="color: green; font-weight: bold;">' + (data.charAt(0).toUpperCase() + data.slice(1)) + '</span>';
                                } 
                                else if (data === 'approved with review')
                                {
                                    return '<span style="color: green; font-weight: bold;">' + (data.charAt(0).toUpperCase() + data.slice(1)) + '</span>';
                                }
                                else if (data === 'rejected')
                                {
                                    return '<span style="color: red; font-weight: bold;">' + (data.charAt(0).toUpperCase() + data.slice(1)) + '</span>';
                                }
                                else
                                {
                                    return data;
                                }
                            }
                        },
                        { data: null, name: 'action', orderable: false, searchable: false,
                            render: function(data, type, row, meta) {
                                var id = row.budget_req_no;
                                var deleteUrl = "{{ route('budget-request.destroy', ':id') }}".replace(':id', id.replaceAll("/", "-")); 
                                return `
                                <div class="d-flex action-btn">
                                    <a href="javascript:void(0)" class="text-primary edit" onClick="openEditModal(${meta.row})">
                                        <i class="ti ti-eye fs-5"></i>
                                    </a>
                                </div>
                                `; 
                            }
                        }
                    ]
                });
            }
        });

        
        // Isi data awal pada modal create
        function fillCreateForm()
        {
            // get new budget request no
            $.ajax({
                url: '{{ route('get.budget.request.no') }}',
                method: 'GET',
                data: {
                    departmentId: fromDepartment.id
                },
                success: function(response) {
                    var no = document.getElementById('no');
                    no.value = response;
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil nomor budget.');
                }
            });

            // get department list
            $.ajax({
                url: '{{ route('get.department.data') }}',
                method: 'GET',
                success: function(response) {
                    departments = response;
                    var fromDept = document.getElementById('from_department');
                    fromDept.value = fromDepartment.department_name;
                    var departmentSelect = document.getElementById('to_department');
                    departments.forEach(department => {
                        if(department.id !== fromDepartment.id)
                        {
                            var option = document.createElement('option');
                            option.value = department.id;
                            option.textContent = department.department_name;
                            departmentSelect.appendChild(option);
                        }
                    });
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data department.');
                }
            });
        }

        // Function untuk menghapus edit div
        function clearEditDiv()
        {
            const container = document.getElementById('editModalDiv');
            const background = document.getElementById('modalBackground');
            // Simpan elemen background
            const preserved = background.cloneNode(true);
            // Kosongkan container
            container.innerHTML = '';
            // Masukkan kembali elemen yang disimpan
            container.appendChild(preserved);
        }

        // Function untuk konfirmasi create budget
        function confirmBudgetCreate(button){
            var form = button.closest('form');
            var actionUrl = form.getAttribute('action');
            
            // Cek validasi form 
            if (!form.checkValidity()) {
                form.reportValidity(); // Menampilkan pesan default browser
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tutup edit modal div
                    const alpineData = Alpine.closestDataStack(button)?.[0];
                    if (alpineData) {
                        alpineData.open = false;
                    }

                    // Kirim form
                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: $(form).serialize(), // Ambil semua input form
                        success: function(response) {
                            // Alert data berhasil
                            Swal.fire({
                                toast: true,
                                icon: 'success',
                                title: response.message,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            // Bersihkan edit div
                            clearEditDiv();

                            // Refresh data table
                            table.ajax.reload(null, false); // Reload data dari server

                            // reset form
                            form.reset();

                            // isi data form awal
                            fillCreateForm();
                        },
                        error: function(xhr) {
                            // Alert data gagal
                            Swal.fire({
                                toast: true,
                                icon: 'error',
                                title: xhr.responseJSON.message,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    });
                }
            });
        }

        // Function untuk konfirmasi delete budget
        function confirmBudgetDelete(button){
            var budgetId = button.getAttribute('data-budget-id');
            var form = document.getElementById('delete-form-' + budgetId);
            var actionUrl = form.getAttribute('action');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim form
                    $.ajax({
                        url: actionUrl,
                        method: 'DELETE',
                        data: $(form).serialize(), // Ambil semua input form
                        success: function(response) {
                            // Alert data berhasil
                            Swal.fire({
                                toast: true,
                                icon: 'success',
                                title: response.message,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            // Bersihkan edit div
                            clearEditDiv();

                            // Refresh data table
                            table.ajax.reload(null, false); // Reload data dari server
                        },
                        error: function(xhr) {
                            // Alert data gagal
                            Swal.fire({
                                toast: true,
                                icon: 'error',
                                title: xhr.responseJSON.message,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    });
                }
            });
        }

        // Function untuk buat/buka modal
        function openEditModal(id){
            var modal = document.getElementById(`editContactModal${id}`);
            var modalBackground = document.getElementById('modalBackground');
            modalBackground.classList.toggle('hidden');
            if (modal){
                modal.classList.toggle('hidden');
                modal.classList.toggle('flex');
                return;
            }
            var modalDiv = document.getElementById('editModalDiv');
            var newEditModal = '';
            var budget = budgets[id];
            newEditModal = `
                <div id="editContactModal${id}" tabindex="-1" aria-modal="true" role="dialog"
                    class="flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="absolute bg-white text-black p-6 rounded-lg shadow-lg w-2/3 max-h-[800px] card">
                        <!-- Header -->
                        <div class="flex justify-start">
                            <div class="flex items-center">
                                <h1 class="text-6xl font-bold text-yellow-700 font-mono">Detail Budget-Request</h1>
                            </div>
                            
                            <div class="w-72 h-32 ml-auto mb-5">
                                <img src="{{ asset('assets/images/logo/logowhite.png')  }}" class="dark-logo" alt="Logo-Dark">
                                <img src="{{ asset('assets/images/logo/logo.png') }}" class="light-logo" alt="Logo-light">
                            </div>
                        </div>
                        <hr class="my-10 border-t-2 rounded-md border-slate-900 opacity-90"> 

                        <!-- Keterangan -->
                        <div>
                            <div class="flex items-center mt-2">
                                <div class="form-group">
                                    <h1 class="form-label font-bold text-lg">From Department</h1>
                                    <input type="text" name="from_department" readonly value="${budget.from_department.department_name}"
                                        class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light flex-1"
                                        placeholder="From Department" required>
                                </div>
                                <div class="ml-auto form-group">
                                    <h1 class="form-label font-bold text-lg">Budget No</h1>
                                    <input type="text" name="no" readonly value="${budget.budget_req_no}"
                                        class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light"
                                        placeholder="Auto Fill" required>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="container mt-10">
                            <div x-data="{ scrolled: false }" @scroll="scrolled = $el.scrollTop > 0 || false"
                                class="overflow-y-auto max-h-[250px] mt-6">
                                <table class="table-auto w-full border-collapse" id="testTable">
                                    <thead :class="scrolled ? 'bg-white shadow-md border-none' : ''" class="sticky top-0 z-10">
                                        <tr>
                                            <th class="text-center w-fit">
                                                <h2>To Department</h2>
                                            </th>
                                            <th class="text-center w-fit">
                                                <h2>Amount</h2>
                                            </th>
                                            <th class="text-center w-fit">
                                                <h2>Reason</h2>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="max-h-[50vh] overflow-y-auto">
                                        <tr>
                                            <td>
                                                <input type="text" name="to_department" value="${budget.to_department.department_name}"
                                                class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                    placeholder="To Department" required>
                                            </td>
                                            <td>
                                                <input type="number" name="amount" value="${budget.amount}"
                                                class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                    placeholder="Input Number" required>
                                            </td>
                                            <td>
                                                <input type="text" name="reason" id="reason" value="${budget.reason}"
                                                    class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                    placeholder="Input reason" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="color: 
                                                ${budget.status.toLowerCase() == 'approved' || budget.status.toLowerCase() == 'approved with review' 
                                                ? 'green' 
                                                : budget.status.toLowerCase() == 'rejected' 
                                                ? 'red' 
                                                : 'black'}">
                                                ${budget.status.substring(0,1).toUpperCase()}${budget.status.substring(1).toLowerCase()}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                ${budget.feedback ?? ''}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex items-center justify-end mx-4 mt-4 gap-2">
                                <button type="button" class="btn btn-danger" data-modal-hide="editContactModal${id}" onClick="openEditModal(${id})">Exit</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            modalDiv.innerHTML += newEditModal;
                
        }
        
        function getTotalAmount(currInput)
        {
            var parent = currInput.parentNode.parentNode.parentNode;
            var quantityInput = parent.querySelector('.quantity');
            var amountInput = parent.querySelector('.amount');
            var totalInput = parent.querySelector('.total');
            // var totalInput = parent.lastElementChild.lastElementChild.firstElementChild;
            if(quantityInput.value <= 0)
            {
                quantityInput.value = 1;
            }
            if(amountInput.value <= 0)
            {
                amountInput.value = 1;
            }
            totalInput.value = parseFloat(quantityInput.value) * parseFloat(amountInput.value);

        }

        
        $('#yearFilter').on('change', function() {
            table.ajax.reload();  // Reload data table dengan filter department
        });
    </script>
    @endpush

</x-app-layout>