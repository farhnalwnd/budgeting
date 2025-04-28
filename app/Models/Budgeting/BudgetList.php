<?php

namespace App\Models\Budgeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetList extends Model
{
    use HasFactory;
    protected $fillable = [
        'budget_allocation_no',
        'name',
        'category_id',
        'quantity',
        'um',
        'default_amount',
        'total_amount'
    ];

    public function allocation()
    {
        return $this->belongsTo(BudgetAllocation::class);
    }
    
    public function category()
    {
        return $this->belongsTo(CategoryMaster::class);
    }
}
