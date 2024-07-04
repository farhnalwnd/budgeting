<x-app-layout>
    @section('title')
    List Departments
    @endsection
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-lg">List Department</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page">Departemen</li>
                        <li class="breadcrumb-item active" aria-current="page">List Departemen</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <!-- Add Department Button -->
        <div class="mb-4 flex justify-end">
            <button type="button" class="px-4 py-3 bg-blue-500 text-white rounded hover:bg-blue-600"
                data-modal-target="createDepartmentModal" data-modal-toggle="createDepartmentModal">
                Tambah Departemen
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="departmentsTable"
                        class="table table-striped w-full  text-left rtl:text-right table-bordered">
                        <thead class="uppercase border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-lg">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3 text-lg">
                                    Nama Departemen
                                </th>
                                <th class="px-6 py-3 text-lg text-center">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $item)
                                <tr>
                                    <td class="px-6 py-4 text-lg">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 text-lg">
                                        {{ $item->department_name }}
                                    </td>
                                    <td class="flex items-center justify-center text-lg space-x-4">
                                        <button type='button'
                                            data-modal-target="createDepartmentModal-{{ $item->id }}"
                                            data-modal-toggle="createDepartmentModal-{{ $item->id }}"
                                            class="text-fade hover:text-yellow-800">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-edit-2 align-middle">
                                            <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                                        </svg>
                                        </button>
                                        <form action="/departments/{{ $item->department_slug }}/delete" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-fade hover:text-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-trash align-middle">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#departmentsTable').DataTable({
                    "lengthChange": false,
                    "pagingType": "simple_numbers",
                    "dom": 'Bfrtip',
                    "buttons": [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            });

            // SweetAlert2 confirmation dialog for delete action
            $('.delete-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting

                var form = this; // Store a reference to the form

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit the form if confirmed
                    }
                });
            });

            @if (session()->has('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session()->get('success') }}',
                    text: '{{ session()->get('message') }}',
                });
            @endif

            // Function to toggle modal visibility
            function toggleModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.classList.toggle('hidden');
                modal.classList.toggle('flex');
            }

            // Function to hide modal
            function hideModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            // Event listener for modal toggles
            document.addEventListener('click', function(e) {
                if (e.target.dataset.modalToggle !== undefined) {
                    const targetModal = e.target.dataset.modalTarget;
                    toggleModal(targetModal);
                }
            });

            // Event listener for modal hides
            document.addEventListener('click', function(e) {
                if (e.target.dataset.modalHide !== undefined) {
                    const targetModal = e.target.dataset.modalHide;
                    hideModal(targetModal);
                }
            });
        </script>
    @endpush
    {{-- Modal Create --}}
    <div id="createDepartmentModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Create Department
                    </h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="createDepartmentModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewbox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form class="space-y-4" action="{{ route('department.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="department_name"
                                class="block mb-2 text-xl font-medium text-gray-900 dark:text-white">Nama
                                Department</label>
                            <input type="text" name="department_name" id="department_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Department Name" required="">
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Edit --}}
    @foreach ($departments as $item)
        <div id="createDepartmentModal-{{ $item->id }}" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Edit Department
                        </h3>
                        <button type="button"
                            class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="createDepartmentModal-{{ $item->id }}">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewbox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <form class="space-y-4"
                            action="{{ route('department.update', ['department' => $item->department_slug]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="department_name"
                                    class="block mb-2 text-xl font-medium text-gray-900 dark:text-white">Nama
                                    Department</label>
                                <input type="text" name="department_name" id="department_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Department Name" required=""
                                    value="{{ $item->department_name }}">
                            </div>
                            <button type="submit"
                                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Edit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


</x-app-layout>
