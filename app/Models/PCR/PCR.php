<?php

namespace App\Models\PCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PCR extends Model
{
    use HasFactory;
    protected $table = 'pcrs';

    protected $guarded = ['id'];

    protected $connection = 'mysql_pcr';

    public function initiatorApprovals()
    {
        return $this->hasMany(InitiatorApproval::class, 'pcr_id', 'id');
    }

    public function pccApprovals()
    {
        return $this->hasMany(PccApproval::class, 'pcr_id', 'id');
    }

    public function pcrProducts()
    {
        return $this->hasMany(PcrProduct::class, 'pcr_id', 'id');
    }

    public function product()
    {
        return $this->belongsToMany(Product::class, 'pcr_products', 'pcr_id', 'product_id');
    }

    public function natureOfChanges()
    {
        return $this->belongsToMany(NatureOfChange::class, 'pcr_nature_of_changes', 'pcr_id', 'nature_of_change_id');
    }

    public function pcrDecision()
    {
        return $this->hasOne(PcrDecision::class, 'pcr_id', 'id');
    }

    public function pcrRevisions()
    {
        return $this->hasMany(PcrRevision::class);
    }


}
