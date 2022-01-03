<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Paciente;
use App\Models\Tratamiento;
use Illuminate\Database\Eloquent\Model;

class Pacbono extends Model
{
    protected $connection = 'mysql2';
    protected $fillable   = [

        'paciente_id',
        'bono',
        'fecha',
        'sesiones',
        'tratamiento_id',
        'importe',
        'caducidad',
        'caducado',
        'texto',
        'username',

    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }

    public static function getSesionesBono($paciente_id, $numero_bono)
    {

        $data = [
            'numero_bono' => 0,
            'sesiones'    => 0,
            'resto'       => 0];

        if ($numero_bono == null || $numero_bono == 0) {
            return $data;
        }

        $bono = Pacbono::where('paciente_id', $paciente_id)
            ->where('bono', $numero_bono)->get()->first();

        if ($bono == null) {
            return $data;
        }

        $usadas = Cita::where('citas.bono', $numero_bono)
            ->where('citas.fecha', '>=', $bono->fecha)
            ->where('citas.estado_id', '<>', '4')
            ->count();

        // if ($resto == null)
        //     return $data;

        return [
            'numero_bono' => $bono->bono,
            'sesiones'    => $bono->sesiones,
            'usadas'      => $usadas,
            'resto'       => ($bono->sesiones - $usadas),
            'caducidad'   => Carbon::parse($bono->fecha)->addDays($bono->caducidad)->isoFormat('M/YY'),
        ];

    }

}
