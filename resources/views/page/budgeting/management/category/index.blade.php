<x-app-layout>
    @section('title')
        List Category
    @endsection
    
    @push('css')
        <style>
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
                        <li class="breadcrumb-item pr-1">Category</li>
                        <li class="breadcrumb-item active">List Category</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section x-data="{open : false}" class="content">
        <!-- Add Category Button -->
        <div class="mb-4 flex justify-end">
            <button type="button" @click="open = ! open"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right">
                Add Category
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">List Category</h1>
            </div>
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="categoryTable" class="table table-striped w-full text-left rtl:text-right table-bordered" style="width:100%;">
                        <thead class="uppercase border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-lg">#</th>
                                <th scope="col" class="px-6 py-3 text-lg">Name</th>
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
                        <h1 class="text-6xl font-bold text-yellow-700 font-mono">Create Category</h1>
                    </div>
                    
                    <div class="w-72 h-32 ml-auto">
                        <img src="{{ asset('assets/images/logo/logowhite.png')  }}" class="dark-logo" alt="Logo-Dark">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" class="light-logo" alt="Logo-light">
                    </div>
                </div>
                <hr class="my-10 border-t-2 rounded-md border-slate-900 opacity-90">

                <form method="POST" action="{{ route('category.store') }}">
                    <!-- Table -->
                    <div class="container mt-10">
                        @csrf
                        <div x-data="{ scrolled: false }" @scroll="scrolled = $el.scrollTop > 0 || false"
                            class="overflow-y-auto max-h-[250px] mt-6">
                            <table class="table-auto w-full border-collapse" id="testTable">
                                <thead :class="scrolled ? 'bg-white shadow-md border-none' : ''" class="sticky top-0 z-10">
                                    <tr>
                                        <th class="text-center w-fit">
                                            <h2>Name</h2>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="max-h-[50vh] overflow-y-auto">
                                    <tr>
                                        <td>
                                            <input type="text" name="name" id="name" placeholder="Category Name"
                                            class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                            required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-end mx-4 mt-4 gap-2">
                            <button type="button" class="btn btn-success" onClick="event.preventDefault(); confirmCategoryCreate(this)">Simpan</button>
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
        var categories = null;
        var table = null;
        document.addEventListener('DOMContentLoaded', function() {
            // Init datatable
            table = $('#categoryTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: '{{ route('get.category.data') }}',
                    type: 'GET',
                    dataSrc: function(response) {
                        categories = response;
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
                    { data: 'name', name: 'name' },
                    { data: null, name: 'action', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            var id = row.id;
                            var deleteUrl = "{{ route('category.destroy', ':id') }}".replace(':id', id); 
                            return `
                            <div class="d-flex action-btn">
                                <a href="javascript:void(0)" class="text-primary edit" onClick="openEditModal(${meta.row})">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                <form id="delete-form-${id}" action="${deleteUrl}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a href="javascript:void(0)" class="text-dark delete ms-2"
                                        data-category-id="${id}" onClick="event.preventDefault(); confirmCategoryDelete(this)">
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

        // Function untuk konfirmasi create category
        function confirmCategoryCreate(button){
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

        // Function untuk konfirmasi edit category
        function confirmCategoryEdit(button, divId){
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

        // Function untuk konfirmasi delete category
        function confirmCategoryDelete(button){
            var categoryId = button.getAttribute('data-category-id');
            var form = document.getElementById('delete-form-' + categoryId);
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
            var category = categories[id];
            var updateUrl = "{{ route('category.update', ':id') }}".replace(':id', category.id); 
            newEditModal = `
                <div id="editContactModal${id}" tabindex="-1" aria-modal="true" role="dialog"
                    class="flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="absolute bg-white text-black p-6 rounded-lg shadow-lg w-2/3 max-h-[800px] card">
                        <!-- Header -->
                        <div class="flex justify-start">
                            <div class="flex items-center">
                                <h1 class="text-6xl font-bold text-yellow-700 font-mono">Update Category</h1>
                            </div>
                            
                            <div class="w-72 h-32 ml-auto mb-5">
                                <img src="{{ asset('assets/images/logo/logowhite.png')  }}" class="dark-logo" alt="Logo-Dark">
                                <img src="{{ asset('assets/images/logo/logo.png') }}" class="light-logo" alt="Logo-light">
                            </div>
                        </div>
                        <hr class="my-10 border-t-2 rounded-md border-slate-900 opacity-90">

                        <form method="POST" action="${updateUrl}">
                            <!-- Table -->
                            <div class="container mt-10">
                                @csrf
                                @method('PUT')
                                <div x-data="{ scrolled: false }" @scroll="scrolled = $el.scrollTop > 0 || false"
                                    class="overflow-y-auto max-h-[250px] mt-6">
                                    <table class="table-auto w-full border-collapse" id="testTable">
                                        <thead :class="scrolled ? 'bg-white shadow-md border-none' : ''" class="sticky top-0 z-10">
                                            <tr>
                                                <th class="text-center w-fit">
                                                    <h2>Name</h2>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="max-h-[50vh] overflow-y-auto">
                                            <tr>
                                                <td>
                                                    <input type="text" name="name" placeholder="Category Name" value="${category.name}"
                                                    class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                    required>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex items-center justify-end mx-4 mt-4 gap-2">
                                    <button type="submit" class="btn btn-success" onClick="event.preventDefault(); confirmCategoryEdit(this, ${id})">Simpan</button>
                                    <button type="button" class="btn btn-danger" data-modal-hide="editContactModal${id}" onClick="openEditModal(${id})">Exit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            `;

            modalDiv.innerHTML += newEditModal;
                
        }
    </script>
    
    @endpush

</x-app-layout>