<x-app-layout>
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium">List Users</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page">Users</li>
                        <li class="breadcrumb-item active" aria-current="page">List Users</li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>

    <section class="content">
        <a href="{{ route('users.create') }}" class="btn btn-success ml-4">Tambah User</a>
        <div class="table-responsive">
            <table class="text-fade table b-1 border-warning w-full" id="usersTable">
                <thead class="bg-warning text-left">
                    <tr>
                        <th>#</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Posisi</th>
                        <th>Departemen</th>
                        <th>Edit</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->position->position_name }}</td>
                        <td>{{ $user->position->department->department_name }}</td>
                        <td><a href="/users/{{ $user->id }}/edit"><i class="mdi mdi-pencil"></i></a></td>
                        <td>
                            <form action="/users/{{ $user->id }}/delete" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"><i class="mdi mdi-delete"></i></button>
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
            $('#usersTable').DataTable({
                "pageLength": 5,
                "lengthChange": false,
                "pagingType": "simple_numbers"
            });
        });
    </script>
    @endpush
</x-app-layout>
