<?php

namespace App\Models\QAD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['id'];
    protected $connection = 'mysql';

    public function rqmMstr()
    {
        return $this->hasMany(RequisitionMaster::class);
    }
}
