<x-app-layout>
    @section('title', 'Purchase Request')
@push('css')
<style>
    #testTable tr,
    #testTable td {
        border: 3px solid black;
    }
    .center {
        text-align: center;
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
                        <li class="breadcrumb-item pr-1">Budgeting</li>
                        <li class="breadcrumb-item active">Purchase Request</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section x-data="{open : false}" class="content">
        <div class="mb-4 flex px-4">
            <div id="deptBalance"
                class="w-fit h-max shadow-md rounded-md shadow-neutral-500 bg-gradient-to-t from-cyan-500 to-blue-500  text-lg p-3 hover:scale-105 group">
                <h1 class="group-hover:cursor-pointer">Rp. {{number_format($department->balance , 0, ',',
                    '.')}}</h1>
            </div>
            <div @click="open = ! open" class="ml-auto">
                <button type="button"
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right">
                    Add Purchase
                    </button>
                    </div>
        </div>
        <!-- Add User Button -->

        <!-- Card Data Display -->
        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">Purchase Request</h1>
                <div>
                    <select id="yearFilter" name="year">
                        <option value="">-- Pilih Tahun --</option>
                        @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <table id="usersTable" class="table table-bordered w-full">
                    <thead>
                        <tr>
                            <th class="w-fit">#</th>
                            <th class="text-center w-2/12">PO Number</th>
                            <th class="text-center w-3/12">Department</th>
                            <th class="text-center w-2/12">Grand Total</th>
                            <th class="text-center w-2/12">Actual Amount</th>
                            <th class="text-center w-2/12">Status</th>
                            <th class="text-center w-fit">Details</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- detail modal -->
        <div id="detailModalDiv">
            <div id="modalBackground" class="bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40 hidden">
            </div>
        </div>

        
        <!-- Modal create-->
        <div x-show="open" x-on:keydown.escape.window="open = false" x-transition.duration.400ms
            class="fixed inset-0 z-[999] flex items-center justify-center bg-black bg-opacity-50">
            <div class="absolute bg-white text-black p-6 rounded-lg shadow-lg w-2/3 max-h-[800px] overflow-y-scroll"
                @click.away="open = false">
                <!-- Header -->
                <div class="flex justify-start">
                    <div class="flex items-center">
                        <h1 class="text-6xl font-bold text-yellow-700 font-mono">PURCHASE</h1>
                    </div>
                    <img src="/sinarlogo.png" alt="logo" class="w-72 h-32 ml-auto">
                </div>
                <hr class="my-10 border-t-2 rounded-md border-slate-900 opacity-90">
            
                <!-- Keterangan -->
                <div>
                    <div class="flex items-center mt-2">
                        <div>
                            <h1 class="font-bold text-lg">ON: </h1>
                            <h2 class="font-semibold text-base">{{$user->department->department_name}}</h2>
                        </div>
                        <div class="ml-auto">
                            <h1 class="font-bold text-lg">Budget No:</h1>
                            <span class="font-semibold text-base">{{$budgetNo}}</span>
                        </div>
                    </div>
                    <h1 class="font-bold text-lg mt-3">DATE:</h1>
                    <span class="font-semibold text-base">{{$currentDate}}</span>
                </div>
            
                <!-- Table -->
                <div class="container mt-10">
                    <form id="purchase-form" x-on:keydown.enter.window="$el.submit()" method="POST"
                        action="{{ route('purchase-request.store') }}">
                <div x-data="{ scrolled: false }" @scroll="scrolled = $el.scrollTop > 0 || false"
                    class="overflow-y-auto max-h-[250px] mt-6">
                    @csrf
                    <table class="table-auto w-full border-collapse" id="testTable">
                        <thead :class="scrolled ? 'bg-white shadow-md border-none' : ''" class="sticky top-0 z-10">
                            <tr>
                                <th class="text-center w-fit">ITEM NAME</th>
                                <th class="text-center w-48">HARGA (RP)</th>
                                <th class="text-center w-28">JML</th>
                                <th class="text-center w-48">TOTAL</th>
                                <th class="text-center w-56">REMARK</th>
                                <th class="text-center w-36">ACTION</th>
                            </tr>
                            </thead>
                            <tbody class="max-h-[50vh] overflow-y-auto">
                                <tr>
                                    <td><input type="text" name="description[]"
                                    
                                        class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none" required>
                                    </td>
                                    <td>
                                        <input type="text" name="price[]"
                                            class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none price-input"
                                            maxlength="17" required>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]"
                                            class="w-full p-1 border-none focus:bg-transparent focus:ring-0 focus:border-none quantity-input"
                                            min="0" maxlength="2" required>
                                    </td>
                                    <td>
                                        <input type="text" name="total[]"
                                            class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none total-input"
                                            readonly>
                                    </td>
                                    <td><textarea name="remark[]"
                                            class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none"></textarea>
                                    </td>
                                    <input type="hidden" name="grand_total" id="grand-total-input">
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
                            <h3 class="text-lg font-mono font-semibold">Saldo Wallet: <span id="wallet-balance"></span></h3>
                            <h3 class="text-lg font-mono font-semibold">Sisa Wallet : <span id="wallet-after"></span></h3>
                        </div>
                    </div>
                    <!--! table budget kurang -->
                    <div id="request-budget-form" class="hidden mt-4 border border-gray-300 p-4 rounded-lg bg-gray-50">
                        <h2 class="text-lg font-semibold mb-2">Budget Request Form</h2>
                        <table class="w-full table-auto border-collapse">
                            <tr>
                                <td class="font-medium py-2 pr-4">From Department:</td>
                                <td><input type="text" name="from_department" id="from-department" class="w-full border rounded p-2"
                                        readonly value="{{ $department->department_name }}"></td>
                                <input type="hidden" name="from_department" id="from-department" class="w-full border rounded p-2" readonly
                                    value="{{ $department->id }}">
                            </tr>
                            <tr>
                                <td class="font-medium py-2 pr-4">To Department:</td>
                                <td>
                                    <select name="to_department" id="to-department" class="w-full border rounded p-2">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium py-2 pr-4">Amount:</td>
                                <td><input type="text" name="amount" id="request-amount" class="w-full border rounded p-2" readonly></td>
                            </tr>
                            <tr>
                                <td class="font-medium py-2 pr-4">Reason:</td>
                                <td><textarea name="reason" id="request-reason" rows="3" class="w-full border rounded p-2"
                                        placeholder="Explain the purpose of the request..."></textarea></td>
                            </tr>
                        </table>
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
                    <button @click="open = false" type="submit" class="btn btn-success">Simpan</button>
                    <button @click="open = !open" type="button" class="btn btn-danger">Exit</button>
                </div>
                </div>
                </form>
            
                </div>
            </div>
        </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var purchases = null;
    let walletBalance = {{ $department->balance }};

    // Fungsi-fungsi dideklarasikan di luar DOMContentLoaded
    function openModal(purchaseNo) {
        var modal = document.getElementById(`detail-${purchaseNo}`);
        var modalBackground = document.getElementById('modalBackground');
        modalBackground.classList.toggle('hidden');
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
            return;
        }

        var modalDiv = document.getElementById('detailModalDiv');
        var purchase = purchases.find(b => b.purchase_no === purchaseNo);
        console.log(purchase);

        var tableRows = '';
        purchase.detail.forEach(item => {
            tableRows += `
                <tr>
                    <td class="border-2 border-gray-400 p-3 text-center">${item.item_name}</td>
                    <td class="border-2 border-gray-400 p-3 text-center">${toRupiah(item.amount)}</td>
                    <td class="border-2 border-gray-400 p-3 text-center">${item.quantity}</td>
                    <td class="border-2 border-gray-400 p-3 text-center">${toRupiah(item.total_amount)}</td>
                    <td class="border-2 border-gray-400 p-3 text-center">${item.remarks || '-'}</td>
                </tr>
            `;
        });

        var newEditModal = `
        <div id="detail-${purchaseNo}" class="text-black fixed inset-0 z-50 bg-black bg-opacity-10 flex items-center justify-center">
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
                        <h1 class="font-bold text-lg">ON:</h1>
                        <h2 class="font-semibold text-base">${purchase.department.department_name}</h2>
                    </div>
                    <div class="pl-7">
                        <h1 class="font-bold text-lg">Budget No:</h1>
                        <span class="font-semibold text-base">${purchase.purchase_no}</span>
                    </div>
                    <div class="col-span-4">
                        <h1 class="font-bold text-lg mb-1">DATE:</h1>
                        <span class="font-semibold text-base">${formatTanggalShort(purchase.updated_at)}</span>
                    </div>
                    <div class="pl-7">
                        <h1 class="font-bold text-lg mb-1">Status:</h1>
                        <span class="font-semibold text-lg rounded-md p-1 uppercase text-emerald-700 border-2 border-emerald-600 border-opacity-50">${purchase.status}</span>
                    </div>
                </div>

                <!-- * table -->
                <div class="container pt-10">
                    <table class="table-auto w-full border-collapse">
                        <thead class="sticky top-0 z-10">
                            <tr>
                                <th class="border-2 p-2 border-gray-800 text-center w-2/6">ITEM NAME</th>
                                <th class="border-2 p-2 border-gray-800 text-center w-1/6">HARGA (RP)</th>
                                <th class="border-2 p-2 border-gray-800 text-center w-1/6">JML</th>
                                <th class="border-2 p-2 border-gray-800 text-center w-1/6">TOTAL</th>
                                <th class="border-2 p-2 border-gray-800 text-center w-1/6">REMARK</th>
                            </tr>
                        </thead>
                        <tbody class="overflow-y-auto">
                            ${tableRows}
                        </tbody>
                    </table>

                    <!--* Catatan dan Grand Total -->
                    <div class="grid grid-cols-4 gap-3 mt-20 text-left">
                        <div class="col-span-3">
                            <p class="text-lg font-semibold">PO number : <span class="text-base font-medium">${purchase.PO || '-'}</span></p>
                            <p class="text-lg font-semibold">category : <span class="text-base font-medium">${purchase.category?.name || 'category belum dipilih'} </span></p>
                        </div>
                        <div>
                            <div class="flex justify-between">
                                <p class="uppercase font-semibold text-lg px-5 text-right">grand total :</p>
                                <span class="text-right">Rp. ${toRupiah(purchase.grand_total)}</span>
                            </div>
                            <div class="flex justify-between">
                                <p class="uppercase font-semibold text-lg px-5 text-right">actual amount :</p>
                                <span class="text-right">${toRupiah(purchase.actual_amount || '0')}</span>
                            </div>
                        </div>
                        <div class="">
                            <p>note:</p>
                            ${purchase.status === 'approved' ? '<p>-</p>' : purchase.status === 'pending' ? '<p>peminjaman dana belum mendapatkan respon</p>' : `<p> ${purchase.budgetRequest?.feedback || 'peminjaman ditolak'}</p>`}
                        </div>
                    </div>
    
                <!--* Tombol -->
                <div class="flex items-center justify-end mx-4 mt-4 gap-2">
                    <div class="ml-auto">
                        <form action="/purchase-request/${purchase.id}/edit" method="GET">
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                    </div>
                    <div class="">
                        <button type="button" class="btn btn-danger" onclick="openModal('${purchaseNo}')">Exit</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
        `;
        modalDiv.innerHTML += newEditModal;
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

        const remainingBalance = walletBalance - grandTotal;

        document.getElementById('grand-total').innerText = toRupiah(grandTotal);
        document.getElementById('wallet-after').innerText = toRupiah(remainingBalance);

        document.getElementById('grand-total-input').value = grandTotal;

        const saveBtn = document.getElementById('save-transaction');
        if (saveBtn) {
            saveBtn.disabled = remainingBalance < 0;
        }

        const walletAfterElem = document.getElementById('wallet-after');
        walletAfterElem.classList.toggle('text-red-600', remainingBalance < 0);
        walletAfterElem.classList.toggle('text-black', remainingBalance >= 0);

        requestBudget(grandTotal);
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
        row.querySelector('input[name="description[]"]').value = '';
        row.querySelector('.price-input').value = toRupiah(0);
        row.querySelector('.quantity-input').value = '';
        row.querySelector('textarea[name="remark[]"]').value = '';
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

    function requestBudget(grandTotal) {
        const requestForm = document.getElementById('request-budget-form');
        const requestAmount = document.getElementById('request-amount');
        if (!requestForm || !requestAmount) return;

        const overAmount = grandTotal - walletBalance;

        if (grandTotal > walletBalance) {
            requestForm.classList.remove('hidden');
            requestAmount.value = toRupiah(overAmount);
        } else {
            requestForm.classList.add('hidden');
            requestAmount.value = '';
        }
        console.log("Grand Total:", grandTotal);
        console.log("Wallet Balance:", walletBalance);
        console.log("Over Amount:", overAmount);
    }

    // Inisialisasi yang harus dijalankan setelah DOM siap
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('purchase-form');
        const reqbud = document.getElementById('request-budget-form');
        const tableBody = document.querySelector('#testTable tbody');
        const balanceElement = document.getElementById('deptBalance')?.querySelector('h1');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const csrfToken = document.querySelector('input[name="_token"]').value;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();
                handleResponse(result);
            } catch (error) {
                showError('Gagal', error.message);
                console.error('Error:', error);
            }
        });

        function handleResponse(result) {
            if (result.success) {
                showSuccessOrPendingAlert(result);
                updateUIAfterSuccess(result);
            } else {
                showError('Gagal', result.message);
            }
        }

        function showSuccessOrPendingAlert(result) {
            Swal.fire({
                icon: result.pending ? 'info' : 'success',
                title: result.pending ? 'Budget Pending' : 'Berhasil melakukan purchase',
                text: result.message,
                timer: result.pending ? 2500 : 2000,
                showConfirmButton: !result.pending
            });
        }

        function updateUIAfterSuccess(result) {
            if (balanceElement) {
                balanceElement.innerText = toRupiah(result.new_balance);
            }

            // Reload table and reset form
            table.ajax.reload(null, false);
            form.reset();
            reqbud.classList.add('hidden');

            // Hapus semua baris kecuali baris pertama
            const rows = tableBody.querySelectorAll('tr');
            rows.forEach((row, index) => {
                if (index > 0) row.remove();
            });
        }

        function showError(title, message) {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message
            });
        }
        
        
        var table = $('#usersTable').DataTable({
            dom: 'Bfrtip',
            autoWidth: false,
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            ajax: {
                url: "{{ route('purchase.data') }}",
                type: 'GET',
                data: function (d) {
                    d.year = $('#yearFilter').val();
                },
                dataSrc: function (response) {
                    purchases = response;
                    //console.log('data masuk :', purchases);
                    return response;
                }
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'purchase_no', name: 'purchase_no', className: 'center' },
                { data: 'department.department_name', name: 'department', className: 'center' },
                { data: 'grand_total', name: 'grand_total', className: 'center',
                render: function (data, type, row) {
                    return toRupiah(data);}
                },
                { data: 'actual_amount', name: 'actual_amount', className: 'center',
                render: function (data, type, row) {
                    return toRupiah(data);}
                },
                { data: 'status', name: 'status', className: 'center' },
                {
                    data: null,
                    name: 'aksi',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                        <button class="btn btn-info" onclick="openModal('${row.purchase_no}')">Detail</button>
                        `;
                    }
                }
            ]
        });

        $('#yearFilter').on('change', function () {
            table.ajax.reload(); // reload datatable dengan parameter tahun baru
            });

        // Setup event listeners on existing rows of your table if any
        document.querySelectorAll('#testTable tbody tr').forEach(row => {
            setupRow(row);
        });

        // Tombol tambah baris
        document.getElementById('add-row')?.addEventListener('click', function () {
            const tbody = document.querySelector('#testTable tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <tr>
                <td><input type="text" name="description[]"
                    class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none" required>
                </td>
                <td>
                    <input type="text" name="price[]"
                        class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none price-input"
                        maxlength="17" required>
                </td>
                <td>
                    <input type="number" name="quantity[]"
                        class="w-full p-1 border-none focus:bg-transparent focus:ring-0 focus:border-none quantity-input"
                        min="0" maxlength="2" required>
                </td>
                <td>
                    <input type="text" name="total[]"
                        class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none total-input"
                        readonly>
                </td>
                <td><textarea name="remark[]"
                        class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none"></textarea>
                </td>
                <input type="hidden" name="grand_total" id="grand-total-input">
                <td class="text-center">
                    <div class="flex justify-center space-x-1">
                        <button type="button" class="remove-row btn btn-danger text-base px-2 py-1">Remove</button>
                        <button type="button" class="clear-btn btn btn-warning text-base px-2 py-1">Clear</button>
                    </div>
                </td>
            </tr>
            `;
            tbody.appendChild(newRow);
            setupRow(newRow);
            updateGrandTotal();
        });

        // Set saldo awal
        document.getElementById('wallet-balance').innerText = toRupiah(walletBalance);
        document.getElementById('wallet-after').innerText = toRupiah(walletBalance);
        updateGrandTotal();
    });
</script>
@endpush

</x-app-layout>
