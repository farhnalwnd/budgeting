<x-app-layout>

    <div class="container mx-auto mt-10">
        <div class="flex flex-wrap">
            <div class="w-full">

                @if (session('status'))
                    <div class="alert bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('status') }}</div>
                @endif

                <div class="card bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="card-header bg-gray-200 px-4 py-2">
                        <h4 class="font-bold">Role : {{ $role->name }}
                            <a href="{{ url('roles') }}" class="btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded float-right">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ url('roles/'.$role->id.'/give-permissions') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-6">
                                @error('permission')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                                @enderror

                                <label class="block text-gray-700 text-sm font-bold mb-2" for="">Permissions</label>

                                <div class="flex flex-wrap">
                                    @foreach ($permissions as $permission)
                                    <div class="w-1/6 mb-4">
                                        <label class="inline-flex items-center">
                                            <input
                                                type="checkbox"
                                                class="form-checkbox h-5 w-5 text-gray-600"
                                                name="permission[]"
                                                value="{{ $permission->name }}"
                                                {{ in_array($permission->id, $rolePermissions) ? 'checked':'' }}
                                            />
                                            <span class="ml-2 text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>

                            </div>
                            <div class="mb-6">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
