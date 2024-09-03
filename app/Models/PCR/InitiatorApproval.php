<?php

namespace App\Models\PCR;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitiatorApproval extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'initiator_approvals';
    protected $connection = 'mysql_pcr';

    public function initiator()
    {
        return $this->belongsTo(Initiator::class, 'initiator_id', 'id');
    }

    public function pcr()
    {
        return $this->belongsTo(PCR::class, 'pcr_id', 'id');
    }

    public function user()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'user_id', 'id');
    }
}
