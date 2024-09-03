<?php

namespace App\Models\PCR;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PccApproval extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'pcc_approvals';
    protected $connection = 'mysql_pcr';

    public function pcr()
    {
        return $this->belongsTo(PCR::class, 'pcr_id', 'id');
    }

    public function pcc()
    {
        return $this->belongsTo(PCC::class, 'pcc_id', 'id');
    }

    public function user()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'user_id', 'id');
    }
}
