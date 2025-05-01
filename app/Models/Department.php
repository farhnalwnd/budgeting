<?php

namespace App\Models;

use App\Models\Budgeting\BudgetAllocation;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Budgeting\Purchase;

class Department extends Model implements Wallet
{

    use HasFactory, HasWallet;
    protected $connection = 'mysql';
    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    
    public function budgetAllocation()
    {
        return $this->hasMany(BudgetAllocation::class, 'department_id');
    }

    public function purchases()
{
    return $this->hasMany(Purchase::class);
}

    public static function boot()
{
    parent::boot();

    self::creating(function ($department) {
        $department->department_slug = Str::slug($department->department_name);
    });
}
}
