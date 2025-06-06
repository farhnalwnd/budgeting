<x-app-layout>
    @section('title')
        List Budget-List
    @endsection
    
    @push('css')
        <style>
            #testTable tr,
            #testTable td {
                border: 3px solid black;
            }
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
                        <li class="breadcrumb-item pr-1">Budget List</li>
                        <li class="breadcrumb-item active">List Budget-List</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section x-data="{open : false}" class="content">
        <!-- Add Budget List Button -->
        <div class="mb-4 flex justify-end">
            <button type="button" @click="open = ! open" 
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right">
                {{-- data-modal-target="createBudgetModal" data-modal-toggle="createBudgetModal" --}}
                Add Budget-List
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">List Budget-List</h1>
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
                                <th scope="col" class="px-6 py-3 text-lg">Name</th>
                                <th scope="col" class="px-6 py-3 text-lg">Category</th>
                                <th scope="col" class="px-6 py-3 text-lg">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-lg">UM</th>
                                <th scope="col" class="px-6 py-3 text-lg">Amount</th>
                                <th scope="col" class="px-6 py-3 text-lg">Total amount</th>
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
                        <h1 class="text-6xl font-bold text-yellow-700 font-mono">Create Budget-List</h1>
                    </div>
                    
                    <div class="w-72 h-32 ml-auto mb-5">
                        <img src="{{ asset('assets/images/logo/logowhite.png')  }}" class="dark-logo" alt="Logo-Dark">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" class="light-logo" alt="Logo-light">
                    </div>
                </div>
                <hr class="my-10 border-t-2 rounded-md border-slate-900 opacity-90">

                <form method="POST" action="{{ route('budget-list.store') }}">
                    @csrf
                    <!-- Keterangan -->
                    <div>
                        <div class="flex items-center mt-2">
                            <div class="form-group">
                                <h1 class="font-bold text-lg">Budget No<span
                                        class="text-danger">*</span></h1>
                                <div class="controls">
                                    <select name="no" id="no" required
                                        class="form-select w-full text-xl" aria-invalid="false"
                                        placeholder="Budget No">
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="ml-auto form-group">
                                <h1 class="font-bold text-lg">Category<span
                                    class="text-danger">*</span></h1>
                                <div class="controls">
                                    <select name="category" id="category" required
                                        class="form-select w-full text-xl" aria-invalid="false"
                                        placeholder="Category">
                                    </select>
                                    <div class="help-block"></div>
                                </div>
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
                                            <h2>Name</h2>
                                        </th>
                                        <th class="text-center w-48">
                                            <h2>Amount (RP)</h2>
                                        </th>
                                        <th class="text-center w-28">
                                            <h2>Qty</h2>
                                        </th>
                                        <th class="text-center w-48">
                                            <h2>Total</h2>
                                        </th>
                                        <th class="text-center w-56">
                                            <h2>UM</h2>
                                        </th>
                                        <th class="text-center w-36">
                                            <h2>ACTION</h2>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="max-h-[50vh] overflow-y-auto">
                                    <tr>
                                        <td><input type="text" name="name[]"
                                            class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                            required>
                                        </td>
                                        <td>
                                            <input type="text" name="amount[]"
                                                class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light price-input" 
                                                maxlength="17" required>
                                        </td>
                                        <td>
                                            <input type="number" name="quantity[]"
                                                class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light quantity-input" 
                                                min="0" maxlength="2" required>
                                        </td>
                                        <td>
                                            <input type="text" name="total[]"
                                                class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light total-input" 
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="um[]"
                                                class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                required>
                                        </td>
                                        <td class="text-center">
                                            <div class="flex justify-center space-x-1">
                                                <button type="button" class="remove-row btn btn-danger text-base px-2 py-1">Remove</button>
                                                <button type="button" class="clear-btn btn btn-warning text-base px-2 py-1">Clear</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="my-3 flex">
                            <div class="mr-auto"></div>
                            <div>
                                <h3 class="text-lg font-mono font-semibold">Grand Total : <span id="grand-total"></span></h3>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mx-4 mt-4">
                            <div>
                                <button type="button" id="add-row" class="btn btn-primary group active:scale-90 transition-transform duration-200">
                                    <svg class="w-[27px] h-[27px] text-gray-800 dark:text-white transform transition-transform duration-200 group-hover:scale-125 group-active:scale-90"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                            d="M9 5v14m8-7h-2m0 0h-2m2 0v2m0-2v-2M3 11h6m-6 4h6m11 4H4c-.55228 0-1-.4477-1-1V6c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1v12c0 .5523-.4477 1-1 1Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="btn btn-success" onClick="event.preventDefault(); confirmBudgetCreate(this)">Simpan</button>
                                <button @click="open = !open" type="button" class="btn btn-danger">Exit</button>
                            </div>
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
        var budgets = null;
        var allocations = null;
        var categories = null;
        var table = null;
        document.addEventListener('DOMContentLoaded', function() {
            // get budget-list year list
            $.ajax({
                url: '{{ route('get.budget-list.year') }}',
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

            // get all budget-allocation list
            $.ajax({
                url: '{{ route('get.budget-allocation.all') }}',
                method: 'GET',
                success: function(response) {
                    allocations = response;
                    var select = document.getElementById('no');
                    allocations.forEach(allocation => {
                        var option = document.createElement('option');
                        option.value = allocation.budget_allocation_no;
                        option.textContent = allocation.budget_allocation_no;
                        select.appendChild(option);
                    });
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data budget allocation.');
                }
            });

            // get category list
            $.ajax({
                url: '{{ route('get.category.data') }}',
                method: 'GET',
                success: function(response) {
                    categories = response;
                    var select = document.getElementById('category');
                    categories.forEach(category => {
                        var option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        select.appendChild(option);
                    });
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data category.');
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
                        url: '{{ route('get.budget.list') }}',
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
                        { data: 'budget_allocation_no', name: 'no' },
                        { data: 'name', name: 'name' },
                        { data: 'category.name', name: 'category' },
                        { data: 'quantity', name: 'quantity' },
                        { data: 'um', name: 'um' },
                        { data: 'default_amount', name: 'amount',
                            render: function(data, type, row) {
                                if (data == null) return '-';

                                return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                                }).format(data);
                            }
                        },
                        { data: 'total_amount', name: 'total',
                            render: function(data, type, row) {
                                if (data == null) return '-';
                                
                                return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                                }).format(data);
                            }
                        },
                        { data: null, name: 'action', orderable: false, searchable: false,
                            render: function(data, type, row, meta) {
                                var id = row.id;
                                var deleteUrl = "{{ route('budget-list.destroy', ':id') }}".replace(':id', id); 
                                return `
                                <div class="d-flex action-btn">
                                    <a href="javascript:void(0)" class="text-primary edit" onClick="openEditModal(${meta.row})">
                                        <i class="ti ti-eye fs-5"></i>
                                    </a>
                                    <form id="delete-form-${id}" action="${deleteUrl}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <a href="javascript:void(0)" class="text-dark delete ms-2"
                                            data-budget-id="${id}" onClick="event.preventDefault(); confirmBudgetDelete(this)">
                                            <i class="ti ti-trash fs-5"></i>
                                        </a>
                                    </form>
                                </div>
                                `;  
                            }
                        }
                    ]
                });
            }
        });

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

        // Function untuk konfirmasi edit budget
        function confirmBudgetEdit(button, divId){
            var form = button.closest('form');
            var actionUrl = form.getAttribute('action');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, edit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tutup edit modal div
                    openEditModal(divId);

                    // Kirim form
                    $.ajax({
                        url: actionUrl,
                        method: 'PUT',
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
            var updateUrl = "{{ route('budget-list.update', ':id') }}".replace(':id', budget.id); 
            newEditModal = `
                <div id="editContactModal${id}" tabindex="-1" aria-modal="true" role="dialog"
                    class="flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="absolute bg-white text-black p-6 rounded-lg shadow-lg w-2/3 max-h-[800px] card">
                        <!-- Header -->
                        <div class="flex justify-start">
                            <div class="flex items-center">
                                <h1 class="text-6xl font-bold text-yellow-700 font-mono">Update Budget-List</h1>
                            </div>
                            
                            <div class="w-72 h-32 ml-auto mb-5">
                                <img src="{{ asset('assets/images/logo/logowhite.png')  }}" class="dark-logo" alt="Logo-Dark">
                                <img src="{{ asset('assets/images/logo/logo.png') }}" class="light-logo" alt="Logo-light">
                            </div>
                        </div>
                        <hr class="my-10 border-t-2 rounded-md border-slate-900 opacity-90">

                        <form method="POST" action="${updateUrl}">
                            @csrf
                            @method('PUT')
                            <!-- Keterangan -->
                            <div>
                                <div class="flex items-center mt-2">
                                    <div class="form-group">
                                        <h1 class="font-bold text-lg">Budget No<span
                                                class="text-danger">*</span></h1>
                                        <div class="controls">
                                            <select name="no" required
                                                class="form-select w-full text-xl" aria-invalid="false"
                                                placeholder="Budget No">`;
                                                    allocations.forEach(function(allocation){
                                                        newEditModal +=`
                                                        <option value="${allocation.budget_allocation_no}" ${allocation.budget_allocation_no == budget.budget_allocation_no ? 'selected' : ''}>
                                                            ${allocation.budget_allocation_no}
                                                        </option>
                                                        `;
                                                    });
                                                    newEditModal += `
                                            </select>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="ml-auto form-group">
                                        <h1 class="font-bold text-lg">Category<span
                                            class="text-danger">*</span></h1>
                                        <div class="controls">
                                            <select name="category" required
                                                class="form-select w-full text-xl" aria-invalid="false"
                                                placeholder="Category">`;
                                                    categories.forEach(function(category){
                                                        newEditModal +=`
                                                        <option value="${category.id}" ${category.name == budget.category.name ? 'selected' : ''}>
                                                            ${category.name}
                                                        </option>
                                                        `;
                                                    });
                                                    newEditModal += `
                                            </select>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="container mt-10">
                                <div x-data="{ scrolled: false }" @scroll="scrolled = $el.scrollTop > 0 || false"
                                    class="overflow-y-auto max-h-[250px] mt-6">
                                    <table class="table-auto w-full border-collapse">
                                        <thead :class="scrolled ? 'bg-white shadow-md border-none' : ''" class="sticky top-0 z-10">
                                            <tr>
                                                <th class="text-center w-fit">
                                                    <h2>Name</h2>
                                                </th>
                                                <th class="text-center w-48">
                                                    <h2>Amount (RP)</h2>
                                                </th>
                                                <th class="text-center w-28">
                                                    <h2>Qty</h2>
                                                </th>
                                                <th class="text-center w-48">
                                                    <h2>Total</h2>
                                                </th>
                                                <th class="text-center w-56">
                                                    <h2>UM</h2>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="max-h-[50vh] overflow-y-auto">
                                            <tr>
                                                <td><input type="text" name="name" value="${budget.name}"
                                                    class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                    required>
                                                </td>
                                                <td>
                                                    <input type="text" name="amount" value="${toRupiah(budget.default_amount)}" onKeydown="editOnPriceKeydown(event)" onBlur="editOnBlur(this)" onFocus="editOnFocus(this)" onInput="editOnPriceInput(this)"
                                                        class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light price-input" 
                                                        maxlength="17" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="quantity" value="${budget.quantity}" onKeydown="editOnQuantityKeydown(event)" onInput="editOnQuantityInput(this)"
                                                        class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light quantity-input" 
                                                        min="0" maxlength="2" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="total" value="${toRupiah(budget.total_amount)}"
                                                        class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light total-input" 
                                                        readonly>
                                                </td>
                                                <td>
                                                    <input type="text" name="um" value="${budget.um}"
                                                        class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                        required>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex items-center justify-end mx-4 mt-4 gap-2">
                                    <button type="submit" class="btn btn-success" onClick="event.preventDefault(); confirmBudgetEdit(this, ${id})">Simpan</button>
                                    <button type="button" class="btn btn-danger" data-modal-hide="editContactModal${id}" onClick="openEditModal(${id})">Exit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            
            modalDiv.innerHTML += newEditModal;
                
        }

            
        function editOnPriceKeydown(e)
        {
            const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
            if (!(e.key >= '0' && e.key <= '9') && !allowedKeys.includes(e.key)) {
                e.preventDefault();
            }
        }

        function editOnBlur(input)
        {
            const numeric = parseRupiah(input.value) || 0;
            input.value = toRupiah(numeric);
            updateTotalEdit(input.closest('tr'));
        }

        function editOnFocus(input)
        {
            const numeric = parseRupiah(input.value);
            input.value = numeric > 0 ? numeric.toString() : '';
        }

        function editOnPriceInput(input)
        {
            const cursorPosition = input.selectionStart;
            const numeric = parseRupiah(input.value);
            input.value = toRupiah(numeric);

            setTimeout(() => {
                input.setSelectionRange(input.value.length, input.value.length);
            }, 0);

            updateTotalEdit(input.closest('tr'));
        }

        function editOnQuantityKeydown(e)
        {
            const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
            if (!(e.key >= '0' && e.key <= '9') && !allowedKeys.includes(e.key)) {
                e.preventDefault();
            }
        }

        function editOnQuantityInput(input)
        {
            let val = parseInt(input.value) || 0;
            input.value = val > 99 ? '99' : (val < 0 ? '0' : val.toString());
            updateTotalEdit(input.closest('tr'));
        }

        function updateTotalEdit(row)
        {
            const priceInput = row.querySelector('.price-input');
            const quantityInput = row.querySelector('.quantity-input');
            const totalInput = row.querySelector('.total-input');

            const price = parseRupiah(priceInput.value);
            const quantity = parseFloat(quantityInput.value) || 0;
            const total = price * quantity;

            totalInput.value = toRupiah(total);
        }

        function toRupiah(number) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(number);
        }

        function parseRupiah(rpString) {
                                return parseInt(rpString.replace(/[^0-9]/g, '')) || 0;
                            }

        function updateTotal(row) {
                                const priceInput = row.querySelector('.price-input');
                                const quantityInput = row.querySelector('.quantity-input');
                                const totalInput = row.querySelector('.total-input');

                                const price = parseRupiah(priceInput.value);
                                const quantity = parseFloat(quantityInput.value) || 0;
                                const total = price * quantity;

                                totalInput.value = toRupiah(total);
                                updateGrandTotal();
                            }

        function updateGrandTotal() {
            const totalInputs = document.querySelectorAll('.total-input');
            let grandTotal = 0;

            totalInputs.forEach(input => {
                grandTotal += parseRupiah(input.value);
            });


            document.getElementById('grand-total').innerText = toRupiah(grandTotal);
        }

        function formatPriceInput(input) {
                    input.addEventListener('keydown', function (e) {
                        const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
                        if (!(e.key >= '0' && e.key <= '9') && !allowedKeys.includes(e.key)) {
                            e.preventDefault();
                        }
                });

                input.addEventListener('blur', function () {
                    const numeric = parseRupiah(this.value) || 0;
                    this.value = toRupiah(numeric);
                    updateTotal(input.closest('tr'));
                });

                input.addEventListener('focus', function () {
                    const numeric = parseRupiah(this.value);
                    this.value = numeric > 0 ? numeric.toString() : '';
                });

                input.addEventListener('input', function () {
                    const cursorPosition = input.selectionStart;
                    const numeric = parseRupiah(input.value);
                    input.value = toRupiah(numeric);
                    updateTotal(input.closest('tr'));

                    setTimeout(() => {
                        input.setSelectionRange(input.value.length, input.value.length);
                    }, 0);

                    updateTotal(input.closest('tr'));
                });
                }

        function formatQuantityInput(input, row) {
                    input.addEventListener('keydown', function (e) {
                        const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
                        if (!(e.key >= '0' && e.key <= '9') && !allowedKeys.includes(e.key)) {
                            e.preventDefault();
                        }
                });

                input.addEventListener('input', function () {
                    let val = parseInt(this.value) || 0;
                    this.value = val > 99 ? '99' : (val < 0 ? '0' : val.toString());
                    updateTotal(row);
                });
        }

    function clearRow(row) {
        row.querySelector('input[name="name[]"]').value = '';
        row.querySelector('.price-input').value = toRupiah(0);
        row.querySelector('.quantity-input').value = '';
        row.querySelector('input[name="um[]"]').value = '';
        row.querySelector('.total-input').value = toRupiah(0);
        updateTotal(row);
    }

    function setupRow(row) {
        const priceInput = row.querySelector('.price-input');
        const quantityInput = row.querySelector('.quantity-input');

        formatPriceInput(priceInput);
        formatQuantityInput(quantityInput, row);

        row.querySelector('.clear-btn')?.addEventListener('click', function () {
            clearRow(row);
            });

        row.querySelector('.remove-row')?.addEventListener('click', function () {
            const rows = document.querySelectorAll('#testTable tbody tr');
            if (rows.length > 1) {
                row.remove();
                updateGrandTotal();
                }
        });
    }

                               
            // Setup semua baris awal
            document.querySelectorAll('#testTable tbody tr').forEach(row => {
                                            row.querySelector('.price-input').value = toRupiah(0);
                                            row.querySelector('.total-input').value = toRupiah(0);
                                            setupRow(row);
        });

            // Tambah baris baru
            document.getElementById('add-row')?.addEventListener('click', function () {
                                            const tableBody = document.querySelector('#testTable tbody');
                                            const newRow = tableBody.querySelector('tr').cloneNode(true);
                                            newRow.querySelector('input[name="name[]"]').value = '';
                                            newRow.querySelector('.price-input').value = toRupiah(0);
                                            newRow.querySelector('.quantity-input').value = '';
                                            newRow.querySelector('input[name="um[]"]').value = '';
                                            newRow.querySelector('.total-input').value = toRupiah(0);

                                            tableBody.appendChild(newRow);
                                            setupRow(newRow);
        });

            // Inisialisasi nilai awal saldo
            updateGrandTotal();

        
        
        $('#yearFilter').on('change', function() {
            table.ajax.reload();  // Reload data table dengan filter department
        });
    </script>
    @endpush

</x-app-layout>