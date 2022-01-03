<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    protected $connection = 'mysql2';

    protected $fillable = [
        'nombre', 'importe', 'leyenda', 'username',
    ];

    public static function selIvas()
    {

        return Iva::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();

    }
}
