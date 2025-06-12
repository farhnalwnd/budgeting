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
                    { data: 'to_department.department_name', name: 'to_department' },
                    { data: 'amount', name: 'amount' },
                    { data: 'budget_purchase_no', name: 'purchase_no' },
                    { data: 'reason', name: 'reason' },
                    { data: 'status', name: 'status' },
                    { data: null, name: 'action', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            var id = row.id;
                            var deleteUrl = "{{ route('budget-request.destroy', ':id') }}".replace(':id', id); 
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
        });

        $('#budgetTable tbody').on('click', 'tr', function () {
                if (!$(event.target).closest('a, button, i').length) {
                    var data = table.row(this).data();
                    if (data && data.budget_purchase_no) {
                        openResendModal(data);
                    }
                }
            });

            function openResendModal(data) {
                var modal = document.getElementById(`detail-${data}`);
                var modalBackground = document.getElementById('modalBg');
                modalBackground.classList.toggle('hidden');
                if (modal) {
                    console.log(modal);
                    modal.classList.toggle('hidden');
                    modal.classList.toggle('flex');
                    return;
                }

                var modalDiv = document.getElementById('resendModalDiv');
                console.log('tes ini datanya', data);

                var newResendModal = `
            <div id="detail-${data}" class="text-black fixed inset-0 z-50 bg-black bg-opacity-10 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg w-2/3">
                    <!-- Header -->
                    <div class="flex justify-start">
                        <div class="flex items-center">
                            <h1 class="text-6xl font-bold text-yellow-700 font-mono">PURCHASE</h1>
                        </div>
                        <img src="/sinarlogo.png" alt="logo" class="w-72 h-32 ml-auto">
                    </div>
                    <hr class="my-10 border-t-2 rounded-md border-slate-900 opacity-90">

                    <!-- Keterangan -->
                    <div class="grid grid-cols-5 gap-2 text-left py-3">
                        <div class="col-span-4">
                            <h1 class="font-bold text-lg">From Department:</h1>
                            <h2 class="font-semibold text-base">${data.from_department.department_name}</h2>
                        </div>
                        <div class="pl-7">
                            <h1 class="font-bold text-lg">To Department:</h1>
                            <span class="font-semibold text-base">${data.to_department.department_name}</span>
                        </div>
                        <div class="col-span-4">
                            <h1 class="font-bold text-lg mb-1">DATE:</h1>
                            <span class="font-semibold text-base">${formatTanggalShort(data.updated_at)}</span>
                        </div>
                        <div class="pl-7">
                            <h1 class="font-bold text-lg mb-1">Status:</h1>
                            <span
                                class="font-semibold text-lg rounded-md uppercase border-2 ${getStatusColor(data.status)} border-opacity-50">${data.status}</span>
                        </div>
                    </div>

                    <!-- * table -->
                    <div class="container pt-10">
                        <table class="table-auto w-full border-collapse">
                            <thead class="sticky top-0 z-10">
                                <tr>
                                    <th class="border-2 p-2 border-gray-800 text-center w-4/12">BUDGET REQUEST NO</th>
                                    <th class="border-2 p-2 border-gray-800 text-center w-1/12">BUDGET PURCHASE NO</th>
                                    <th class="border-2 p-2 border-gray-800 text-center w-2/12">AMOUNT</th>
                                    <th class="border-2 p-2 border-gray-800 text-center w-1/12">STATUS</th>
                                    <th class="border-2 p-2 border-gray-800 text-center w-2/12">REASON</th>
                                    <th class="border-2 p-2 border-gray-800 text-center w-2/12">FEEDBACK</th>
                                </tr>
                            </thead>
                            <tbody class="overflow-y-auto">
                                <tr>
                                    <td class="border-2 border-gray-400 p-3 text-center">${data.budget_req_no}</td>
                                    <td class="border-2 border-gray-400 p-3 text-center">${data.budget_purchase_no}</td>
                                    <td class="border-2 border-gray-400 p-3 text-center">${toRupiah(data.amount)}</td>
                                    <td class="border-2 border-gray-400 p-3 text-center">${data.status}</td>
                                    <td class="border-2 border-gray-400 p-3 text-center">${data.reason}</td>
                                    <td class="border-2 border-gray-400 p-3 text-center">${data.feedback || '-'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!--* Tombol -->
                    <div class="flex items-center justify-end mx-4 mt-4 gap-2">
                        <div class="ml-auto">
                            <form action="/purchase-request/edit" method="GET">
                                <button type="button" class="btn btn-primary" onClick="resendEmail('${data.budget_purchase_no}')">Resend</button>
                            </form>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-danger"
                                onclick="closeResendModal('${data}')">Exit</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
                modalDiv.innerHTML += newResendModal;
            }

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

        function formatTanggalShort(dateStr) {
            const options = { day: 'numeric', month: 'short', year: '2-digit' };
            return new Date(dateStr).toLocaleDateString('en-GB', options);
        }

        function toRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
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
                    <div class="relative p-4 w-full max-w-4xl max-h-full">
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
                                        <label for="No Budget"
                                            class="block mb-2 text-xl font-medium text-gray-900 dark:text-white">No Budget</label>
                                        <input type="text" name="no" readonly value="${budget.budget_req_no}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="No Budget" required>
                                    </div>
                                    <div class="grid grid-cols-2 gap-x-4">
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
                                    <div class="flex gap-x-4">
                                        <input type="hidden" name="action" value=""></input>
                                        <button type="button" value="approve" onClick="submitForm(this, ${id})"
                                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            Approve
                                        </button>
                                        <button type="button" value="approve with review" onClick="submitForm(this, ${id})"
                                            class="w-full text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                            Approve with review
                                        </button>
                                        <button type="button" value="reject" onClick="submitForm(this, ${id})"
                                            class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                            Reject
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <label for="reviewTextArea" class="hidden block mb-2 text-xl font-medium text-danger text-center">Tolong isi review terlebih dahulu.</label>
                                        <textarea class="form-control w-full" placeholder="Leave a review here" name="reviewTextArea"></textarea>
                                    </div>
                                </form>
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
            var review = form.querySelector('[name="reviewTextArea"]');
            var label = form.querySelector('label[for="reviewTextArea"]');
            actionDiv.value = button.value;

            if(button.value === 'approve with review' || button.value === 'reject')
            {
                if(review.value.trim() === "")
                {
                    label.classList.remove('hidden');
                }
                else
                {
                    label.classList.add('hidden');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, submit form!'
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
            else if(button.value === 'approve')
            {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit form!'
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