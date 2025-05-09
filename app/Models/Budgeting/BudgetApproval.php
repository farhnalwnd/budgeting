<?php

namespace App\Models\Budgeting;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BudgetApproval extends Model
{
    use HasFactory;
    protected $fillable = [
        'budget_req_no',
        'nik',
        'status',
        'feedback',
        'token'
    ];

    public function request()
    {
        return $this->belongsTo(BudgetRequest::class, 'budget_req_no');
    }
        public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }
}
