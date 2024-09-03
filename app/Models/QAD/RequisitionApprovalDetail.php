<?php

namespace App\Models\QAD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionApprovalDetail extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $primaryKey = 'rqdaNbr';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    public function rqmMstr()
    {
        return $this->belongsTo(RequisitionMaster::class, 'rqdNbr', 'rqmNbr');
    }
}
