<x-app-layout>
    @section('title')
    List PCR
@endsection

<div class="content-header">
    <div class="flex items-center justify-between">

        <h4 class="page-title text-2xl font-medium"></h4>
        <div class="inline-flex items-center">
            <nav>
                <ol class="breadcrumb flex items-center">
                    <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i
                                class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item pr-1" aria-current="page"> PCR</li>
                    <li class="breadcrumb-item active" aria-current="page"> List PCR</li>
                </ol>
            </nav>
        </div>

    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-3">
            <button type="button"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-3 py-3 text-center me-2 mb-2 float-right"
                onclick="location.href='{{ route('pcr.create') }}'">Make New PCR</button>
        </div>
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                    <h4 class="page-title text-2xl font-medium">List PCR</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="tablePCR" class="!border-separate text-fade table table-bordered display w-full">
                            <thead class="text-left">
                                <tr class="text-dark" role="row">
                                    <th>No Register PCR</th>
                                    <th>Product Name</th>
                                    <th>Nature of Change</th>
                                    <th>Reason Of Change</th>
                                    <th>Estimated Benefit</th>
                                    <th>Initiator</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>24PCR001</td>
                                <td>Product 1 <span class="badge badge-primary">+2</span></td>
                                <td>{{ strlen('Nature A shlahslkahslkhalkshlahslkhalkshaklhslkahslkhaklshlkahsahslkahkl') > 20 ? substr('Nature A shlahslkahslkhalkshlahslkhalkshaklhslkahslkhaklshlkahsahslkahkl', 0, 20) . '...' : 'Nature A shlahslkahslkhalkshlahslkhalkshaklhslkahslkhaklshlkahsahslkahkl' }}</td>
                                <td>Reason A</td>
                                <td>Benefit A</td>
                                <td>Initiator A</td>
                                <td>Change</td>
                                <td>
                                    <a href="#" class="fa fa-pencil"></a>&nbsp;&nbsp;
                                    <a href="#" class="fa fa-trash"></a>
                                </td>
                            </tr>
                            <tr>
                                <td>24PCR002</td>
                                <td>Product B <span class="badge badge-primary">+2</span></td>
                                <td>Nature B</td>
                                <td>Reason B</td>
                                <td>Benefit B</td>
                                <td>Initiator B</td>
                                <td>Change</td>
                                <td>
                                    <a href="#" class="fa fa-pencil"></a>&nbsp;&nbsp;
                                    <a href="#" class="fa fa-trash"></a>
                                </td>
                            </tr>
                            <tr>
                                <td>24PCR003</td>
                                <td>Product C <span class="badge badge-primary">+2</span></td>
                                <td>Nature C</td>
                                <td>Reason C</td>
                                <td>Benefit C</td>
                                <td>Initiator C</td>
                                <td>Not Change</td>
                                <td>
                                    <a href="#" class="fa fa-pencil"></a>&nbsp;&nbsp;
                                    <a href="#" class="fa fa-trash"></a>
                                </td>
                            </tr>
                            <tr>
                                <td>24PCR004</td>
                                <td>Product D</td>
                                <td>Nature D</td>
                                <td>Reason D</td>
                                <td>Benefit D</td>
                                <td>Initiator D</td>
                                <td>Not Change</td>
                                <td>
                                    <a href="#" class="fa fa-pencil"></a>&nbsp;&nbsp;
                                    <a href="#" class="fa fa-trash"></a>
                                </td>
                            </tr>
                            <tr>
                                <td>24PCR005</td>
                                <td>Product E</td>
                                <td>Nature E</td>
                                <td>Reason E</td>
                                <td>Benefit E</td>
                                <td>Initiator E</td>
                                <td>On Progress</td>
                                <td>
                                    <a href="#" class="fa fa-pencil"></a>&nbsp;&nbsp;
                                    <a href="#" class="fa fa-trash"></a>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tablePCR').DataTable({

                "scrollCollapse": true,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endpush


</x-app-layout>
