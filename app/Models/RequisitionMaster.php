<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionMaster extends Model
{
    use HasFactory;

    protected $table = 'requisition_masters';
    protected $primaryKey = 'rqmNbr';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    public function rqdDets()
    {
        return $this->hasMany(RequisitionDetail::class, 'rqdNbr', 'rqmNbr');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'rqmVend', 'vd_addr');
    }
}
