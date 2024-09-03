<?php

namespace App\Models\PCR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'products';
    protected $connection = 'mysql_pcr';

    public function pcrProducts()
    {
        return $this->hasMany(PcrProduct::class, 'product_id', 'id');
    }
}
