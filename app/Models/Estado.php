<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $connection = 'mysql2';
    protected $fillable   = ['nombre'];
}
