<x-app-layout>
    @section('title')
        Requisition Maintenance
    @endsection
    <div class="content-header">
        <div class="flex items-center justify-between">

            <h4 class="page-title text-2xl font-medium">Requisition Maintenance</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page"> Requsition</li>
                        <li class="breadcrumb-item active" aria-current="page"> Requsition Maintenance</li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>



    <!-- Main content -->
    <section class="content">
        <!-- Step wizard -->
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body wizard-content">
                <form id="form" name="form" method="POST" action="{{ route('store') }}"
                    class="validation-wizard wizard-circle">
                    @csrf
                    <!-- Step 1 -->
                    <h6>PR Information</h6>
                    <section>
                        <div class="grid xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-x-4">
                            <div class="mb-1">
                                @csrf
                                <label for="prNumber" class="block mb-2 text-md font-medium">Req Number: <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="prNumberInput" name="prNumber" readonly
                                    class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                    placeholder="Tekan Enter untuk mendapatkan Nomor PR">
                            </div>

                            <div class="form-group relative mb-1">
                                <label for="supplier" class="block mb-2 text-md font-medium">Supplier:</label>
                                <div class="relative">
                                    <input type="text" name="rqmVend"
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-10 p-2.5 "
                                        id="supplier">
                                    <a data-modal-target="large-modal" data-modal-toggle="large-modal"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                                        <i class="fa fa-search text-gray-500 text-2xl"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-1" style="display: none;">
                                <label for="enterby" class="block mb-2 text-md font-medium ">Users:</label>
                                <input type="text" value="{{ Auth::user()->name }}" name="enterby"
                                    class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    id="enterby" readonly>
                            </div>
                            <div class="mb-1">
                                <input type="hidden" value="1000" id="site" name="rqmSite">
                            </div>
                            <div class="mb-1">
                                <input type="hidden" value="1000" id="site" name="rqmShip">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-x-4">
                            <!-- Kolom Kiri -->
                            <div>
                                <div class="mb-1">
                                    <label for="requestdate" class="block mb-2 text-md font-medium">Request
                                        Date: <span class="text-danger">*</span></label>
                                    <input type="date" id="requestdate" value="{{ now()->format('Y-m-d') }}"
                                        name="rqmReqDate" readonly
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                </div>
                                <div class="mb-1">
                                    <label for="needdate" class="block mb-2 text-md font-medium">Need Date: <span
                                            class="text-danger">*</span></label>
                                    <input type="date" id="needdate" value="{{ now()->format('Y-m-d') }}"
                                        name="rqmNeedDate"
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                </div>
                                <div class="mb-1">
                                    <label for="duedate" class="block mb-2 text-md font-medium">Due Date: <span
                                            class="text-danger">*</span></label>
                                    <input type="date" id="duedate" value="{{ now()->format('Y-m-d') }}"
                                        name="rqmDueDate"
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                </div>
                                <div class="mb-1">
                                    <input type="hidden" id="enterby" value="mfg" name="rqmRqbyUserid"
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                </div>
                                <div class="mb-1">
                                    <input type="hidden" id="requestby" value="mfg"
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div class="form-group relative mb-1">
                                    <label for="costcenter" class="block mb-2 text-md font-medium">Cost
                                        Center: <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <input type="text" name="rqmCc"
                                            class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-10 p-2.5 "
                                            id="costcenter">
                                        <a data-modal-target="large-modal-cost" data-modal-toggle="large-modal-cost"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                                            <i class="fa fa-search text-gray-500 text-2xl"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="form-group relative mb-1">
                                    <label for="enduser" class="block mb-2 text-md font-medium">End User: <span
                                            class="text-danger">*</span></label>
                                    <div class="relative">
                                        <input type="text" name="rqmEndUserid"
                                            class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-10 p-2.5 "
                                            id="enduser">
                                        <a data-modal-target="large-modal-enduser"
                                            data-modal-toggle="large-modal-enduser"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                                            <i class="fa fa-search text-gray-500 text-2xl"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                            <!-- Kolom Kanan -->
                            <div>
                                <div class="mb-1">
                                    <label for="reason" class="block mb-2 text-md font-medium">Reason: </label>
                                    <input type="text" id="reason" name="rqmReason"
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div class="mb-1">
                                    <label for="remarks" class="block mb-2 text-md font-medium">Remarks: <span
                                        class="text-danger">*</span></label>
                                    <input type="text" id="remarks" name="rqmRmks"
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>

                                <div class="mb-1">
                                    <label for="currency" class="block mb-2 text-md font-medium">Currency: <span
                                        class="text-danger">*</span></label>
                                    <select id="currency" name="rqmCurr"
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required">
                                        @foreach ($currency as $curr)
                                            <option value="{{ $curr->code }}">{{ $curr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="messageContainer" class="mt-2"></div>
                                <div class="mb-1">
                                    <input type="hidden" value="US" id="lang" name="rqmLang">
                                </div>

                                <div class="mb-1">
                                    <input type="hidden" value="R" id="emailOption" name="emailOptEntry">
                                </div>
                                <div class="mb-1">
                                    <input type="hidden" id="entity" value="SMII" name="rqmEntity">
                                </div>

                                <div class="mb-1">
                                    <label for="appstatus" class="block mb-2 text-md font-medium">Approval
                                        Status:</label>
                                    <input type="text" id="appstatus" name="rqmAprvStat"
                                        class="bg-gray-200 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        readonly>
                                </div>
                                <div class="mt-4 flex items-center">
                                    <input type="checkbox" id="directCheckbox" class="rounded" name="rqmDirect">
                                    <label for="directCheckbox" class="text-md font-medium ml-2 text-black">Direct
                                        Material</label>
                                    <input type="hidden" id="directCheckboxHidden" name="rqmDirect" value="false">
                                </div>
                                <div class="mt-4 flex items-center">
                                    <input type="checkbox" id="nonPOCheckbox" class="rounded" name="rqm__log01">
                                    <label for="nonPOCheckbox" class="text-md font-medium ml-2 text-black">PR Non
                                        PO</label>
                                    <input type="hidden" id="nonPOCheckboxHidden" name="rqm__log01" value="false">

                                </div>
                            </div>
                    </section>
                    <!-- Step 2 -->
                    <h6 class="text-md font-semibold mb-4">Product Detail</h6>
                    <section>
                        <div id="lineItemsContainer">
                            <div class="text-md p-5 font-bold">Line 1</div>

                            <div class="lineItem" data-row-id="1">

                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 ">
                                    <div class="form-group relative mb-1">
                                        <label for="itemnumber" class="block text-md font-medium">Item
                                            Number: <span class="text-danger">*</span></label>
                                        <div class="relative">
                                            <input type="text" id="itemnumber" name="rqdPart[]"
                                                class="itemnumber bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 itemnumber ">
                                            <a data-modal-target="modal-item"
                                                class="modal-trigger absolute inset-y-0 right-0 flex items-center pr-3 top-1/2 transform -translate-y-1/2 cursor-pointer">
                                                <i class="fa fa-search text-gray-500 text-2xl"></i>
                                            </a>
                                        </div>
                                        <input type="hidden" class="rqdLine" id="rqdLine" name="rqdLine[]">
                                        <input type="hidden" class="rqdDesc" id="rqdDesc" name="rqdDesc[]">
                                    </div>
                                    <div class="form-group relative mb-1">
                                        <label for="supplieritem" class="block text-md font-medium">Supplier:</label>
                                        <div class="relative">
                                            <input type="text" id="supplieritem" name="rqdVend[]"
                                                class="supplieritem bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                            <a data-modal-target="modal-supplieritem"
                                                class="
                                                    modal-trigger absolute inset-y-0 right-0 flex items-center pr-3
                                                    top-1/2 transform -translate-y-1/2 cursor-pointer">
                                                <i class="fa fa-search text-gray-500 text-2xl"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reqQty" class="block text-md font-medium">Req Qty: <span
                                                class="text-danger">*</span></label>
                                        <input type="number" id="reqQty" value="" name="rqdReqQty[]"
                                            placeholder="0"
                                            class="bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 reqQty required">
                                    </div>
                                    <div class="form-group">
                                        <label for="um" class="block text-md font-medium">UM:</label>
                                        <input type="text" id="rqdUm" name="rqdUm[]"
                                            class="rqdUm bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                    <div class="form-group">
                                        <label for="unitCost" class="block text-md font-medium">Unit Cost: <span
                                                class="text-danger">*</span></label>
                                        <input type="number" id="unitCost" name="rqdPurCost[]" placeholder="0"
                                            class="bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 unitCost required">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" id="discount" value="0" name="rqdDiscPct[]"
                                            class="bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>

                                </div>

                                <div class="grid grid-cols-3 gap-4 mt-6 ">
                                    <div class="form-group relative mb-1">
                                        <label for="dueDate" class="block text-md font-medium">Due Date: <span
                                                class="text-danger">*</span></label>
                                        <input type="date" id="dueDate" name="rqdDueDate[]"
                                            value="{{ old('rqdDueDate.0', now()->format('Y-m-d')) }}"
                                            class="rqdDueDate bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required">

                                        <label for="needDate" class="block text-md font-medium mt-4">Need
                                            Date: <span class="text-danger">*</span></label>
                                        <input type="date" id="needDate" name="rqdNeedDate[]"
                                            value="{{ old('rqdNeedDate.0', now()->format('Y-m-d')) }}"
                                            class="rqdNeedDate bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required">

                                        <div class="form-group relative mb-1">
                                            <label for="purracct" class="block text-md font-medium">Purr
                                                Acct: <span class="text-danger">*</span></label>
                                            <div class="relative">
                                                <input type="text" id="purracct" name="rqdAcct[]"
                                                    value="{{ old('rqdAcct.0', '5516') }}"
                                                    class="purracct bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required">
                                                <a data-modal-target="modal-purracct"
                                                    class="rqdAcct modal-trigger absolute inset-y-0 right-0 flex items-center pr-3 top-1/2 transform -translate-y-1/2 cursor-pointer">
                                                    <i class="fa fa-search text-gray-500 text-2xl"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group relative mb-1">
                                        <label for="stockUMQty" class="block text-md font-medium">Stock UM
                                            Qty:</label>
                                        <input type="number" id="stockUMQty" value="0" name="rqdUmConv[]"
                                            readonly
                                            class="bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 stockUMQty">
                                        <label for="maxUnitCost" class="block text-md font-medium mt-4">Maximum
                                            Unit
                                            Cost:</label>
                                        <input type="number" id="maxUnitCost" value="0" name="rqdMaxCost[]"
                                            class="bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 maxUnitCost">
                                        <div class="target-insert-point">

                                        </div>
                                        <div class="mt-4 flex items-center">
                                            <input type="checkbox" id="commentsCheckbox-row-1"
                                                class="rounded commentsCheckbox" name="lineCmmts[]"
                                                data-row-id="row-1" data-toggle="modal"
                                                data-target="#commentsModal-row-1" value="false">
                                            <label for="commentsCheckbox-row-1"
                                                class="text-md font-medium ml-2">Comments</label>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="maxExtCost" class="block text-md font-medium">Max Extended
                                                Cost:</label>
                                            <input type="number" id="maxExtCost" value="0"
                                                class="bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 maxExtCost"
                                                readonly>

                                            <label for="extCost" class="block text-md font-medium mt-4">Extended
                                                Cost:</label>
                                            <input type="number" id="extCost" value="0"
                                                class="bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 extCost"
                                                readonly>
                                        </div>
                                    </div>
                                </div>


                                <button type="button"
                                    class="removeLineItem bg-red-500 text-white px-4 py-2 rounded hidden">Hapus
                                    Item</button>
                            </div>
                        </div>
                        <div class="flex justify-center mt-4">
                            <button type="button" id="addLineItem"
                                class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Item</button>
                        </div>
                    </section>
                    <h6>Confirmation</h6>
                    <section>
                        <div class="grid grid-cols-1">
                            <div class="">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title text-xl font-medium">Product Details:</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="relative overflow-x-auto">
                                            <table class="w-full text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                <thead class="text-base text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3">
                                                            Item Number
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Qty
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Price
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Max Extended Cost
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="overviewTableBody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Route To: <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="text" class="form-control w-full required"
                                                id="routeto" name="routeToApr" aria-invalid="true" readonly>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="buyer" class="block mb-2 text-md font-medium">
                                            Buyer:<span class="text-danger">*</span></label></label>
                                        <select class="form-select w-full required" id="buyer"
                                            name="routeToBuyer" aria-invalid="true">
                                            <option value="">Please select a buyer</option>
                                            <option value="linda">Linda</option>
                                            <option value="rahman">Rahman</option>
                                        </select>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="c-inputs-stacked">
                                        <input type="checkbox" id="allInfoCorrectCheckbox" class="rounded required"
                                            name="allInfoCorrect">
                                        <label for="allInfoCorrectCheckbox" class="d-block">All Data is
                                            Correct? <span class="text-danger">*</span></label></label>
                                        <input type="hidden" id="allInfoCorrectCheckboxHidden" name="allInfoCorrect"
                                            value="false">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="modalcomment">
                        {{-- Modal Comment Template --}}
                        <div id="commentsModal-template"
                            class="commentsModal-template fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-opacity-50 modal hidden"
                            aria-hidden="true">
                            <div class="relative w-full max-w-4xl max-h-full bg-white rounded-lg shadow-lg">
                                <!-- Modal Content -->
                                <div class="relative">
                                    <!-- Modal header -->
                                    <div
                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <label class="text-xl font-medium text-gray-900">Comment</label>
                                        <label class="modal-close text-medium font-medium text-gray-900 cursor-pointer"
                                            data-target="#commentsModal-template">
                                            <i class="modal-close fa fa-times" aria-hidden="true"></i>
                                        </label>
                                    </div>
                                    <div class="p-2 md:p-3 space-y-4 controls">
                                        <textarea class="commentText form-control" cols="30" rows="10" style="border: none; width: 100%;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>


    @push('scripts')
        {{-- /* PR Number */ --}}
        <script>
            $(document).ready(function() {
                $('#prNumberInput').on('keypress', function(event) {
                    if (event.keyCode === 13) { // Jika tombol Enter ditekan
                        event.preventDefault(); // Mencegah form dari submit secara default

                        var token = $('input[name="_token"]').val(); // Mengambil CSRF token
                        var prNumber = $('#prNumberInput').val(); // Mengambil nilai dari input

                        $.ajax({
                            url: '{{ route('get.pr.number') }}', // URL tujuan
                            type: 'POST', // Metode request
                            data: {
                                _token: token, // CSRF token
                                prNumber: prNumber // Data yang dikirim
                            },
                            dataType: 'json', // Tipe data yang diharapkan dari server
                            success: function(response) {
                                if (response.prNumber) {
                                    var formattedNumber = 'PR' + response
                                        .prNumber; // Menambahkan "PR" sebelum nomor
                                    $('#prNumberInput').val(
                                        formattedNumber
                                    ); // Menetapkan nomor PR yang diformat ke input
                                    $('#enduser').val(
                                        '{{ Auth::user()->name }}'); // Menetapkan nilai end user
                                } else {
                                    alert('Error: Nomor PR tidak ditemukan');
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Error: ' + error); // Menampilkan pesan error
                            }
                        });
                    }
                });
            });
        </script>


        {{-- /* dynamis */ --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addLineItemButton = document.getElementById('addLineItem');
                addLineItemButton.disabled = true; // Disable button initially

                const rqdLineInput = document.querySelector('.rqdLine');
                if (rqdLineInput) {
                    rqdLineInput.value = 1;
                }

                const itemNumberInput = document.querySelector('.itemnumber');
                const qtyInput = document.querySelector('.reqQty');
                const unitCostInput = document.querySelector('.unitCost');
                const stockUMQtyInput = document.querySelector('.stockUMQty');
                const maxUnitCostInput = document.querySelector('.maxUnitCost');
                const maxExtCostInput = document.querySelector('.maxExtCost');
                const ExtCostInput = document.querySelector('.extCost');

                if (itemNumberInput.value.trim() !== '' &&
                    qtyInput.value.trim() !== '' &&
                    unitCostInput.value.trim() !== '') {
                    addLineItemButton.disabled = false;
                }

                [itemNumberInput, qtyInput, unitCostInput].forEach(input => {
                    input.addEventListener('input', function() {
                        if (itemNumberInput.value.trim() !== '' &&
                            qtyInput.value.trim() !== '' &&
                            unitCostInput.value.trim() !== '') {
                            addLineItemButton.disabled = false;

                            stockUMQtyInput.value = qtyInput.value;
                            maxUnitCostInput.value = unitCostInput.value;
                            ExtCostInput.value = unitCostInput.value;

                            const reqQty = parseFloat(qtyInput.value) || 0;
                            const unitCost = parseFloat(unitCostInput.value) || 0;
                            const maxExtendedCost = (reqQty * unitCost).toFixed(2);
                            maxExtCostInput.value = maxExtendedCost;
                            ExtCostInput.value = maxExtendedCost;

                            generateOverviewTable();
                        } else {
                            addLineItemButton.disabled = true;
                        }
                    });
                });

                document.getElementById('addLineItem').addEventListener('click', function() {
                    addLineItem();
                });

                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('removeLineItem')) {
                        removeLineItem(event.target);
                    } else if (event.target.closest('.modal-trigger')) {
                        openModal(event.target.closest('.modal-trigger'));
                    } else if (event.target.classList.contains('selectData')) {
                        selectData(event.target);
                    } else if (event.target.classList.contains('modal-close')) {
                        closeModal(event.target.closest('.modal'));
                    }
                });

                function addLineItem() {
                    const lineItemContainer = document.getElementById('lineItemsContainer');
                    if (!lineItemContainer) {
                        console.error('Container for line items not found');
                        return;
                    }

                    const lineItemTemplate = document.querySelector('.lineItem').cloneNode(true);
                    if (!lineItemTemplate) {
                        console.error('Template for line items not found');
                        return;
                    }

                    lineItemTemplate.querySelectorAll('input').forEach(input => {
                        input.value = '';
                        if (input.id) {
                            const originalId = input.id;
                            const newId = `${originalId}-${lineItemContainer.children.length + 1}`;
                            input.id = newId;
                            const label = lineItemTemplate.querySelector(`label[for="${originalId}"]`);
                            if (label) {
                                label.setAttribute('for', newId);
                            }
                        }
                    });

                    const commentText = lineItemTemplate.querySelector('.commentText');
                    if (commentText) {
                        commentText.value = '';
                    }

                    const removeButton = lineItemTemplate.querySelector('.removeLineItem');
                    if (removeButton) {
                        removeButton.classList.remove('hidden');
                    }

                    const newLineIndex = lineItemContainer.querySelectorAll('.lineItem').length + 1;
                    lineItemTemplate.setAttribute('data-row-id', newLineIndex);

                    const newLineNumber = document.createElement('div');
                    newLineNumber.classList.add('line-number');
                    newLineNumber.innerHTML = `
                        <div class="text-md p-5 font-bold">Line ${newLineIndex}</div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4"></div>
                    `;
                    lineItemTemplate.prepend(newLineNumber);

                    const rqdLineInput = lineItemTemplate.querySelector('.rqdLine');
                    if (rqdLineInput) {
                        rqdLineInput.value = newLineIndex;
                    }

                    const existingCheckboxContainer = lineItemTemplate.querySelector('.commentsCheckbox')?.parentNode;
                    if (existingCheckboxContainer) {
                        existingCheckboxContainer.remove();
                    }

                    lineItemTemplate.querySelector('.reqQty')?.addEventListener('input', function() {
                        updateRealTimeValues.call(this);
                        generateOverviewTable();
                    });
                    lineItemTemplate.querySelector('.unitCost')?.addEventListener('input', function() {
                        updateRealTimeValues.call(this);
                        generateOverviewTable();
                    });
                    lineItemTemplate.querySelector('.itemnumber')?.addEventListener('input', function() {
                        generateOverviewTable();
                    });

                    const commentsCheckbox = document.createElement('input');
                    commentsCheckbox.type = 'checkbox';
                    commentsCheckbox.id = `commentsCheckbox-row-${newLineIndex}`;
                    commentsCheckbox.classList.add('rounded', 'commentsCheckbox');
                    commentsCheckbox.name = 'lineCmmts[]';
                    commentsCheckbox.setAttribute('data-row-id', newLineIndex);
                    commentsCheckbox.setAttribute('data-toggle', 'modal');
                    commentsCheckbox.setAttribute('data-target', `#commentsModal-row-${newLineIndex}`);
                    commentsCheckbox.value = 'false';
                    commentsCheckbox.checked = false;

                    const hiddenCheckbox = document.createElement('input');
                    hiddenCheckbox.type = 'hidden';
                    hiddenCheckbox.name = 'lineCmmts[]';
                    hiddenCheckbox.value = 'false';

                    // Event listener to toggle modal and update checkbox value
                    commentsCheckbox.addEventListener('change', function(event) {
                        toggleModal(event.target);
                        commentsCheckbox.value = event.target.checked ? 'true' : 'false';
                        hiddenCheckbox.disabled = event.target
                            .checked; // Disable hidden input when checkbox is checked
                        updateCommentTextarea(event.target.checked,
                            newLineIndex
                        ); // Call function to update comment textarea based on checkbox status
                    });

                    const commentsLabel = document.createElement('label');
                    commentsLabel.setAttribute('for', `commentsCheckbox-row-${newLineIndex}`);
                    commentsLabel.textContent = 'Comments';
                    commentsLabel.classList.add('text-md', 'font-medium', 'ml-2');

                    const checkboxContainer = document.createElement('div');
                    checkboxContainer.classList.add('mt-4', 'flex', 'items-center');
                    checkboxContainer.appendChild(commentsCheckbox);
                    checkboxContainer.appendChild(commentsLabel);
                    checkboxContainer.appendChild(hiddenCheckbox);

                    const targetInsertPoint = lineItemTemplate.querySelector('.target-insert-point');
                    if (targetInsertPoint) {
                        targetInsertPoint.appendChild(checkboxContainer);
                    } else {
                        lineItemTemplate.appendChild(checkboxContainer);
                    }

                    function updateCommentTextarea(checked, rowIndex) {
                        const commentTextarea = document.getElementById(`commentText-${rowIndex}`);
                        if (commentTextarea) {
                            if (checked) {
                                commentTextarea.removeAttribute('disabled');
                                if (commentTextarea.value.trim() === '') {
                                    commentTextarea.value = '';
                                }
                            } else {
                                commentTextarea.setAttribute('disabled', true);
                                commentTextarea.value = '';
                            }
                        }
                    }
                    const dueDateInput = lineItemTemplate.querySelector('.rqdDueDate');
                    dueDateInput.value = new Date().toISOString().split('T')[
                        0]; // Sesuaikan dengan tanggal saat ini dalam format Y-m-d

                    // Set nilai default pada elemen input dengan class 'rqdNeedDate'
                    const needDateInput = lineItemTemplate.querySelector('.rqdNeedDate');
                    needDateInput.value = new Date().toISOString().split('T')[
                        0]; // Sesuaikan dengan tanggal saat ini dalam format Y-m-d

                    // Set nilai default pada elemen input dengan class 'purracct'
                    const purracctInput = lineItemTemplate.querySelector('.purracct');
                    purracctInput.value = '5516'; // Sesuaikan dengan nilai yang Anda inginkan

                    lineItemContainer.appendChild(lineItemTemplate);

                    // Create and append the comment modal for the new line item
                    const modalContainer = document.querySelector('.modalcomment');
                    const newCommentModal = createCommentModal(newLineIndex);
                    modalContainer.appendChild(newCommentModal);

                    updateCosts();
                    generateOverviewTable();
                }

                function removeLineItem(button) {
                    button.closest('.lineItem').remove();
                    updateCosts();
                    generateOverviewTable();
                }

                let cmtCmmt = [];

                function addCommentEventListener(textArea) {
                    textArea.addEventListener('input', function() {
                        const commentIndex = textArea.getAttribute('data-row-id');
                        cmtCmmt[commentIndex] = textArea.value;
                        console.log(`Comment ${commentIndex}: ${textArea.value}`);
                    });
                }

                function createCommentModal(rowId) {
                    const modalTemplate = document.querySelector('.commentsModal-template').cloneNode(true);
                    const modalId = `commentsModal-row-${rowId}`;
                    modalTemplate.id = modalId;

                    const commentTextarea = modalTemplate.querySelector('.commentText');
                    const commentTextareaId = `commentText-${rowId}`;
                    commentTextarea.id = commentTextareaId;
                    commentTextarea.setAttribute('name', `cmtCmmt[]`);

                    const modalCloseButton = modalTemplate.querySelector('.modal-close');
                    modalCloseButton.setAttribute('data-target', `#${modalId}`);

                    return modalTemplate;
                }

                function toggleModal(checkbox) {
                    const modalContainer = document.querySelector('.modalcomment');

                    if (!modalContainer) {
                        console.error('Container modal tidak ditemukan.');
                        return;
                    }

                    const rowId = checkbox.getAttribute('data-row-id');
                    const modalId = `commentsModal-row-${rowId}`;

                    let modal = modalContainer.querySelector(`#${modalId}`);

                    if (!modal) {
                        modal = createCommentModal(rowId);
                        modalContainer.appendChild(modal);
                    }

                    if (checkbox.checked) {
                        checkbox.value = 'true';
                        modal.classList.remove('hidden');
                    } else {
                        checkbox.value = 'false';
                        modal.classList.add('hidden');
                    }
                }

                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('modal-close')) {
                        const targetModalId = event.target.getAttribute('data-target');
                        const targetModal = document.querySelector(targetModalId);

                        if (targetModal) {
                            targetModal.classList.add('hidden');
                            const commentTextarea = targetModal.querySelector('.commentText');
                            const commentIndex = commentTextarea.id.split('-')[1];
                            cmtCmmt[commentIndex] = commentTextarea.value;
                            console.log(`Saved comment ${commentIndex}: ${commentTextarea.value}`);
                        }
                    }
                });

                document.querySelectorAll('.commentsCheckbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function(event) {
                        toggleModal(event.target);
                    });
                });


                // Function to initialize DataTables
                function initializeDataTable(table) {
                    if ($.fn.DataTable.isDataTable(table)) {
                        table.DataTable().clear().destroy(); // Bersihkan dan hancurkan jika tabel sudah diinisialisasi
                    }
                    table.DataTable({
                        processing: true,
                        serverSide: false,
                        "pageLength": 10,
                        "lengthChange": false,
                        "pagingType": "simple_numbers"
                    });
                }

                document.getElementById('directCheckbox').addEventListener('change', function() {
                    const isChecked = this.checked;
                    const itemnumberInput = document.querySelector(
                    '.lineItem .itemnumber'); // Adjust this selector based on your HTML structure

                    if (itemnumberInput) {
                        itemnumberInput.readOnly = isChecked;
                    }
                });

                // Function to open a modal and load its content dynamically
                function openModal(trigger) {
                    const target = trigger.getAttribute('data-modal-target');
                    const modal = document.getElementById(target);

                    if (!modal) {
                        console.error(`Modal with ID '${target}' not found.`);
                        return;
                    }

                    const modalTitle = modal.querySelector('.modal-title');
                    const tableBody = modal.querySelector('.dynamicTableBody');
                    const rowToFill = trigger.closest('.lineItem'); // Identify the row that triggered the modal

                    // Store the rowToFill in the modal's dataset for later use
                    const rowId = rowToFill.getAttribute('data-row-id');
                    modal.setAttribute('data-row-to-fill', rowId);
                    console.log("Opening modal for row ID:", rowId); // Debugging

                    // Clear previous table body content
                    tableBody.innerHTML = '';

                    let ajaxUrl = '';
                    let ajaxData = {};

                    // Check if the direct checkbox is checked
                    const directCheckbox = document.getElementById('directCheckbox');
                    if (directCheckbox && directCheckbox.checked) {
                        // Make itemnumber input readonly
                        const itemnumberInput = rowToFill.querySelector('.itemnumber');
                        if (itemnumberInput) {
                            itemnumberInput.setAttribute('readonly', 'readonly');
                        }
                    }

                    if (target === 'modal-item') {
                        if (modalTitle) {
                            modalTitle.innerText = 'Items';
                        }
                        ajaxUrl = '{{ route('get.items.ajax') }}';
                        ajaxData = {
                            type: 'items'
                        };

                        // Check if the direct checkbox is checked
                        const directCheckbox = document.getElementById('directCheckbox');
                        if (directCheckbox && directCheckbox.checked) {
                            ajaxData.pt_prod_line = ['0110', '0111', '0112', '0113', '0114', '0115', '0120', '0201',
                                '0202', '0203', '0204', '0205', '0206', '0207', '0208', '0209', '0210', '0212',
                                '0213', '0214', '0215', '0216', '0217', '0218', '0220', '0221', '0222', '0309',
                                '0310', '0311', '0312', '0313', '0314', '0315', '0316', '0317', '0401', '0402',
                                '0403'
                            ];
                            ajaxData.pt_taxable = 'true';
                        }
                    } else if (target === 'modal-supplieritem') {
                        if (modalTitle) {
                            modalTitle.innerText = 'Supplier';
                        }
                        ajaxUrl = '{{ route('get.suppliers.ajax') }}';
                        ajaxData = {
                            type: 'supplier'
                        };
                    } else if (target === 'modal-purracct') {
                        if (modalTitle) {
                            modalTitle.innerText = 'Purchasing Account';
                        }
                        ajaxUrl = '{{ route('get.account.ajax') }}';
                        ajaxData = {
                            type: 'account'
                        };
                    }

                    $.ajax({
                        url: ajaxUrl,
                        type: 'GET',
                        data: ajaxData,
                        success: function(response) {
                            console.log("Response received successfully:",
                                response); // Check response in console log
                            let tableBodyHtml = '';

                            if (target === 'modal-item') {
                                response.data.forEach(item => {
                                    tableBodyHtml += `
                        <tr class="cursor-pointer" data-supplier-code="${item.pt_part}" data-um="${item.pt_um}" data-desc1="${item.pt_desc1}">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${item.pt_part}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${item.pt_desc1}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${item.pt_um}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${item.pt_part_type}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${item.pt_status === '0002' ? 'Non Active' : 'Active'}</td>
                        </tr>`;
                                });
                                // If direct checkbox is checked, itemnumber input should be readonly
                                if (directCheckbox && directCheckbox.checked) {
                                    const itemnumberInput = rowToFill.querySelector('.itemnumber');
                                    if (itemnumberInput) {
                                        itemnumberInput.setAttribute('readonly', 'readonly');
                                    }
                                }
                            } else if (target === 'modal-supplieritem') {
                                response.data.forEach(supplier => {
                                    tableBodyHtml += `
                        <tr class="cursor-pointer" data-supplier-code="${supplier.vd_addr}">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${supplier.vd_addr}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${supplier.vd_sort}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${supplier.ad_name}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${supplier.ad_line1}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${supplier.ad_city}</td>
                        </tr>`;
                                });
                            } else if (target === 'modal-purracct') {
                                response.data.forEach(account => {
                                    tableBodyHtml += `
                        <tr class="cursor-pointer" data-supplier-code="${account.ac_code}">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${account.ac_code}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${account.ac_desc}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${account.ac_curr}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">${account.ac_gl_type}</td>
                        </tr>`;
                                });
                            }

                            tableBody.innerHTML = tableBodyHtml;

                            // Attach click event to table rows
                            tableBody.querySelectorAll('tr').forEach(row => {
                                row.addEventListener('click', () => {
                                    // Get the rowToFill element using the stored dataset attribute
                                    const rowToFillId = modal.getAttribute(
                                        'data-row-to-fill');
                                    const rowToFill = document.querySelector(
                                        `[data-row-id="${rowToFillId}"]`);

                                    if (rowToFill) {
                                        console.log("Row to fill found:", rowToFill);

                                        // Set input values and close modal
                                        if (target === 'modal-item') {
                                            if (rowToFill.querySelector('.itemnumber')) {
                                                rowToFill.querySelector('.itemnumber')
                                                    .value = row.getAttribute(
                                                        'data-supplier-code');
                                            }
                                            if (rowToFill.querySelector('.rqdUm')) {
                                                rowToFill.querySelector('.rqdUm').value =
                                                    row.getAttribute('data-um');
                                            }
                                            if (rowToFill.querySelector('.rqdDesc')) {
                                                const desc = row.getAttribute('data-desc1');
                                                rowToFill.querySelector('.rqdDesc').value =
                                                    desc ? desc : rowToFill.querySelector(
                                                        '.itemnumber').value;
                                                updateRealTimeValues.call(rowToFill
                                                    .querySelector('.rqdDesc')
                                                ); // Update real-time values appropriately
                                            }
                                        } else if (target === 'modal-supplieritem') {
                                            if (rowToFill.querySelector('.supplieritem')) {
                                                rowToFill.querySelector('.supplieritem')
                                                    .value = row.getAttribute(
                                                        'data-supplier-code');
                                            }
                                        } else if (target === 'modal-purracct') {
                                            if (rowToFill.querySelector('.purracct')) {
                                                rowToFill.querySelector('.purracct').value =
                                                    row.getAttribute('data-supplier-code');
                                            }
                                        }

                                        // Close the modal
                                        closeModal(modal);
                                    } else {
                                        console.error("Row to fill not found for ID:",
                                            rowToFillId);
                                    }
                                });
                            });

                            initializeDataTable($(modal).find('.dataModalTable'));

                            modal.classList.remove('hidden');
                        },
                        error: function(xhr, status, error) {
                            alert('Error: ' + error);
                        }
                    });
                }

                // Function to close the modal
                function closeModal(modal) {
                    if (!modal) {
                        console.error('Modal element is not provided.');
                        return;
                    }

                    // Destroy DataTable if it exists
                    const table = $(modal).find('.dataModalTable');
                    if ($.fn.DataTable.isDataTable(table)) {
                        table.DataTable().clear().destroy();
                    }

                    // Clear modal content if it has dynamicTableBody
                    const dynamicTableBody = modal.querySelector('.dynamicTableBody');
                    if (dynamicTableBody) {
                        dynamicTableBody.innerHTML = '';
                    }

                    // Hide modal
                    modal.classList.add('hidden');
                    modal.removeAttribute('data-row-id');
                }


                // Function to generate the overview table
                function generateOverviewTable() {
                    const lineItems = document.querySelectorAll('.lineItem');
                    const overviewTableBody = document.getElementById('overviewTableBody');
                    overviewTableBody.innerHTML = ''; // Clear previous content

                    lineItems.forEach(lineItem => {
                        const itemNumber = lineItem.querySelector('.itemnumber').value;
                        const reqQty = lineItem.querySelector('.reqQty').value;
                        const unitCost = lineItem.querySelector('.unitCost').value;
                        const maxExtendedCost = (parseFloat(reqQty) * parseFloat(unitCost)).toFixed(2);

                        console.log(
                            `Menambahkan item dengan itemNumber: ${itemNumber}, reqQty: ${reqQty}, unitCost: ${unitCost}, maxExtendedCost: ${maxExtendedCost}`
                        );

                        const row = document.createElement('tr');
                        row.classList.add('border-b');

                        row.innerHTML = `
                                        <td class="px-6 py-4">${itemNumber}</td>
                                        <td class="px-6 py-4">${reqQty}</td>
                                        <td class="px-6 py-4">${unitCost}</td>
                                        <td class="px-6 py-4">${maxExtendedCost}</td>
                                    `;

                        // Check if data is not null or undefined before appending to the table body
                        if (itemNumber && reqQty && unitCost && maxExtendedCost) {
                            overviewTableBody.appendChild(row);
                        }
                    });
                }


                // Function to update costs
                function updateCosts() {
                    const lineItems = document.querySelectorAll('.lineItem');

                    lineItems.forEach(lineItem => {
                        const reqQtyInput = lineItem.querySelector('.reqQty');
                        const stockUMQtyInput = lineItem.querySelector('.stockUMQty');
                        const unitCostInput = lineItem.querySelector('.unitCost');
                        const maxUnitCostInput = lineItem.querySelector('.maxUnitCost');
                        const extCostInput = lineItem.querySelector('.extCost');
                        const maxExtCostInput = lineItem.querySelector('.maxExtCost');

                        // Check if elements exist before setting their values
                        if (reqQtyInput && stockUMQtyInput && unitCostInput && maxUnitCostInput &&
                            extCostInput && maxExtCostInput) {
                            const reqQty = parseFloat(reqQtyInput.value) || 0;
                            const unitCost = parseFloat(unitCostInput.value) || 0;
                            const maxExtendedCost = (reqQty * unitCost).toFixed(2);

                            console.log(
                                `Memperbarui biaya untuk item dengan reqQty: ${reqQty}, unitCost: ${unitCost}, maxExtendedCost: ${maxExtendedCost}`
                            );

                            maxExtCostInput.value = maxExtendedCost;
                            extCostInput.value = maxExtendedCost;
                            stockUMQtyInput.value = reqQty;
                            maxUnitCostInput.value = unitCost;
                            generateOverviewTable();
                        } else {
                            console.error('Some inputs are missing in the line item:', {
                                reqQtyInput,
                                stockUMQtyInput,
                                unitCostInput,
                                maxUnitCostInput,
                                extCostInput,
                                maxExtCostInput,
                            });
                        }
                    });
                }

                // Function to update real-time values
                function updateRealTimeValues() {
                    const lineItem = this.closest('.lineItem');
                    const reqQtyInput = lineItem.querySelector('.reqQty');
                    const stockUMQtyInput = lineItem.querySelector('.stockUMQty');
                    const unitCostInput = lineItem.querySelector('.unitCost');
                    const maxUnitCostInput = lineItem.querySelector('.maxUnitCost');
                    const extCostInput = lineItem.querySelector('.extCost');
                    const maxExtCostInput = lineItem.querySelector('.maxExtCost');

                    // Check if elements exist before setting their values
                    if (reqQtyInput && stockUMQtyInput && unitCostInput && maxUnitCostInput &&
                        extCostInput && maxExtCostInput) {
                        const reqQty = parseFloat(reqQtyInput.value) || 0;
                        const unitCost = parseFloat(unitCostInput.value) || 0;
                        const maxExtendedCost = (reqQty * unitCost).toFixed(2);

                        maxExtCostInput.value = maxExtendedCost;
                        extCostInput.value = maxExtendedCost;
                        stockUMQtyInput.value = reqQty;
                        maxUnitCostInput.value = unitCost;
                    } else {
                        console.error('Some inputs are missing in the line item during real-time update:', {
                            reqQtyInput,
                            stockUMQtyInput,
                            unitCostInput,
                            maxUnitCostInput,
                            extCostInput,
                            maxExtCostInput
                        });
                    }
                }

                // Call the function initially to generate the table
                generateOverviewTable();

                // Initial call to update costs on page load
                updateCosts();

                const appstatusSelect = document.getElementById('appstatus');
                const directCheckbox = document.getElementById('directCheckbox');
                const nonPOCheckbox = document.getElementById('nonPOCheckbox');
                const allInfoCorrectCheckbox = document.getElementById('allInfoCorrectCheckbox');
                const lineCmmtsCheckboxes = document.querySelectorAll('.commentsCheckbox-row-1');

                // Set initial values
                appstatusSelect.value = appstatusSelect.value === '2' ? 'Approved' : 'Unapproved';

                function updateHiddenInput(checkbox, hiddenInput) {
                    hiddenInput.value = checkbox.checked ? 'true' : 'false';
                }

                // Initial update for hidden inputs
                updateHiddenInput(directCheckbox, document.getElementById('directCheckboxHidden'));
                updateHiddenInput(nonPOCheckbox, document.getElementById('nonPOCheckboxHidden'));
                if (allInfoCorrectCheckbox) {
                    updateHiddenInput(allInfoCorrectCheckbox, document.getElementById('allInfoCorrectCheckboxHidden'));
                }

                // Add event listeners
                directCheckbox.addEventListener('change', function() {
                    updateHiddenInput(this, document.getElementById('directCheckboxHidden'));
                });

                nonPOCheckbox.addEventListener('change', function() {
                    updateHiddenInput(this, document.getElementById('nonPOCheckboxHidden'));
                });

                if (allInfoCorrectCheckbox) {
                    allInfoCorrectCheckbox.addEventListener('change', function() {
                        updateHiddenInput(this, document.getElementById('allInfoCorrectCheckboxHidden'));
                    });
                }

                appstatusSelect.addEventListener('change', function() {
                    this.value = this.value === '1' ? 'Unapproved' : 'Approved';
                });
            });
        </script>

        {{-- supplier --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Toggle modal visibility
                document.querySelectorAll('[data-modal-toggle]').forEach(a => {
                    a.addEventListener('click', () => {
                        const modalId = a.getAttribute('data-modal-target');
                        const modal = document.getElementById(modalId);
                    });
                });

                // Hide modal
                document.querySelectorAll('[data-modal-hide]').forEach(a => {
                    a.addEventListener('click', () => {
                        const modalId = a.getAttribute('data-modal-hide');
                        const modal = document.getElementById(modalId);
                        modal.classList.add('hidden');
                    });
                });

                // Set input value and close modal on row click
                document.querySelectorAll('#large-modal tbody tr').forEach(row => {
                    row.addEventListener('click', () => {
                        const supplierCode = row.getAttribute('data-supplier-code');
                        document.getElementById('supplier').value = supplierCode;
                        document.getElementById('modal-close-a').click();
                    });
                });
            });

            $(document).ready(function() {
                $('#supplierTable').DataTable({
                    "pageLength": 10,
                    "lengthChange": false,
                    "pagingType": "simple_numbers" // Ubah sesuai kebutuhan: simple, simple_numbers, full, atau full_numbers
                });
            });
        </script>

        {{-- cost center --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // Toggle modal visibility
                document.querySelectorAll('[data-modal-toggle]').forEach(a => {
                    a.addEventListener('click', () => {
                        const modalId = a.getAttribute('data-modal-target');
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            modal.classList.toggle('hidden');
                        }
                    });
                });

                // Hide modal
                document.querySelectorAll('[data-modal-hide]').forEach(a => {
                    a.addEventListener('click', () => {
                        const modalId = a.getAttribute('data-modal-hide');
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            modal.classList.add('hidden');
                        }
                    });
                });

                // Set input value and close modal on row click
                document.querySelectorAll('#large-modal-cost tbody tr').forEach(row => {
                    row.addEventListener('click', () => {
                        const costCenter = row.getAttribute('data-supplier-code');
                        const costCenterInput = document.getElementById('costcenter');
                        costCenterInput.value = costCenter;
                        document.getElementById('modal-close-a-cost').click();

                        // Manually trigger change event
                        const event = new Event('change');
                        costCenterInput.dispatchEvent(event);
                    });
                });

                // Fetch and handle approver data on cost center change
                const costCenterInput = document.getElementById('costcenter');
                if (costCenterInput) {
                    costCenterInput.addEventListener('change', function() {
                        const costCenter = this.value;
                        console.log('Cost center changed:', costCenter);
                        $.ajax({
                            type: 'GET',
                            url: `/get-approver`,
                            data: {
                                costCenter: costCenter
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log('Response data:', data);
                                if (data && data.rqa_apr) {
                                    const routetoInput = document.getElementById('routeto');

                                    // Capitalize the first letter of rqa_apr
                                    const rqaApr = data.rqa_apr.charAt(0).toUpperCase() + data
                                        .rqa_apr.slice(1);
                                    routetoInput.value = rqaApr;
                                } else {
                                    const routetoInput = document.getElementById('routeto');
                                    routetoInput.value = '';
                                    alert('Approver not found for this cost center.');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                alert('Error: ' + error);
                            }
                        });
                    });
                } else {
                    console.log('Cost center input element not found');
                }
            });

            $(document).ready(function() {
                $('#costTable').DataTable({
                    "pageLength": 10,
                    "lengthChange": false,
                    "pagingType": "simple_numbers"
                });
            });
        </script>


        {{-- end user --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // Toggle modal visibility
                document.querySelectorAll('[data-modal-toggle]').forEach(a => {
                    a.addEventListener('click', () => {
                        const modalId = a.getAttribute('data-modal-target');
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            modal.classList.toggle('hidden');
                        }
                    });
                });

                // Hide modal
                document.querySelectorAll('[data-modal-hide]').forEach(a => {
                    a.addEventListener('click', () => {
                        const modalId = a.getAttribute('data-modal-hide');
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            modal.classList.add('hidden');
                        }
                    });
                });

                // Set input value and close modal on row click
                document.querySelectorAll('#large-modal-enduser tbody tr').forEach(row => {
                    row.addEventListener('click', () => {
                        const endUser = row.getAttribute('data-supplier-code');
                        const endUserInput = document.getElementById('enduser');
                        endUserInput.value = endUser;
                        document.getElementById('modal-close-a-enduser').click();

                        // Manually trigger change event
                        const event = new Event('change');
                        endUserInput.dispatchEvent(event);
                    });
                });
            });

            $(document).ready(function() {
                $('#endUserTable').DataTable({
                    "pageLength": 10,
                    "lengthChange": false,
                    "pagingType": "simple_numbers"
                });
            });
        </script>

        {{-- checkCurr --}}
        <script>
            // Add JavaScript to handle real-time validation and required field logic
            document.getElementById('currency').addEventListener('change', function(event) {
                var selectedCurrency = this.value;

                // Reset message container
                var messageContainer = document.getElementById('messageContainer');
                messageContainer.innerHTML = '';



                // Check if selected currency is IDR (123295)
                if (selectedCurrency === '123295') {
                    messageContainer.innerHTML = '<span class="text-green-600">Currency is available!</span>';

                } else {
                    // Call the backend API to check currency availability
                    fetch('{{ route('check.curr') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ rqmCurr: selectedCurrency })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error === 'true') {
                            messageContainer.innerHTML = '<span class="text-green-600">Currency is available!</span>';

                        } else {
                            messageContainer.innerHTML = '<span class="text-red-600">Currency not available right now, please choose another currency.</span>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        messageContainer.innerHTML = '<span class="text-red-600">An error occurred while checking currency.</span>';

                    });
                }

            });
        </script>

    @endpush




    {{-- modal supplier --}}

    <div id="large-modal" tabindex="-1"
        class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto  bg-opacity-50 modal hidden"
        aria-hidden="true">
        <div class="relative w-full max-w-4xl max-h-full bg-white rounded-lg shadow-lg">
        <!-- Modal content -->
        <div class="relative">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <label class="text-xl font-medium text-gray-900">Supplier</label>
                    <a type="a"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-md w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="large-modal" id="modal-close-a">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </a>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4 text-md overflow-y-auto max-h-[calc(100vh-8rem)]">
                    <table class="min-w-full leading-normal" id="supplierTable">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Code Supplier</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Sort Name Supplier</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Name Supplier</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Address1</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    City</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $item)
                                <tr class="cursor-pointer" data-supplier-code="{{ $item->vd_addr }}">
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->vd_addr }}</td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->vd_sort }}
                                    </td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->ad_name }}</td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->ad_line1 }}</td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->ad_city }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    {{-- modal cost center --}}
    <div id="large-modal-cost" tabindex="-1"
        class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto  bg-opacity-50 modal hidden"
        aria-hidden="true">
        <div class="relative w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <label class="text-xl font-medium text-gray-900">Cost Center</label>
                    <a type="a"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-md w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="large-modal-cost" id="modal-close-a-cost">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </a>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <table class="min-w-full leading-normal" id="costTable">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Cost Center</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Description</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cost as $item)
                                <tr class="cursor-pointer" data-supplier-code="{{ $item->cc_ctr }}">
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->cc_ctr }}</td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->cc_desc }}
                                    </td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->cc_active ? 'Active' : 'Non Active' }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal employee --}}
    <div id="large-modal-enduser" tabindex="-1"
        class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto  bg-opacity-50 modal hidden"
        aria-hidden="true">
        <div class="relative w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <label class="text-xl font-medium text-gray-900">Employee</label>
                    <a type="a"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-md w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="large-modal-enduser" id="modal-close-a-enduser">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </a>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <table class="min-w-full leading-normal" id="endUserTable">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Employee</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Sort Name</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    City</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Country</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Active</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Employee Date</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $item)
                                <tr class="cursor-pointer" data-supplier-code="{{ $item->emp_addr }}">
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->emp_addr }}</td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->emp_sort }}
                                    </td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->emp_city }}
                                    </td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->emp_country }}
                                    </td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->emp_emp_date }}
                                    </td>
                                    <td
                                        class="px-5 py-5 border-b border-gray-200 bg-white text-md text-gray-600 uppercase tracking-wider">
                                        {{ $item->emp_active ? 'Active' : 'Non Active' }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div id="modal-purracct" tabindex="-1"
        class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto  bg-opacity-50 modal hidden"
        aria-hidden="true">
        <div class="relative w-full max-w-4xl max-h-full bg-white rounded-lg shadow-lg">
            <!-- Modal content -->
            <div class="relative">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <label class="text-xl font-medium text-gray-900">Purchase Account</label>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-md w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white modal-close"
                        data-modal-hide="large-modal" id="modal-close-a">
                        <svg class="w-5 h-5 pointer-events-none" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4 text-md overflow-y-auto max-h-[calc(100vh-8rem)]">
                    <table id="dataModalTable-account" class="min-w-full display dataModalTable">
                        <thead class="dynamicTableHead">
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Account Code</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Account Name</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Account Currency</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Account Type</th>
                            </tr>
                        </thead>
                        <tbody class="dynamicTableBody">
                            <!-- Dynamic content goes here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-supplieritem" tabindex="-1"
        class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto  bg-opacity-50 modal hidden"
        aria-hidden="true">
        <div class="relative w-full max-w-4xl max-h-full bg-white rounded-lg shadow-lg">
            <!-- Modal content -->
            <div class="relative">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <label class="text-xl font-medium text-gray-900">Supplier</label>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-md w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white modal-close"
                        data-modal-hide="large-modal" id="modal-close-supplier">
                        <svg class="w-5 h-5 pointer-events-none" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4 text-md overflow-y-auto max-h-[calc(100vh-8rem)]">
                    <table id="dataModalTable-supplier" class="min-w-full leading-normal display dataModalTable">
                        <thead class="dynamicTableHead">
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Code Supplier</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Sort Name Supplier</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Name Supplier</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Address1</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    City</th>
                            </tr>
                        </thead>
                        <tbody class="dynamicTableBody">
                            <!-- Dynamic content goes here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-item" tabindex="-1"
        class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto  bg-opacity-50 modal hidden"
        aria-hidden="true">
        <div class="relative w-full max-w-4xl max-h-full bg-white rounded-lg shadow-lg">
            <!-- Modal content -->
            <div class="relative">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <label class="text-xl font-medium text-gray-900">Items</label>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-md w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white modal-close"
                        data-modal-hide="large-modal" id="modal-close-item">
                        <svg class="w-5 h-5 pointer-events-none" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4 text-md overflow-y-auto max-h-[calc(100vh-8rem)]">
                    <table id="dataModalTable-item" class="min-w-full leading-normal display dataModalTable">
                        <thead class="dynamicTableHead">
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Product Code</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Product Name</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    UM</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Item Type</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="dynamicTableBody">
                            <!-- Dynamic content goes here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
