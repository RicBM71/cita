<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $connection = 'mysql2';
    protected $fillable   = [
        'lim_efe', 'lim_efe_nores', 'pie_rebu1', 'img1', 'img2', 'carpeta_docs', 'username', 'ult_fecha_asigna_bono',
    ];
}
