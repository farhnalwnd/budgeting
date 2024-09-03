<?php

namespace App\Models\PCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcrDecision extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'pcr_decisions';
    protected $connection = 'mysql_pcr';

    public function pcr()
    {
        return $this->belongsTo(PCR::class, 'pcr_id', 'id');
    }
}
