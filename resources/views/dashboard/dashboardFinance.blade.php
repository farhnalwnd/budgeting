<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard Finance') }}
    </h2>
</x-slot>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-x-4">
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-primary">
                        <p class="text-fade mb-10">User</p>
                        <h2 class="my-0 fw-700 text-3xl">{{ $totalUser }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-users bg-primary-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-info">
                        <p class="text-fade mb-10">Item</p>
                        <h2 class="my-0 fw-700 text-3xl">{{ $totalItem }}</h2>
                    </div>
                    <div class="icon">
                        <i
                            class="fa-solid fa-hand-holding-dollar bg-info-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-warning">
                        <p class="text-fade mb-10">Supplier</p>
                        <h2 class="my-0 fw-700 text-3xl">{{ $totalSupplier }}</h2>
                    </div>
                    <div class="icon">
                        <i
                            class="fa-sack-dollar bg-danger-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-danger">
                        <p class="text-fade mb-10">PR</p>
                        <h2 class="my-0 fw-700 text-3xl">{{ $totalRequisitionMaster }}</h2>
                    </div>
                    <div class="icon">
                        <i
                            class="fa-solid fa-file-invoice bg-warning-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


