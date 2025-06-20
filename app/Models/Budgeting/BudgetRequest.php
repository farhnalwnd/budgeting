<?php

namespace App\Models\Budgeting;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'budget_req_no',
        'nik',
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

        public function purchase()
    {
        return $this->hasOne(Purchase::class, 'purchase_no', 'budget_purchase_no');
    }

    public function approval()
    {
        return $this->hasMany(BudgetApproval::class, 'budget_req_no');
    }

}
