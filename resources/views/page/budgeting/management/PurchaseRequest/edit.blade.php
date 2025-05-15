<x-app-layout>
    <div class="card">
        <div class="card-header">
            <h1 class="card-title text-2xl font-medium">Purchase Request</h1>
        </div>
        <div class="card-body">
            <table id="usersTable" class="table table-striped w-full text-left rtl:text-right table-bordered">
                <thead class="uppercase border-b">
                    <tr>
                        <th class="px-6 py-3 text-lg text-center w-5">#</th>
                        <th class="px-6 py-3 text-lg text-center whitespace-nowrap w-10">PO Number</th>
                        <th class="px-6 py-3 text-lg text-center">Item Name</th>
                        <th class="px-6 py-3 text-lg text-center w-56">department</th>
                        <th class="px-6 py-3 text-lg text-center w-48">Amount</th>
                        <th class="px-6 py-3 text-lg text-center w-5">Quantity</th>
                        <th class="px-6 py-3 text-lg text-center w-5">status</th>
                        <th class="px-6 py-3 text-lg text-center w-1/6">remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                    <tr>
                        <td class="px-6 py-4 text-lg text-center w-5">{{ $purchase->id }}</td>
                        <td class="px-6 py-4 text-lg text-center whitespace-nowrap w-10">{{ $purchase->purchase_no }}</td>
                        <td class="px-6 py-4 text-lg">{{ $purchase->item_name }}</td>
                        <td class="px-6 py-4 text-lg w-56 text-center">{{ $purchase->department->department_name }}</td>
                        <td class="px-6 py-4 text-lg text-center w-48">Rp. {{ number_format($purchase->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-lg text-center">{{ $purchase->quanitity }}</td>
                        <td class="px-6 py-4 text-lg text-center w-5">{{ $purchase->status }}</td>
                        <td class="px-6 py-4 text-lg w-1/6">{{ $purchase->remarks }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>