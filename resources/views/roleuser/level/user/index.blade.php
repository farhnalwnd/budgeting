<x-app-layout>
    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium">List Level</h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page">Level</li>
                        <li class="breadcrumb-item active" aria-current="page">List Level</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <a href="{{ route('level.create') }}" class="btn btn-success ml-4">Tambah Level</a>

        <div class="table-responsive">
            <table class="text-fade table b-1 border-warning w-full" id="levelsTable">
                <thead class="bg-warning text-left">
                    <tr>
                        <th>#</th>
                        <th>Nama Level</th>
                        <th>Edit</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($levels as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->level_name }}</td>
                        <td><a href="/levels/{{ $item->level_slug }}/edit"><i class="mdi mdi-pencil"></i></a></td>
                        <td>
                            <form action="/levels/{{ $item->level_slug }}/delete" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus level ini?')"><i class="mdi mdi-delete"></i></button>
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
            $('#levelsTable').DataTable({
                "pageLength": 5,
                "lengthChange": false,
                "pagingType": "simple_numbers"
            });
        });
    </script>
    @endpush
</x-app-layout>
