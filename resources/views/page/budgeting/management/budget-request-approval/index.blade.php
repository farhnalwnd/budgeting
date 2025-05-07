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
    <div id="editModalDiv">
        <div id="modalBackground" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40 hidden"></div>
    </div>

    @push('scripts')
    <script>
        var budgets = null;
        document.addEventListener('DOMContentLoaded', function() {


            // Init datatable
            var table = $('#budgetTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: '{{ route('get.budget-request.approval.list') }}',
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
                                        <button type="button" value="approve" onClick="submitForm(this)"
                                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            Approve
                                        </button>
                                        <button type="button" value="approve with review" onClick="submitForm(this)"
                                            class="w-full text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                            Approve with review
                                        </button>
                                        <button type="button" value="reject" onClick="submitForm(this)"
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

        function submitForm(button){
            var form = button.closest('form');
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
                            form.submit();
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
                        form.submit();
                    }
                });
                
            }

            console.log(button, form, actionDiv, review, label);  
        }

    </script>
    @endpush

</x-app-layout>