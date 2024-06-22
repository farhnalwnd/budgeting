<x-app-layout>
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium">Daftar Permissions</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page">Permissions</li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar Permissions</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <a href="{{ route('permissions.create') }}" class="btn btn-success ml-4">Tambah Permission</a>
        <div class="table-responsive">
            <table class="text-fade table b-1 border-warning w-full" id="permissionsTable">
                <thead class="bg-warning text-left">
                    <tr>
                        <th>#</th>
                        <th>Nama Permission</th>
                        <th>Edit</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $permission->name }}</td>
                        <td><a href="{{ route('permissions.edit', $permission->id) }}"><i class="mdi mdi-pencil"></i></a></td>
                        <td>
                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus permission ini?')"><i class="mdi mdi-delete"></i></button>
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
            $('#permissionsTable').DataTable({
                "pageLength": 5,
                "lengthChange": false,
                "pagingType": "simple_numbers"
            });
        });
    </script>
    @endpush
</x-app-layout>
