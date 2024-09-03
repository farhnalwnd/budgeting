<?php

namespace App\Models\QAD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $connection = 'mysql';

}
