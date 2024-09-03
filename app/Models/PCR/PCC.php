<?php

namespace App\Models\PCR;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PCC extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'pccs';
    protected $connection = 'mysql_pcr';

    public function user()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'user_id', 'id');
    }

    public function pccApprovals()
    {
        return $this->hasMany(PccApproval::class, 'pcc_id', 'id');
    }
}
