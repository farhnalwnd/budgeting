<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://github.com/laravel/framework/actions">
        <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
    </a>
</p>

## Tentang Laravel

Laravel adalah framework aplikasi web dengan sintaks yang ekspresif dan elegan. Kami percaya bahwa pengembangan harus menjadi pengalaman yang menyenangkan dan kreatif agar benar-benar memuaskan. Laravel menghilangkan rasa sakit dalam pengembangan dengan memudahkan tugas-tugas umum yang digunakan dalam banyak proyek web, seperti:

- [Mesin routing yang sederhana dan cepat](https://laravel.com/docs/routing).
- [Kontainer injeksi dependensi yang kuat](https://laravel.com/docs/container).
- Banyak backend untuk penyimpanan [session](https://laravel.com/docs/session) dan [cache](https://laravel.com/docs/cache).
- [ORM database](https://laravel.com/docs/eloquent) yang ekspresif dan intuitif.
- [Migrasi skema](https://laravel.com/docs/migrations) yang agnostik terhadap database.
- [Pemrosesan pekerjaan latar belakang](https://laravel.com/docs/queues) yang kuat.
- [Penyiaran acara real-time](https://laravel.com/docs/broadcasting).

## Instalasi

Ikuti langkah-langkah ini untuk mengatur proyek di mesin lokal Anda.

### 1. Prasyarat

Pastikan Anda telah menginstal:

- **Git**: Instal dari [sini](https://git-scm.com/downloads).
- **PHP**: Pastikan PHP 7.3 atau lebih tinggi telah diinstal. Anda dapat mengunduhnya dari [sini](https://www.php.net/downloads).
- **Composer**: Instal dari [sini](https://getcomposer.org/download/).
- **Node.js & npm**: Instal dari [sini](https://nodejs.org/).
- **XAMPP**: Instal XAMPP, yang mencakup Apache, MySQL, dan PHP. Anda dapat mengunduhnya dari [sini](https://www.apachefriends.org/index.html).

### 2. Mengkloning Repository dan Mengatur Proyek

1. **Klon Repository ke Mesin Lokal Anda:**
    ```bash
    git clone https://github.com/aderusmana22/template_smii.git
    cd template_smii
    ```

2. **Menginstal Dependensi:**
    ```bash
    composer install
    npm install
    ```

3. **Pengaturan Lingkungan:**
    ```bash
    cp .env.example .env
    ```

4. **Menghasilkan Kunci Aplikasi:**
    ```bash
    php artisan key:generate
    ```

5. **Pengaturan Database:**
    ```bash
    php artisan migrate
    ```

6. **Seed Database:**
    ```bash
    php artisan db:seed
    ```

7. **Memulai Server Pengembangan:**
    ```bash
    php artisan serve
    ```

Sekarang Anda siap untuk mulai mengembangkan dengan Laravel!
