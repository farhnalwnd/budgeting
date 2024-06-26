<x-app-layout>
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium">Daftar Role</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page">Role</li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar Role</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <a href="{{ route('roles.create') }}" class="btn btn-success ml-4">Tambah Role</a>
        <div class="table-responsive">
            <table class="text-fade table b-1 border-warning w-full" id="rolesTable">
                <thead class="bg-warning text-left">
                    <tr>
                        <th>#</th>
                        <th>Nama Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    @foreach ($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <div class="flex items-center justify-center">
                                <a href="{{ url('roles/'.$role->id.'/give-permissions') }}" class="btn btn-info mr-2">
                                    Set Permission
                                </a>
                                <a type="button" href="{{ route('roles.edit', $role->id) }}" class=" btn btn-warning mr-2"><i class="mdi mdi-pencil"></i></a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus role ini?')"><i class="mdi mdi-delete"></i></button>
                                </form>
                            </div>

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
            $('#rolesTable').DataTable({
                "pageLength": 5,
                "lengthChange": false,
                "pagingType": "simple_numbers"
            });
        });
    </script>
    @endpush
</x-app-layout>
