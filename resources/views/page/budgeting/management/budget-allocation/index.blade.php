<x-app-layout>
    @section('title')
        List Budget-Allocation
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
                        <li class="breadcrumb-item pr-1">Budget Allocation</li>
                        <li class="breadcrumb-item active">List Budget Allocation</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <!-- Add Budget Allocation Button -->
        <div class="mb-4 flex justify-end">
            <button type="button"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right"
                data-modal-target="createBudgetModal" data-modal-toggle="createBudgetModal">
                Add Budget Allocation
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">List Budget Allocation</h1>
            </div>
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="budgetTable" class="table table-striped w-full text-left rtl:text-right table-bordered" style="width: 100%;">
                        <thead class="uppercase border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-lg">#</th>
                                <th scope="col" class="px-6 py-3 text-lg">No</th>
                                <th scope="col" class="px-6 py-3 text-lg">Department</th>
                                <th scope="col" class="px-6 py-3 text-lg">Description</th>
                                <th scope="col" class="px-6 py-3 text-lg">Total amount</th>
                                <th scope="col" class="px-6 py-3 text-lg">Wallet</th>
                                <th scope="col" class="px-6 py-3 text-lg">Allocated by</th>
                                <th scope="col" class="px-6 py-3 text-lg">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    

    {{-- {-- Create Budget Allocation Modal --} --}}
    <div id="createBudgetModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" style="margin-top: 10%;">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-3xl font-semibold text-white">Create Budget Allocation</h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="createBudgetModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewbox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 overflow-y-auto max-h-96">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="space-y-4" action="{{ route('budget-allocation.store') }}" method="POST" id="createBudgetForm">
                        @csrf
                        <div class="form-group">
                            <label class="form-label text-white text-xl">Budget No<span
                                    class="text-danger">*</span></label>
                            <div class="controls">
                                <input type="text" name="no" id="no"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    required readonly placeholder="Budget No">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label text-white text-xl">Department<span
                                    class="text-danger">*</span></label>
                            <div class="controls">
                                <select name="department" id="department" required onChange="getBudgetNumber()"
                                    class="form-select w-full text-xl" aria-invalid="false"
                                    placeholder="Department">
                                    <option value="" selected disabled>Select Department</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label text-white text-xl">Description</label>
                            <div class="controls">
                                <input type="text" name="description" id="description"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Budget Name">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Create
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div id="editModalDiv">
        <div id="modalBackground" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40 hidden"></div>
    </div>

    @push('scripts')
    <script>
        var budgets = null;
        var departments = null;
        document.addEventListener('DOMContentLoaded', function() {
            
            // get department list
            $.ajax({
                url: '{{ route('get.department.data') }}',
                method: 'GET',
                success: function(response) {
                    departments = response;

                    var departmentSelect = document.getElementById('department');
                    departments.forEach(department => {
                        var option = document.createElement('option');
                        option.value = department.id;
                        option.textContent = department.department_name;
                        departmentSelect.appendChild(option);
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
                    url: '{{ route('get.budget.data') }}',
                    type: 'GET',
                    dataSrc: function(response) {
                        console.log(response);
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
                    { data: 'department.department_name', name: 'department' },
                    { data: 'description', name: 'description' },
                    { data: 'total_amount', name: 'total' },
                    { data: 'department.wallet.balance', name: 'wallet' },
                    { data: 'allocated_by', name: 'allocated' },
                    { data: null, name: 'action', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            var id = row.budget_allocation_no;
                            var deleteUrl = "{{ route('budget-allocation.destroy', ':id') }}".replace(':id', id.replaceAll("/", "-")); 
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
            var updateUrl = "{{ route('budget-allocation.update', ':id') }}".replace(':id', budget.budget_allocation_no.replaceAll("/", "-")); 
            newEditModal = `
                <div id="editContactModal${id}" tabindex="-1" aria-modal="true" role="dialog"
                    class="flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" style="margin-top: 10%;">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-3xl font-semibold text-white">Update Budget</h3>
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
                                <form class="space-y-4" action="${updateUrl}" method="POST" id="updateBudgetForm">
                                @csrf
                                @method('PUT')
                                    <div class="form-group">
                                        <label class="form-label text-white text-xl">Budget No<span
                                                class="text-danger">(readonly)</span></label>
                                        <div class="controls">
                                            <input type="text" name="no"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required readonly placeholder="Budget No" value="${budget.budget_allocation_no}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label text-white text-xl">Department<span
                                                class="text-danger">(readonly)</span></label>
                                        <div class="controls">
                                            <select name="department" required readonly disabled
                                                class="form-select w-full text-xl" aria-invalid="false"
                                                placeholder="Department">
                                                `;
                                                departments.forEach(function(department){
                                                    newEditModal +=`
                                                    <option value="${department.id}" ${budget.department_id == department.id ? 'selected' : ''}>
                                                        ${department.department_name}
                                                    </option>
                                                    `;
                                                });
                                                newEditModal += `
                                            </select>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label text-white text-xl">Description</label>
                                        <div class="controls">
                                            <input type="text" name="description" value="${budget.description ?? ''}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                placeholder="Budget Name">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Update
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            modalDiv.innerHTML += newEditModal;
                
        }
       
        function getBudgetNumber(){
            // Get no budget new
            var departmentSelectValue = document.getElementById('department').value;
            console.log(departmentSelectValue);
            $.ajax({
                url: '{{ route('get.budget.no') }}',
                method: 'GET',
                data: {
                    departmentId: departmentSelectValue
                },
                success: function(response) {
                    console.log(response);
                    var no = document.getElementById('no');
                    no.value = response;
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil nomor budget.');
                }
            });

        }

    </script>
    @endpush

</x-app-layout>