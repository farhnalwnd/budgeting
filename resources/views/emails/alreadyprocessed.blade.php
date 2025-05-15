<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>

<body>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center items-center">
        <!-- Gambar -->
        <div class="mb-11 border-2 rounded-full shadow-lg animate-pulse"> <!-- Margin bottom untuk jarak dengan teks -->
            <img src="/security.png" alt="uploaded modify" class="max-h-60 p-10">
        </div>
        <!-- Teks -->
        <div>
            <h1 class="text-center text-xl font-semibold font-sans mb-14">UUPPPSSSSSSSIIEEE</h1>
            <p class="font-light text-lg">sepertinya kamu sudah melakukan aksi ini sebelumnya & berhasil disimpan......</p>
        </div>
        <div class="mt-20" id="message">
            <p class="text-base">Window ini akan tertutup dalam <span id="countdown" class="text-warning">5</span> detik.</p>
        </div>
    </div>
    <script>
        window.onload = function () {
            // Set waktu countdown
            var timeLeft = 5;
            var countdownElement = document.getElementById('countdown');

            // Update setiap 1 detik
            var interval = setInterval(function () {
                // Kurangi waktu setiap detik
                timeLeft--;

                // Update teks countdown
                countdownElement.textContent = timeLeft;

                // Jika waktu habis (0), tutup jendela
                if (timeLeft < 0) {
                    clearInterval(interval); // Hentikan interval
                    window.close(); // Tutup jendela
                }
            }, 1000); // 1000 ms = 1 detik
        };
    </script>
</body>
</html>
