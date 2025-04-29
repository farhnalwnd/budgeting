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
        'budget_no',
        'amount',
        'quanitity',
        'total_amount',
        'remarks'
    ];

    protected $casts = [
        'amount' =>'float',
        'total_amount' =>'float',
        'quanitity' => 'integer'
    ];

    // Helper untuk konversi Rupiah ke decimal
    public static function parseRupiah($rupiah)
    {
        return (float) preg_replace('/[^0-9]/', '', $rupiah);
    }

    //     protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->document_number = generateDocumentNumber();
    //     });
    // }
}
