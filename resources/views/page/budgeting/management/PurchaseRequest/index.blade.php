<x-app-layout>
    @section('title', 'Purchase Request')
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
                        <li class="breadcrumb-item pr-1">Budgeting</li>
                        <li class="breadcrumb-item active">Purchase Request</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section x-data="{open : false}" class="content">
        <!-- Add User Button -->
        <div @click="open = ! open" class="mb-4 flex justify-end">
            <button type="button"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right">
                Add User
            </button>
        </div>

        <!-- Card Data Display -->
        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">Purchase Request</h1>
            </div>
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="usersTable" class="table table-striped w-full text-left rtl:text-right table-bordered">
                        <thead class="uppercase border-b">
                            <tr>
                                <th class="px-6 py-3 text-lg text-center w-5">#</th>
                                <th class="px-6 py-3 text-lg text-center whitespace-nowrap w-10">NO Budget</th>
                                <th class="px-6 py-3 text-lg text-center">Item Name</th>
                                <th class="px-6 py-3 text-lg text-center w-48">Amount</th>
                                <th class="px-6 py-3 text-lg text-center w-5">Quantity</th>
                                <th class="px-6 py-3 text-lg text-center w-5">status</th>
                                <th class="px-6 py-3 text-lg text-center ">remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)
                            <tr>
                                <td class="px-6 py-4 text-lg text-center w-5">{{ $purchase->id }}</td>
                                <td class="px-6 py-4 text-lg text-center whitespace-nowrap w-10">{{ $purchase->budget_no }}</td>
                                <td class="px-6 py-4 text-lg">{{ $purchase->item_name }}</td>
                                <td class="px-6 py-4 text-lg text-center w-48">Rp. {{ number_format($purchase->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-lg text-center">{{ $purchase->quanitity }}</td>
                                <td class="px-6 py-4 text-lg text-center w-5">{{ $purchase->status }}</td>
                                <td class="px-6 py-4 text-lg w-2/6">{{ $purchase->remarks }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<!-- Modal -->
<div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white text-black p-6 rounded-lg shadow-lg w-2/3 h-fit">
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
                    <h2 class="font-semibold text-base">Department</h2>
                </div>
                <div class="ml-auto">
                    <h1 class="font-bold text-lg">NO PURCHASE:</h1>
                    <span class="font-semibold text-base">nomer purchase</span>
                </div>
            </div>
            <h1 class="font-bold text-lg mt-3">DATE:</h1>
            <span class="font-semibold text-base">tanggal</span>
        </div>

        <!-- Table -->
        <div class="container pt-20">
<form method="POST" action="{{ route('PurchaseRequest.store') }}">
    @csrf
    <table class="table-auto w-full border-collapse" id="testTable">
        <thead>
            <tr>
                <th class="text-center w-fit">ITEM NAME</th>
                <th class="text-center w-48">HARGA (RP)</th>
                <th class="text-center w-28">JML</th>
                <th class="text-center w-48">TOTAL</th>
                <th class="text-center w-56">REMARK</th>
                <th class="text-center w-36">ACTION</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="description[]"
                        class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none" required>
                </td>
                <td>
                    <input type="text" name="price[]"
                        class="w-full p-2 border-none focus:bg-transparent focus:ring-0 focus:border-none price-input"
                        maxlength="10" required>
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
                <td class="text-center">
                    <div class="flex justify-center space-x-1">
                        <button type="button" class="remove-row btn btn-danger text-base px-2 py-1">Remove</button>
                        <button type="button" class="clear-btn btn btn-warning text-base px-2 py-1">Clear</button>
                    </div>
                    </td>
                    </tr>
                    </tbody>
                    </table>
            <div class="my-3 flex flex-col items-end">
                <h3>Grand Total: <span id="grand-total">Rp 0</span></h3>
                <h3>Saldo Wallet: <span id="wallet-balance">Rp 1.000.000</span></h3>
                <h3>Sisa Wallet: <span id="wallet-after">Rp 1.000.000</span></h3>
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
            <button type="submit" class="btn btn-success">Simpan</button>
            <button @click="open = !open" class="btn btn-danger">Exit</button>
        </div>
        </div>
        </form>

        </div>
    </div>
</div>
</section>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let walletBalance = 1000000;

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
            let priceInput = row.querySelector('.price-input');
            let quantityInput = row.querySelector('.quantity-input');
            let totalInput = row.querySelector('.total-input');

            let price = parseRupiah(priceInput.value);
            let quantity = parseFloat(quantityInput.value) || 0;
            let total = price * quantity;

            totalInput.value = toRupiah(total);
            updateGrandTotal();
        }

        function updateGrandTotal() {
            let totalInputs = document.querySelectorAll('.total-input');
            let grandTotal = 0;
            totalInputs.forEach(input => {
                grandTotal += parseRupiah(input.value);
            });
            document.getElementById('grand-total').innerText = toRupiah(grandTotal);

            let remainingBalance = walletBalance - grandTotal;
            document.getElementById('wallet-after').innerText = toRupiah(remainingBalance);
            document.getElementById('save-transaction').disabled = remainingBalance < 0;
        }

        function formatPriceInput(input) {
            input.addEventListener('keydown', function (e) {
                // Hanya izinkan angka, backspace, delete, arrow keys
                if (
                    !(
                        (e.key >= '0' && e.key <= '9') ||
                        ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)
                    )
                ) {
                    e.preventDefault();
                }
            });

            input.addEventListener('blur', function () {
                let numeric = parseRupiah(this.value) || 0;
                this.value = toRupiah(numeric);
                updateTotal(input.closest('tr'));
            });

            input.addEventListener('focus', function () {
                let numeric = parseRupiah(this.value);
                this.value = numeric > 0 ? numeric.toString() : '';
            });

            input.addEventListener('input', function () {
                updateTotal(input.closest('tr'));
            });
        }

        function formatQuantityInput(input, row) {
            input.addEventListener('keydown', function (e) {
                if (
                    !(
                        (e.key >= '0' && e.key <= '9') ||
                        ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)
                    )
                ) {
                    e.preventDefault();
                }
            });

            input.addEventListener('input', function () {
                let val = parseInt(this.value) || 0;
                if (val > 99) {
                    this.value = '99';
                } else if (val < 0) {
                    this.value = '0';
                }
                updateTotal(row);
            });
        }

        function clearRow(row) {
            row.querySelector('input[name="description[]"]').value = '';
            row.querySelector('.price-input').value = toRupiah(0);
            row.querySelector('.quantity-input').value = '';
            row.querySelector('textarea[name="remark[]"]').value = '';
            updateTotal(row);
        }

        function setupRow(row) {
            formatPriceInput(row.querySelector('.price-input'));
            formatQuantityInput(row.querySelector('.quantity-input'), row);

            row.querySelector('.clear-btn').addEventListener('click', function () {
                clearRow(row);
            });

            row.querySelector('.remove-row').addEventListener('click', function () {
                if (document.querySelectorAll('#testTable tbody tr').length > 1) {
                    row.remove();
                    updateGrandTotal();
                }
            });
        }

        // Initialize existing rows
        document.querySelectorAll('#testTable tbody tr').forEach(row => {
            row.querySelector('.price-input').value = toRupiah(0);
            row.querySelector('.total-input').value = toRupiah(0);
            setupRow(row);
        });

        // Add new row
        document.getElementById('add-row').addEventListener('click', function () {
            let tableBody = document.querySelector('#testTable tbody');
            let newRow = tableBody.querySelector('tr').cloneNode(true);

            newRow.querySelector('input[name="description[]"]').value = '';
            newRow.querySelector('.price-input').value = toRupiah(0);
            newRow.querySelector('.quantity-input').value = '';
            newRow.querySelector('textarea[name="remark[]"]').value = '';
            newRow.querySelector('.total-input').value = toRupiah(0);

            tableBody.appendChild(newRow);
            setupRow(newRow);
        });

        // Initialize wallet display
        document.getElementById('wallet-balance').innerText = toRupiah(walletBalance);
        document.getElementById('wallet-after').innerText = toRupiah(walletBalance);
    });
</script>
@endpush

</x-app-layout>
<!-- document.addEventListener('DOMContentLoaded', function () {
    let walletBalance = 1000000; // contoh saldo awal Rp 1.000.000

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
        let priceInput = row.querySelector('input[name="price[]"]');
        let quantityInput = row.querySelector('input[name="quantity[]"]');
        let totalInput = row.querySelector('input[name="total[]"]');

        let price = parseRupiah(priceInput.value);
        let quantity = parseFloat(quantityInput.value) || 0;
        let total = price * quantity;

        totalInput.value = toRupiah(total);
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let totalInputs = document.querySelectorAll('input[name="total[]"]');
        let grandTotal = 0;
        totalInputs.forEach(input => {
            grandTotal += parseRupiah(input.value);
        });
        document.getElementById('grand-total').innerText = toRupiah(grandTotal);

        updateWalletBalance(grandTotal);
    }

    function updateWalletBalance(grandTotal) {
        let sisaWallet = walletBalance - grandTotal;
        let sisaWalletElement = document.getElementById('sisa-wallet');
        let submitButton = document.getElementById('submit-btn');

        sisaWalletElement.innerText = toRupiah(sisaWallet);

        if (sisaWallet < 0) {
            sisaWalletElement.style.color = 'red';
            submitButton.disabled = true;
        } else {
            sisaWalletElement.style.color = 'black';
            submitButton.disabled = false;
        }
    }

    function formatPriceInput(input) {
        input.addEventListener('input', function () {
            let original = input.value;
            let numeric = parseRupiah(original);

            input.value = toRupiah(numeric);

            let row = input.closest('tr');
            updateTotal(row);
        });

        input.addEventListener('focus', function () {
            if (input.value.trim() === '') {
                input.value = toRupiah(0);
            }
        });

        input.addEventListener('blur', function () {
            if (input.value.trim() === '' || parseRupiah(input.value) === 0) {
                input.value = toRupiah(0);
            }
        });
    }

    function formatQuantityInput(input) {
        input.addEventListener('input', function () {
            let row = input.closest('tr');
            updateTotal(row);
        });
    }

    function addEventListeners(row) {
        let priceInput = row.querySelector('input[name="price[]"]');
        let quantityInput = row.querySelector('input[name="quantity[]"]');

        formatPriceInput(priceInput);
        formatQuantityInput(quantityInput);
    }

    let rows = document.querySelectorAll('#testTable tbody tr');
    rows.forEach(row => {
        let priceInput = row.querySelector('input[name="price[]"]');
        let totalInput = row.querySelector('input[name="total[]"]');

        priceInput.value = toRupiah(0);
        totalInput.value = toRupiah(0);

        addEventListeners(row);
    });

    document.getElementById('add-row')?.addEventListener('click', function () {
        let tableBody = document.querySelector('#testTable tbody');
        let newRow = tableBody.rows[0].cloneNode(true);

        Array.from(newRow.querySelectorAll('input')).forEach(input => input.value = '');
        Array.from(newRow.querySelectorAll('textarea')).forEach(textarea => textarea.value = '');

        let priceInput = newRow.querySelector('input[name="price[]"]');
        let totalInput = newRow.querySelector('input[name="total[]"]');

        priceInput.value = toRupiah(0);
        totalInput.value = toRupiah(0);

        tableBody.appendChild(newRow);
        addEventListeners(newRow);
    });

    document.querySelector('#testTable').addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-row')) {
            let row = event.target.closest('tr');
            let tableBody = document.querySelector('#testTable tbody');

            if (tableBody.rows.length > 1) {
                row.remove();
                updateGrandTotal();
            }
        }
    });

    document.getElementById('clear-btn').addEventListener('click', clearInputs);

    // Inisialisasi awal sisa wallet
    updateGrandTotal();
}); -->