<x-app-layout>
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium">List Users</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page"> Users</li>
                        <li class="breadcrumb-item active" aria-current="page"> List Users</li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>

    <section class="content">
        <div class="">
            <div class="card">
                <div class="card-body">
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table id="usersTable" class="table table-striped w-full text-base text-left rtl:text-right text-gray-500 dark:text-gray-400 table-bordered">
                            <thead class="text-base  uppercase border-b">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Nama User
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Posisi
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Departemen
                                    </th>
                                    <th scope="col">
                                       Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr class="border-b">
                                    <td class="px-6 py-4">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $user->position->position_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $user->position->department->department_name }}
                                    </td>
                                    <td class="table-action min-w-100 flex items-center">
                                        <a href="/users/{{ $user->id }}/edit" class="text-fade hover-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 align-middle"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg></a>
                                        <form action="/users/{{ $user->id }}/delete" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="text-fade hover-primary" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash align-middle"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                "pageLength": 5,
                "lengthChange": false,
                "pagingType": "simple_numbers",
                "dom": 'Bfrtip',
                "buttons": [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>
