<?php

namespace App\Models\Budgeting;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'budget_req_no',
        'from_department_id',
        'to_department_id',
        'budget_purchase_no',
        'amount',
        'reason',
        'status',
        'feedback'
    ];

    public function fromDepartment()
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }
    

    public function toDepartment()
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }
}
