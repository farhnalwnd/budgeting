<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Alasan Penolakan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-500">
    <div class="min-h-screen py-10 px-16 mb-10 md:flex md:items-center md:justify-evenly">
        <div class="relative w-full h-[400px] mb-5 overflow-hidden md:overflow-visible  md:w-1/2">
            <img src="/blob.svg" alt="blob" class="absolute w-full h-full object-cover overflow-visible">
            <img src="/research paper-bro.png" alt="pict"
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-h-[300px] md:max-h-[400px] z-10 object-contain">
        </div>
        <div class="md:w-1/2">
            <div class="bg-white rounded-lg shadow-md w-full md:w-full md:h-full  overflow-hidden ">
                <div class="flex items-center justify-between bg-gray-100 p-4">
                    <h2 class="text-xl font-semibold text-blue-900 justify-items-end">Alasan Penolakan</h2>
                    <img src="/sinarlogo.png" alt="logo" class="max-w-24">
                </div>

                <form method="POST" action="{{ route('budgeting.request.reject.feedback') }}" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="budget_req_no" value="{{ $budget_req_no }}">
                    <input type="hidden" name="nik" value="{{ $nik }}">

                <div>
                    <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Berikan alasan
                        penolakan:</label>
                    <textarea id="feedback" name="feedback" rows="5"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tuliskan alasan penolakan secara detail..." required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                        Kirim Alasan
                    </button>
                </div>
                </form>
        </div>
        </div>
        </div>
</body>
</html>