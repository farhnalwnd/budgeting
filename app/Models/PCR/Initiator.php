<?php

namespace App\Models\PCR;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Initiator extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'initiators';
    protected $connection = 'mysql_pcr';


    public function user()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'user_id', 'id');
    }

    public function pcrs()
    {
        return $this->hasMany(PCR::class, 'initiator_id', 'id');
    }

    public function initiatorApprovals()
    {
        return $this->hasMany(InitiatorApproval::class, 'initiator_id', 'id');
    }
}
