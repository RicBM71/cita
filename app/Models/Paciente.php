<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paciente extends Model
{

    use HasFactory;

    protected $connection = 'mysql2';

    protected $fillable = [

        'nombre',
        'apellidos',
        'email',
        'username',

    ];

    protected $appends = ['nom_ape', 'edad', 'cumple'];

    public function getNomApeAttribute()
    {

        return $this->nombre . " " . $this->apellidos;

    }

    public function getEdadAttribute()
    {

        if ($this->fecha_nacimiento == null) {
            return 0;
        }

        return Carbon::parse($this->fecha_nacimiento)->age;
    }

    public function getCumpleAttribute()
    {

        $cumple = '';

        $today = Carbon::today();

        if (substr($this->fecha_nacimiento, 5, 6) == date('m-d')) {
            $cumple = 'CUMPLEAÑOS!';
        } else if (substr($this->fecha_nacimiento, 5, 2) == date('m')) {

            $fecha_cumple = $today->format('Y') . '-' . substr($this->fecha_nacimiento, 5, 6);
            $fecha_cumple = Carbon::parse($fecha_cumple);

            $dif = $today->diffInDays($fecha_cumple);

            if ($dif <= -7 && $dif >= 7) {
                $cumple = 'Cumple el ' . (int) substr($this->fecha_nacimiento, 8, 2);
            }

        }

        return $cumple;
    }

    public function bonos()
    {
        return $this->hasMany(Pacbono::class);
    }

    public function citas()
    {
        return $this->hasMany(Adjunto::class);
    }

    public static function getPrecioTratamiento($paciente_id, $tratamiento_id)
    {

        if ($tratamiento_id == null) {
            return 0;
        }

        $paciente = Paciente::findOrFail($paciente_id);

        $tratamiento = Tratamiento::with('iva')->findOrFail($tratamiento_id);

        $paciente->tarifa_reducida == true ? $base = $tratamiento->importe_reducido : $base = $tratamiento->importe;

        // calcular precio en base a descuentos
        if ($paciente->descuento != 0) { // hay descuento
            if ($paciente->porcentual) { // es %
                $dto    = round($base * (float) $paciente->descuento / 100, 2);
                $precio = $base - $dto;
            } else { // es directo
                $precio = $base - (float) $paciente->descuento;
            }
        } else {
            $precio = $base;
        }

        return array('bono' => null,
            'importe'           => $precio,
            'iva'               => $tratamiento->iva->importe,
            'importe_ponderado' => $precio);

    }

}
