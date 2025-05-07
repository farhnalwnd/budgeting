<x-app-layout>
    @section('title')
        List Approver
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
                        <li class="breadcrumb-item pr-1">Approver</li>
                        <li class="breadcrumb-item active">List Approver</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <!-- Add Approver Button -->
        <div class="mb-4 flex justify-end">
            <button type="button"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right"
                data-modal-target="createApproverModal" data-modal-toggle="createApproverModal">
                Add Approver
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">List Approver</h1>
            </div>
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="activityTable" class="table table-striped w-full text-left rtl:text-right table-bordered" style="width: 100%;">
                        <thead class="uppercase border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-lg">#</th>
                                <th scope="col" class="px-6 py-3 text-lg">Department</th>
                                <th scope="col" class="px-6 py-3 text-lg">Approver</th>
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
    

    {{-- {-- Create Approver-List Modal --} --}}
    <div id="createApproverModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-4xl max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" style="margin-top: 10%;">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-3xl font-semibold text-white">Create Budget Approver</h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="createApproverModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewbox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 overflow-y-auto">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="space-y-4" action="{{ route('approver.store') }}" method="POST" id="createApproverForm">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label text-white text-xl">Department<span
                                        class="text-danger">*</span></label>
                                <div class="controls">
                                    <select name="department" id="department" required
                                        class="form-select w-full text-xl" aria-invalid="false"
                                        placeholder="Department">
                                        <option value="" selected disabled>Select Department</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label text-white text-xl">Approver<span
                                        class="text-danger">*</span></label>
                                <div class="controls">
                                    <select name="nik" id="nik" required
                                        class="form-select w-full text-xl" aria-invalid="false"
                                        placeholder="Department">
                                        <option value="" selected disabled>Select User</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
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
        var approvers = null;
        var users = null;
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

            // get users list
            $.ajax({
                url: '{{ route('get.users.data') }}',
                method: 'GET',
                success: function(response) {
                    users = response;

                    var nikSelect = document.getElementById('nik');
                    users.forEach(user => {
                        var option = document.createElement('option');
                        option.value = user.nik;
                        option.textContent = `${user.nik} - ${user.name}`;
                        nikSelect.appendChild(option);
                    });
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data department.');
                }
            });

            // Init datatable
            var table = $('#activityTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: '{{ route('get.approver.data') }}',
                    type: 'GET',
                    dataSrc: function(response) {
                        approvers = response;
                        return response;
                    }
                },
                columns: [
                    { data: null,
                        render: function(data, type, row, meta) {
                            return meta.row +1;
                        }
                    },
                    { data: 'department.department_name', name: 'department'},
                    { data: 'user.username', name: 'approver',
                        render: function(data, type, row, meta) {
                            return row.user.nik + ' - ' + row.user.username;
                        }
                    },
                    { data: null, name: 'action', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            var id = row.id;
                            var deleteUrl = "{{ route('approver.destroy', ':id') }}".replace(':id', id); 
                            return `
                            <div class="d-flex action-btn">
                                <a href="javascript:void(0)" class="text-primary edit" onClick="openEditModal(${meta.row})">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                <form id="delete-form-${id}" action="${deleteUrl}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a href="javascript:void(0)" class="text-dark delete ms-2"
                                        data-approver-id="${id}" onClick="confirmApproverDelete(this)">
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
       
        // Function untuk konfirmasi delete approver
        function confirmApproverDelete(button){
            var approverId = button.getAttribute('data-approver-id');
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
                    document.getElementById('delete-form-' + approverId).submit();
                }
            });
        }

        // Function untuk buat/buka modal
        function openEditModal(id){
            var modal = document.getElementById(`editApproverModal${id}`);
            var modalBackground = document.getElementById('modalBackground');
            modalBackground.classList.toggle('hidden');
            if (modal){
                modal.classList.toggle('hidden');
                modal.classList.toggle('flex');
                return;
            }
            var modalDiv = document.getElementById('editModalDiv');
            var newEditModal = '';
            var approver = approvers[id];
            var updateUrl = "{{ route('approver.update', ':id') }}".replace(':id', approver.id); 
            newEditModal = `
                <div id="editApproverModal${id}" tabindex="-1" aria-modal="true" role="dialog"
                    class="flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-4xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" style="margin-top: 10%;">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-3xl font-semibold text-white">Update Approver</h3>
                                <button type="button"
                                    class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="editApproverModal${id}" onClick="openEditModal(${id})">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewbox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <div class="p-4 md:p-5">
                                <form class="space-y-4" action="${updateUrl}" method="POST">
                                @csrf
                                @method('PUT')
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="form-group">
                                            <label class="form-label text-white text-xl">Department<span
                                                    class="text-danger">*</span></label>
                                            <div class="controls">
                                                <select name="department" required
                                                    class="form-select w-full text-xl" aria-invalid="false"
                                                    placeholder="Department">`;
                                                    departments.forEach(function(department){
                                                        newEditModal +=`
                                                        <option value="${department.id}" ${department.id == approver.department_id ? 'selected' : ''}>
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
                                            <label class="form-label text-white text-xl">Approver<span
                                                class="text-danger">*</span></label>
                                            <div class="controls">
                                                <select name="nik" required
                                                    class="form-select w-full text-xl" aria-invalid="false"
                                                    placeholder="NIK">`;
                                                    users.forEach(function(user){
                                                        newEditModal +=`
                                                        <option value="${user.nik}" ${user.nik == approver.nik ? 'selected' : ''}>
                                                            ${user.nik} - ${user.username}
                                                        </option>
                                                        `;
                                                    });
                                                    newEditModal += `
                                                    
                                                </select>
                                            </div>
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
    </script>
    @endpush

</x-app-layout>