<?php

namespace App\Models\Budgeting;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetAllocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'budget_allocation_no',
        'department_id',
        'description',
        'total_amount',
        'allocated_by'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function list()
    {
        return $this->hasMany(BudgetList::class, 'budget_allocation_no');
    }

    public function Purchase()
    {
        return $this->hasMany(Purchase::class);
    }
}
