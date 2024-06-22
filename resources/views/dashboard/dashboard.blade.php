<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-x-4">
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-primary">
                        <p class="text-fade mb-10">Customers</p>
                        <h2 class="my-0 fw-700 text-3xl">4,562</h2>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-users bg-primary-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>
                <p class="text-success mb-0 mt-10"><i class="fa-solid fa-arrow-up"></i> +8.5%
                    since last week</p>
            </div>
        </div>
    </div>
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-info">
                        <p class="text-fade mb-10">Revenue</p>
                        <h2 class="my-0 fw-700 text-3xl">$5,125</h2>
                    </div>
                    <div class="icon">
                        <i
                            class="fa-solid fa-hand-holding-dollar bg-info-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>
                <p class="text-danger mb-0 mt-10"><i class="fa-solid fa-arrow-down"></i> -0.10%
                    since last week</p>
            </div>
        </div>
    </div>
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-warning">
                        <p class="text-fade mb-10">Invoices</p>
                        <h2 class="my-0 fw-700 text-3xl">2,145</h2>
                    </div>
                    <div class="icon">
                        <i
                            class="fa-solid fa-file-invoice bg-warning-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>
                <p class="text-success mb-0 mt-10"><i class="fa-solid fa-arrow-up"></i> +10.5%
                    since last week</p>
            </div>
        </div>
    </div>
    <div>
        <div class="box pull-up">
            <div class="box-body">
                <div class="flex justify-between items-center">
                    <div class="bs-5 ps-10 border-danger">
                        <p class="text-fade mb-10">Profit</p>
                        <h2 class="my-0 fw-700 text-3xl">70%</h2>
                    </div>
                    <div class="icon">
                        <i
                            class="fa-solid fa-sack-dollar bg-danger-light me-0 fs-24 rounded-3"></i>
                    </div>
                </div>
                <p class="text-danger mb-0 mt-10"><i class="fa-solid fa-arrow-down"></i> -0.5%
                    since last week</p>
            </div>
        </div>
    </div>
</div>


