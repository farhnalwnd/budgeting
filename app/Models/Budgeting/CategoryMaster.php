<?php

namespace App\Models\Budgeting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function list()
    {
        return $this->hasMany(BudgetList::class, 'category_id');
    }

    public function category(){
        return $this->hasMany(Purchase::class, 'category_id');
    }
}
