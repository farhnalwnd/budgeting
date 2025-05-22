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
                    
                    <div class="w-72 h-32 ml-auto">
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
                            <button type="submit" class="btn btn-success">Simpan</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            
            fromDepartment = @json(Auth::user()->department);

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


            // Init datatable
            var table = $('#budgetTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: '{{ route('get.budget.request.list') }}',
                    type: 'GET',
                    dataSrc: function(response) {
                        console.log('berhasil', response);
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
                    { data: 'amount', name: 'amount' },
                    { data: 'budget_purchase_no', name: 'purchase_no' },
                    { data: 'reason', name: 'reason' },
                    { data: 'status', name: 'status' },
                    { data: null, name: 'action', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            var id = row.budget_req_no;
                            var deleteUrl = "{{ route('budget-request.destroy', ':id') }}".replace(':id', id.replaceAll("/", "-")); 
                            return `
                            <div class="d-flex action-btn">
                                <a href="javascript:void(0)" class="text-primary edit" onClick="openEditModal(${meta.row})">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                <form id="delete-form-${id}" action="${deleteUrl}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a href="javascript:void(0)" class="text-dark delete ms-2"
                                        data-budget-id="${id}" onClick="confirmBudgetDelete(this)">
                                        <i class="ti ti-trash fs-5"></i>
                                    </a>
                                </form>
                            </div>
                            `;  
                        }
                    }
                ]
            });
        });


        // Function untuk konfirmasi delete budget
        function confirmBudgetDelete(button){
            var budgetId = button.getAttribute('data-budget-id');
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
                    document.getElementById('delete-form-' + budgetId).submit();
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
                    <div class="relative p-4 w-full max-w-4xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" style="margin-top: 10%;">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-3xl font-semibold text-white">Detail Budget</h3>
                                <button type="button"
                                    class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="editContactModal${id}" onClick="openEditModal(${id})">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewbox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <div class="p-4 md:p-5">
                                <div class="form-group">
                                    <label for="No Budget"
                                        class="block mb-2 text-xl font-medium text-gray-900 dark:text-white">No Budget</label>
                                    <input type="text" name="no" readonly value="${budget.budget_req_no}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="No Budget" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label for="from_department"
                                            class="block mb-2 text-xl font-medium text-gray-900 dark:text-white">From Department</label>
                                        <input type="text" name="from_department" readonly value="${budget.from_department.department_name}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="From Department" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="to_department"
                                                class="form-label text-white text-xl">Department</label>
                                        <div class="controls">
                                            <input type="text" name="to_department" readonly value="${budget.to_department.department_name}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="From Department" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount"
                                            class="block mb-2 text-xl font-medium text-gray-900 dark:text-white">Amount</label>
                                        <input type="number" name="amount" value="${budget.amount}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="Input Number" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="reason"
                                            class="block mb-2 text-xl font-medium text-gray-900 dark:text-white">Reason</label>
                                        <input type="text" name="reason" value="${budget.reason}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="Input reason" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            newEditModal = `
                <div id="editContactModal${id}" tabindex="-1" aria-modal="true" role="dialog"
                    class="flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="absolute bg-white text-black p-6 rounded-lg shadow-lg w-2/3 max-h-[800px] card">
                        <!-- Header -->
                        <div class="flex justify-start">
                            <div class="flex items-center">
                                <h1 class="text-6xl font-bold text-yellow-700 font-mono">Detail Budget-Request</h1>
                            </div>
                            
                            <div class="w-72 h-32 ml-auto">
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
            console.log(quantityInput.value, amountInput.value, totalInput.value);
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

    </script>
    @endpush

</x-app-layout>