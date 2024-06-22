<x-app-layout>
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium">List Posisi</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page">Posisi</li>
                        <li class="breadcrumb-item active" aria-current="page">List Posisi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <a href="{{ route('position.create') }}" class="btn btn-success ml-4">Tambah Posisi</a>
        <div class="table-responsive">
            <table class="text-fade table b-1 border-warning w-full" id="positionsTable">
                <thead class="bg-warning text-left">
                    <tr>
                        <th>#</th>
                        <th>Nama Posisi</th>
                        <th>Departemen</th>
                        <th>Level</th>
                        <th>Edit</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    @foreach ($positions as $position)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $position->position_name }}</td>
                        <td>{{ $position->department->department_name }}</td>
                        <td>{{ $position->level->level_name }}</td>
                        <td><a href="/positions/{{ $position->position_slug }}/edit"><i class="mdi mdi-pencil"></i></a></td>
                        <td>
                            <form action="/positions/{{ $position->position_slug }}/delete" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus posisi ini?')"><i class="mdi mdi-delete"></i></button>
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
            $('#positionsTable').DataTable({
                "pageLength": 5,
                "lengthChange": false,
                "pagingType": "simple_numbers"
            });
        });
    </script>
    @endpush
</x-app-layout>
