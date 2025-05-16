<?php

namespace App\Models\Budgeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsDecimal;
use Illuminate\Database\Eloquent\Model;
use App\Models\department;
use App\Models\PurchaseDetail;

class Purchase extends Model
{
    use HasFactory;

        protected $fillable = [
        'item_name',
        'purchase_no',
        'amount',
        'department_id',
        'quanitity',
        'status',
        'total_amount',
        'actual_amount',
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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function BudgetAllocation(){
        return $this->belongsTo(BudgetAllocation::class);
    }

    public function budgetRequest()
    {
        return $this->hasMany(BudgetRequest::class, 'budget_purchase_no', 'purchase_no');
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_no', 'purchase_no');
    }



    //     protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->document_number = generateDocumentNumber();
    //     });
    // }
}
