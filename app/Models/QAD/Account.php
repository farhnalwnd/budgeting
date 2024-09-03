<?php

namespace App\Models\QAD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $guarded = ['id'];

}
