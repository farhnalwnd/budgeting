<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    use HasFactory;
    protected $table = 'requisition_details';
    protected $primaryKey = 'rqdNbr';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    public function rqmMstr()
    {
        return $this->belongsTo(RequisitionMaster::class, 'rqdNbr', 'rqmNbr');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'rqdVend', 'vd_addr');
    }
}
