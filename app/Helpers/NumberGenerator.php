<?php
// app/Helpers/NumberGenerator.php

use App\Models\Budgeting\Purchase;
use Carbon\Carbon;

if (!function_exists('generateDocumentNumber')) {

    function generateDocumentNumber($departmentName)
    {
        $now = Carbon::now();
        $year = $now->format('Y'); // Tahun 4 digit

        // Normalisasi nama department
        $cleaned = preg_replace('/[^a-zA-Z\s]/', '', $departmentName); // hilangkan karakter seperti &
        $words = array_values(array_filter(explode(' ', strtolower($cleaned)))); // pecah jadi array dan filter kosong

        // Ambil prefix: 2 huruf dari kata pertama + 1 huruf dari kata valid selanjutnya
        $prefix = '';
        if (count($words) >= 1) {
            $prefix .= substr($words[0], 0, 2); // 2 huruf dari kata pertama
        }
        if (count($words) >= 2) {
            // Cari kata kedua yang valid (bukan & atau kosong)
            for ($i = 1; $i < count($words); $i++) {
                if (strlen($words[$i]) > 0) {
                    $prefix .= substr($words[$i], 0, 1); // 1 huruf dari kata valid
                    break;
                }
            }
        }

        $prefix = strtoupper($prefix);

        // Ambil dokumen terakhir dengan pola prefix/year/nomor
        $lastDocument = Purchase::where('purchase_no', 'like', "$prefix/$year/%")
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastDocument) {
            preg_match('/^' . preg_quote($prefix, '/') . '\/' . $year . '\/(\d{4})$/', $lastDocument->purchase_no, $matches);
            $lastNumber = (int) ($matches[1] ?? 0);
        }

        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "$prefix/$year/$nextNumber";
    }
}

if (!function_exists('generateMultipleDocumentNumbers')) {
    function generateMultipleDocumentNumbers(int $count, $prefix = 'SURAT', $type = 'CAPEX')
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('y');

        $lastDocument = Purchase::where('purchase_no', 'like', "$prefix%/$type/$month/$year")
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastDocument) {
            preg_match('/^' . preg_quote($prefix) . '-(\d+)/', $lastDocument->purchase_no, $matches);
            $lastNumber = (int) ($matches[1] ?? 0);
        }

        $numbers = [];
        for ($i = 1; $i <= $count; $i++) {
            $nextNumber = str_pad($lastNumber + $i, 3, '0', STR_PAD_LEFT);
            $numbers[] = "$prefix-$nextNumber/$type/$month/$year";
        }

        return $numbers;
    }
}
