<x-app-layout>
    @section('title', 'Purchase Request')
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

        <section class="content">

            <!-- Add User Button -->
            <div class="mb-4 flex justify-end">
                <button type="button"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right"
                data-modal-target="createUserModal" data-modal-toggle="createUserModal">
                Add User
            </button>
        </div>
        
        <!--card data display -->
        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">Purchase Request</h1>
            </div>
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="usersTable" class="table table-striped w-full text-left rtl:text-right table-bordered">
                        <thead class="uppercase border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-lg">#</th>
                                <th scope="col" class="px-6 py-3 text-lg">NO Budget</th>
                                <th scope="col" class="px-6 py-3 text-lg">Item Name</th>
                                <th scope="col" class="px-6 py-3 text-lg">Amount</th>
                                <th scope="col" class="px-6 py-3 text-lg">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)
                                <tr>
                                    <td class="px-6 py-4 text-lg">{{ $purchase->id }}</td>
                                    <td class="px-6 py-4 text-lg">{{ $purchase->budget_no }}</td>
                                    <td class="px-6 py-4 text-lg">{{ $purchase->item_name}}</td>
                                    <td class="px-6 py-4 text-lg">{{ $purchase->amount }}</td>
                                    <td class="px-6 py-4 text-lg">{{ $purchase->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>