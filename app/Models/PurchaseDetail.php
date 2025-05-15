<?php

namespace App\Models;

use App\Models\Budgeting\Purchase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    public function master(){
        return $this->belongsTo(Purchase::class, 'purchase_no', 'purchase_no');
    }
}
