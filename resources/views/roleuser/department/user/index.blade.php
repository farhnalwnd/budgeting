<x-app-layout>
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium">List Department</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page">Departemen</li>
                        <li class="breadcrumb-item active" aria-current="page">List Departemen</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <a href="{{ route('department.create') }}" class="btn btn-success ml-4">Tambah Department</a>

        <div class="table-responsive">
            <table class="text-fade table b-1 border-warning w-full" id="departmentsTable">
                <thead class="bg-warning text-left">
                    <tr>
                        <th>#</th>
                        <th>Nama Departemen</th>
                        <th>Edit</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    @foreach ($departments as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->department_name }}</td>
                        <td><a href="/departments/{{ $item->department_slug }}/edit"><i class="mdi mdi-pencil"></i></a></td>
                        <td>
                            <form action="/departments/{{ $item->department_slug }}/delete" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus departemen ini?')"><i class="mdi mdi-delete"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#departmentsTable').DataTable({
                "pageLength": 5,
                "lengthChange": false,
                "pagingType": "simple_numbers"
            });
        });
    </script>
    @endpush
</x-app-layout>
