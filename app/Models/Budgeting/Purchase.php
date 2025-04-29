<?php

namespace App\Models\Budgeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsDecimal;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

        protected $fillable = [
        'item_name',
        'amount',
        'quanitity',
        'total_amount',
        'remarks'
    ];

    protected $casts = [
        'amount' =>'float',
        'total_amount' =>'float',
        'quantity' => 'integer'
    ];

    // Helper untuk konversi Rupiah ke decimal
    public static function parseRupiah($rupiah)
    {
        return (float) preg_replace('/[^0-9]/', '', $rupiah);
    }
}
