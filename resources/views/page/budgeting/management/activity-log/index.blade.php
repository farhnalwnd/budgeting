<x-app-layout>
    @section('title')
        List Activity
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
                        <li class="breadcrumb-item pr-1">Activity Log</li>
                        <li class="breadcrumb-item active">List Activity</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-2xl font-medium">List Activity</h1>
            </div>
            <div class="card-body">
                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table id="activityTable" class="table table-striped w-full text-left rtl:text-right table-bordered" style="width: 100%;">
                        <thead class="uppercase border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-lg">#</th>
                                <th scope="col" class="px-6 py-3 text-lg">Name</th>
                                <th scope="col" class="px-6 py-3 text-lg">No</th>
                                <th scope="col" class="px-6 py-3 text-lg">Action</th>
                                <th scope="col" class="px-6 py-3 text-lg">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Init datatable
            var table = $('#activityTable').DataTable({
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: '{{ route('get.logs.data') }}',
                    type: 'GET',
                    dataSrc: function(response) {
                        return response;
                    }
                },
                columns: [
                    { data: null,
                        render: function(data, type, row, meta) {
                            return meta.row +1;
                        }
                    },
                    { data: null, name: 'name' ,
                        render: function(data, type, row) {
                            // console.log();
                            return row.log_name;
                        }
                    },
                    { data: null, name: 'no' ,
                        render: function(data, type, row) {
                            // console.log();
                            return row.properties.no;
                        }
                    },
                    { data: 'event', name: 'action'},
                    { data: 'description', name: 'description'}
                ]
            });
        });
       

    </script>
    @endpush

</x-app-layout>