<x-app-layout>
    @push('css')
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    @endpush
    
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="card w-fit p-6 bg-white rounded-lg shadow-md transition-all duration-500 ease-in-out min-h-[600px]">
            <div class="card-header mb-4">
                <h1 class="text-2xl font-medium">Purchase No: {{ $purchase->purchase_no }}</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('purchase-request.update', $purchase) }}" method="POST"
                x-data="{
                    grandTotal: {{ $purchase->grand_total ?? 0 }},
                    actualAmount: {{ old('actual_amount', $purchase->actual_amount) ?? 0 }},
                    oldactualAmount: {{ $purchase->actual_amount ?? 0 }},
                    deptBalance: {{ $dept->balance ?? 0 }},
                    get showDepartment() {
                        var baseAmount = this.oldactualAmount > 0 ? this.oldactualAmount : this.grandTotal;
                        return (this.actualAmount - baseAmount - this.deptBalance) > 0;
                    },
                    get showBalance() {
                        return this.actualAmount < this.deptBalance;
                    },
                    get leftColSpan() {
                        return this.showDepartment ? 'col-span-2' : 'col-span-3';
                    },
                    get shortage() {
                        var baseAmount = this.oldactualAmount > 0 ? this.oldactualAmount : this.grandTotal;
                        const short = this.actualAmount - baseAmount - this.deptBalance;
                        return short > 0 ? short.toFixed(2) : 0;
                    }
                }">
                
                    @csrf
                    @method('PUT')
                
                    <div class="grid grid-cols-3 gap-4">
                        <div class="contents">
                            <!-- Left column -->
                            <div :class="leftColSpan">
                                <div class="mb-4">
                                    <label for="category_id" class="block font-semibold mb-1">Kategori</label>
                                    <select name="category_id" id="category_id"
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $purchase->category_id) ==
                                            $category->id ?
                                            'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                
                                <div class="mb-4">
                                    <label for="PO" class="block font-semibold mb-1">PO</label>
                                    <input type="text" name="PO" id="PO" value="{{ old('PO', $purchase->PO) }}"
                                        class="w-full border border-gray-300 rounded px-3 py-2 placeholder:italic focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Nomor PO">
                                </div>
                
                                <input type="hidden" name="fromDept" value="{{ $deptId }}">
                                <input type="hidden" name="grand_total" value="{{ $purchase->grand_total }}">
                
                                <div class="mb-4">
                                    <label for="actual_amount" class="block font-semibold mb-1">Actual Amount</label>
                                    <input type="number" name="actual_amount" id="actual_amount" x-model.number="actualAmount"
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Actual amount" min="0" />
                                </div>
                            </div>
                        </div>
                
                        <template x-if="showDepartment">
                            <div class="col-span-1 transition-all duration-300 flex flex-row"
                                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-4">
                                <div class="mb-4">
                                    <label for="department_id" class="block font-semibold mb-1">Department Pinjam</label>
                                    <select name="department_id" id="department_id"
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Department --</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $purchase->department_id) ==
                                            $department->id ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </template>
                
                        <!-- Info saldo -->
                        <div class="transition-all col-span-3">
                            <div
                                class="p-6 bg-gray-50 rounded-lg border w-full border-gray-200 grid grid-flow-row gap-y-0 gap-x-6 grid-rows-2">
                                <div class="w-full col-span-2 flex items-center justify-center">
                                    <h3 class="font-semibold text-gray-700">Informasi Saldo
                                    </h3>
                                </div>
                                <div>
                                    <p class="text-center text-gray-600">Saldo Department:
                                        <span class="text-center font-medium">{{ number_format($dept->balance, 2) }}</span>
                                    </p>
                                    <p class="text-center text-gray-600">Grand Total:
                                        <span class="text-center font-medium">{{ number_format($purchase->grand_total, 2) }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-center text-gray-600">Kebutuhan:
                                        <span class="text-center font-medium"
                                            x-text="'Rp ' + Number(actualAmount).toLocaleString()"></span>
                                    </p>
                                    <p class="text-center text-red-600 mt-2" x-show="shortage > 0">
                                        Kekurangan: <span x-text="'Rp ' + Number(shortage).toLocaleString()"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-3 flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Update
                            </button>
                        </div>
                    </div>
                    @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                                        </ul>
                                        @endif
                                        </form>
                                        </div>
        </div>
    </div>
</x-app-layout>