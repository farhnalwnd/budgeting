<x-app-layout>
    @section('title')
        Create PCR
    @endsection

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets') }}/ckeditor5/ckeditor5.css">
        <link rel="stylesheet" href="{{ asset('assets') }}/choices/choices.css">
    @endpush
    <style>
        .ck-editor__editable_inline {
            min-height: 150px;

        }
        .choices__inner {
            border-radius: 0.5rem; /* Menambahkan rounded pada elemen choices */
            padding: 2px;
            background-color: #f3f4f6;
        }
    </style>

    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-3xl font-bold"></h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page"> PCR</li>
                        <li class="breadcrumb-item active" aria-current="page"> Create PCR</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-3">
                <button type="button"
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-lg px-20 py-4 text-center me-2 mb-2 float-right"
                    onclick="location.href='{{ route('pcr.index') }}'">Back</button>
            </div>
            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="page-title text-3xl font-bold">Create PCR</h4>
                    </div>
                    <div class="">
                        <form action="{{ route('pcr.store') }}" method="POST" class="rounded-lg p-8">
                            @csrf
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div class="mb-1">
                                    <label for="no_reg" class="block mb-2 text-lg font-medium">No Reg: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="no_reg" name="no_reg" readonly
                                        class="bg-gray-100 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required"
                                        placeholder="Tekan Enter untuk mendapatkan Nomor Reg">
                                </div>
                                <div class="mb-1">
                                    <label for="initiator_id" class="block mb-2 text-lg font-medium">Initiator: <span class="text-danger">*</span></label>
                                    <select id="initiator_id" name="initiator_id" class="bg-gray-100 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required" aria-invalid="false">
                                        <option value="">Pilih Initiator</option>
                                        <option value="1">Initiator 1</option>
                                        <option value="2">Initiator 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div class="mb-1">
                                    <label for="product" class="block mb-2 text-lg font-medium">Product Item Code: <span class="text-danger">*</span></label>
                                    <select id="product" name="product[]"
                                        class="choices bg-gray-100 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required"
                                        multiple>
                                        <option value="">Pilih Product Item Code</option>
                                        <option value="1">Produk 1</option>
                                        <option value="2">Produk 2</option>
                                        <option value="3">Produk 3</option>
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <label for="database_number" class="block mb-2 text-lg font-medium">Database Number: <span class="text-danger">*</span></label>
                                    <input type="text" id="database_number" name="database_number" readonly
                                        class="bg-gray-100 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required"
                                        placeholder="Pilih Product Item Code">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block mb-2 text-md font-medium">Nature of Change</label>
                                <div class="grid grid-cols-2 gap-10">
                                    <div class="flex flex-col gap-10">
                                        <div class="flex items-center">
                                            <input id="change1" type="checkbox" name="nature_of_change[]"
                                                value="change1"
                                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-lg focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="change1" class="ml-2 text-lg font-medium ">Change 1</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="change2" type="checkbox" name="nature_of_change[]"
                                                value="change2"
                                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-lg focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="change2" class="ml-2 text-lg font-medium ">Change 2</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="change3" type="checkbox" name="nature_of_change[]"
                                                value="change3"
                                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-lg focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="change3" class="ml-2 text-lg font-medium ">Change 3</label>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-10">
                                        <div class="flex items-center">
                                            <input id="change4" type="checkbox" name="nature_of_change[]"
                                                value="change4"
                                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-lg focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="change4" class="ml-2 text-lg font-medium ">Change 4</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="change5" type="checkbox" name="nature_of_change[]"
                                                value="change5"
                                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-lg focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="change5" class="ml-2 text-lg font-medium ">Change 5</label>
                                        </div>
                                        <div class="flex items-center gap-10">
                                            <input id="other" type="checkbox" name="nature_of_change[]"
                                                value="other"
                                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-lg focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="other" class="ml-2 text-lg font-medium ">Other</label>
                                            <div id="otherNotes" class="mb-2 text-lg font-medium ml-10 hidden">
                                                <input type="text" id="otherNotesText" name="other_notes"
                                                    class="p-2 w-full text-lg bg-gray-100 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Other Nature of Change">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-1">
                                <label for="description_of_change" class="block mb-2 text-lg font-medium">Description of Change: <span class="text-danger">*</span></label>
                                <textarea id="editor" name="description_of_change" rows="5"
                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required"
                                    placeholder="Input Description of Change"></textarea>
                            </div>

                            <div class="mb-1">
                                <label for="reason_of_change" class="block mb-2 text-lg font-medium">Reason of Change: <span class="text-danger">*</span></label>
                                <textarea id="reason_of_change" name="reason_of_change" rows="5"
                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required"
                                    placeholder="Input Reason of Change"></textarea>
                            </div>

                            <div class="mb-10">
                                <label for="estimation_benefit" class="block mb-2 text-lg font-medium">Estimation Benefit: <span class="text-danger">*</span></label>
                                <input type="text" id="estimation_benefit" name="estimation_benefit"
                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 required"
                                    placeholder="Input Estimation Benefit">
                            </div>

                            <div class="flex justify-center mt-5">
                                <button type="submit"
                                    class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-lg px-25 py-4 text-center me-2 mb-2">
                                    Submit PCR
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Paragraph,
                Bold,
                Italic,
                Font,
                Table,
                TableToolbar
            } from '{{ asset('assets/ckeditor5/ckeditor5.js') }}';

            ClassicEditor
                .create(document.querySelector('#editor'), {
                    plugins: [Essentials, Paragraph, Bold, Italic, Font, Table, TableToolbar],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'insertTable'
                    ],
                    table: {
                        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                    },
                    height: '300px' // Menambahkan pengaturan tinggi
                })
                .then(editor => {
                    window.editor = editor;
                })
                .catch(error => {
                    console.error('Terjadi kesalahan saat menginisialisasi editor:', error);
                });
        </script>
        <script src="{{ asset('assets') }}/choices/choices.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const choices = new Choices('.choices', {
                    removeItemButton: true,
                    searchEnabled: true,
                    placeholderValue: 'Pilih Code Item Produk',
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#other').change(function() {
                    if (this.checked) {
                        $('#otherNotes').removeClass('hidden');
                    } else {
                        $('#otherNotes').addClass('hidden');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
