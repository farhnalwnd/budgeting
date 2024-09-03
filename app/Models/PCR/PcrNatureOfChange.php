<?php

namespace App\Models\PCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcrNatureOfChange extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'pcr_nature_of_changes';
    protected $connection = 'mysql_pcr';

    public function pcr()
    {
        return $this->belongsTo(PCR::class, 'pcr_id', 'id');
    }

    public function natureOfChange()
    {
        return $this->belongsTo(NatureOfChange::class, 'nature_of_change_id', 'id');
    }
}
