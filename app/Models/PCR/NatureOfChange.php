<?php

namespace App\Models\PCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NatureOfChange extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'nature_of_changes';
    protected $connection = 'mysql_pcr';

    
    public function pcrNatureOfChanges()
    {
        return $this->hasMany(PcrNatureOfChange::class, 'nature_of_change_id', 'id');
    }
}
