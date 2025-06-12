<x-app-layout>
    @section('title')
        List Budget-Request Approval
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
                        <li class="breadcrumb-item pr-1">Budget Request Approval</li>
                        <li class="breadcrumb-item active">List Budget-Request Approval</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
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
                                <th scope="col" class="px-6 py-3 text-lg">Amount</th>
                                <th scope="col" class="px-6 py-3 text-lg">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Edit User -->
    <div id="resendModalDiv">
        <div id="modalBg" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40 hidden"></div>
    </div>

    <!-- Modal Edit User -->
    <div id="editModalDiv">
        <div id="modalBackground" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40 hidden"></div>
    </div>

    @push('scripts')
    <script>
        var budgets = null;
        var table = null;
        document.addEventListener('DOMContentLoaded', function() {


            // Init datatable
            table = $('#budgetTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: '{{ route('get.budget-request.approval.list') }}',
                    type: 'GET',
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
                    { data: 'status', name: 'status' }
                ]
            });
        });

        async function resendEmail(purchaseNo) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch('getResendEmail', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        budget_purchase_no: purchaseNo
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire('Sukses!', 'Email berhasil dikirim ulang.', 'success');
                } else {
                    throw new Error(data.message || 'Gagal mengirim ulang email.');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Gagal!', error.message, 'error');
            }
        }

        function closeResendModal(data) {
            var modal = document.getElementById(`detail-${data}`);
            var modalBackground = document.getElementById('modalBg');
            modalBackground.classList.toggle('hidden');
            if (modal) {
                modal.classList.toggle('hidden');
                modal.classList.toggle('flex');
                modal.remove();
                return;
            }
            var modalDiv = document.getElementById('resendModalDiv');
            var newResendModal = ``;
            modalDiv.innerHTML += newResendModal;
        }

    // css untuk status
        function getStatusColor(status) {
            switch (status.toLowerCase()) {
                case 'pending':
                    return 'p-1 bg-yellow-100 text-yellow-800 border border-yellow-400';
                case 'rejected':
                    return 'p-1 bg-red-100 text-red-800 border border-red-400';
                    break;
                default:
                    return 'p-1 bg-green-100 text-green-800 border border-green-400';
                    break;
            }
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
            var updateUrl = "{{ route('budget-request.update', ':id') }}".replace(':id', budget.id); 
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
                        <form class="space-y-4" action="${updateUrl}" method="POST" id="updateBudgetForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value=""></input>

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
                                                    <input type="text" name="to_department" value="${budget.to_department.department_name}" readonly
                                                    class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                        placeholder="To Department" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="amount" value="${budget.amount}" readonly
                                                    class="w-full p-2 border focus:ring-0 text-center text-body bg-secondary-light" 
                                                        placeholder="Input Number" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="reason" id="reason" value="${budget.reason}" readonly
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
                                <div class="flex items-center justify-between mx-4 mt-4 gap-2">
                                    <div class="">
                                        <button type="button" class="btn btn-success" value="approve" onClick="submitForm(this, ${id})">
                                            Approve
                                        </button>
                                        <button type="button" class="btn btn-danger" value="reject" onClick="submitForm(this, ${id})">
                                            Reject
                                        </button>
                                    </div>
                                    <div class="ml-auto">
                                        <form action="/purchase-request/edit" method="GET">
                                            <button type="button" class="btn btn-primary" onClick="resendEmail('${budget.budget_purchase_no}')">Resend</button>
                                        </form>
                                        <button type="button" class="btn btn-danger" data-modal-hide="editContactModal${id}" onClick="openEditModal(${id})">Exit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            modalDiv.innerHTML += newEditModal;
                
        }

        
        $('#budgetTable tbody').on('click', 'tr', function () {
            let rowIndex = table.row(this).index();
            openEditModal(rowIndex);
        });

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

        // Function check and submit form
        function submitForm(button, divId){
            var form = button.closest('form');
            var actionUrl = form.getAttribute('action');
            var actionDiv = form.querySelector('[name="action"]');
            actionDiv.value = button.value;

            if(button.value === 'reject')
            {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reject form!'
                }).then((result) => {
                    if (result.isConfirmed) {   
                        Swal.fire({
                            title: 'Enter feedback for closing',
                            input: 'textarea',
                            inputPlaceholder: 'Enter feedback here...',
                            inputValidator: (value) => {
                                if (!value.trim()) {
                                    return 'You need to write something!';
                                }
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            cancelButtonText: 'Cancel',
                            cancelButtonColor: '#d33',
                            confirmButtonColor: '#3085d6',
                        }).then((inputResult) => {
                            if (inputResult.isConfirmed) {
                                
                                // Tutup edit modal div
                                openEditModal(divId);

                                // Kirim form
                                const feedback = inputResult.value;
                                $.ajax({
                                    url: actionUrl,
                                    method: 'PUT',
                                    data: $(form).serialize() + '&reviewTextArea=' + encodeURIComponent(feedback), // Ambil semua input form
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
                });
            }
            else if(button.value === 'approve')
            {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve form!'
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
        }

    </script>
    @endpush

</x-app-layout>