<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $connection = 'mysql2';

    protected $fillable = [

        'nombre', 'nombre_web', 'nombre_reducido', 'importe', 'importe_reducido', 'precio_coste', 'duracion_manual',
        'duracion_aparatos', 'edad', 'tpv', 'inventario', 'activo', 'username', 'bono', 'iva_id',

    ];

    public function iva()
    {
        return $this->belongsTo(Iva::class);
    }

    public static function selTratamientos($con_bono = null)
    {

        return Tratamiento::select('id AS value', 'nombre_web AS text', 'duracion_manual', 'duracion_aparatos')
            ->where('activo', true)
            ->where('web', true)
            ->orderBy('nombre_web', 'asc')
            ->get();

    }

}
