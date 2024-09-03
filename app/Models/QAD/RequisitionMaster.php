<?php

namespace App\Models\QAD;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RequisitionMaster extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'requisition_masters';
    protected $primaryKey = 'rqmNbr';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $connection = 'mysql';

    protected $guarded = ['id'];

    public function rqdDets()
    {
        return $this->hasMany(RequisitionDetail::class, 'rqdNbr', 'rqmNbr');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'rqmVend', 'vd_addr');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'enterby', 'username');
    }

    public function curr()
    {
        return $this->belongsTo(Currency::class);
    }
}
