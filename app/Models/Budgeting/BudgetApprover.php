<?php

namespace App\Models\Budgeting;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetApprover extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id',
        'nik'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }
}
