<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    public function rqmMstr()
    {
        return $this->hasMany(RequisitionMaster::class);
    }
}
