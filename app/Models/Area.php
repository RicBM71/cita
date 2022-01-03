<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $connection = 'mysql2';

    protected $fillable = [

        'nombre', 'hora1', 'hora2', 'tarde', 'activo', 'frecuencia', 'username', 'dias_online', 'bloqueo_citas_online', 'bloqueo_minutos',

    ];

    public static function selAreas()
    {

        return Area::select('id AS value', 'nombre AS text')
            ->get();

    }

}
