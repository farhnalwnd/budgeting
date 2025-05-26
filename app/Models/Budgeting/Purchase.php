<?php

namespace App\Models\Budgeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsDecimal;
use Illuminate\Database\Eloquent\Model;
use App\Models\department;
use App\Models\PurchaseDetail;
use App\Models\Budgeting\CategoryMaster;

class Purchase extends Model
{
    use HasFactory;

        protected $fillable = [
        'purchase_no',
        'department_id',
        'status',
        'grand_total',
        'category_id',
        'PO',
        'actual_amount',
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

    public function detail(){
        return $this->hasMany(PurchaseDetail::class, 'purchase_no', 'purchase_no');
    }

    public function budgetRequest()
    {
        return $this->hasOne(BudgetRequest::class, 'budget_purchase_no', 'purchase_no');
    }
    public function category()
    {
        return $this->belongsTo(CategoryMaster::class);
    }



    //     protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->document_number = generateDocumentNumber();
    //     });
    // }
}
