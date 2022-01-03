<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Horario extends Model
{
    protected $connection = 'mysql2';
    protected $fillable   = [

        'fecha', 'facultativo_id',
        'inim_1', 'finm_1',
        'init_1', 'fint_1', 'inim_2', 'finm_2', 'init_2', 'fint_2', 'inim_3', 'finm_3', 'init_3', 'fint_3', 'inim_4', 'finm_4', 'init_4', 'fint_4',
        'inim_5', 'finm_5', 'init_5', 'fint_5', 'inim_6', 'finm_6', 'init_6', 'fint_6', 'inim_0', 'finm_0', 'init_0', 'fint_0', 'username',

    ];

    protected $dates = ['fecha'];

    // protected $dateFormat = 'H:i';

    protected $casts = [
        'fecha' => 'date:Y-m-d',
        // 'inim_1' => 'time:h:m',
    ];

    public function facultativo()
    {
        return ($this->belongsTo(Facultativo::class));
    }

    public static function getHorario($fecha, $facultativo_id)
    {

        $dt = Carbon::parse($fecha);

        $dw = $dt->dayOfWeek;

        $horario = Horario::select(DB::raw('inim_' . $dw . ' AS ini_m, finm_' . $dw . ' AS fin_m, init_' . $dw . ' AS ini_t, fint_' . $dw . ' AS fin_t'))
            ->join('facultativos', 'facultativo_id', '=', 'facultativos.id')
            ->where('facultativo_id', $facultativo_id)
            ->whereDate('fecha', '<=', $fecha)
            ->whereNull('fecha_baja')
            ->orderBy('fecha', 'desc')
            ->first();

        if ($horario == null) {
            $horario = Horario::select(DB::raw('inim_' . $dw . ' AS ini_m, finm_' . $dw . ' AS fin_m, init_' . $dw . ' AS ini_t, fint_' . $dw . ' AS fin_t'))
                ->where('facultativo_id', 1)
                ->whereDate('fecha', '<=', $fecha)
                ->orderBy('fecha', 'desc')
                ->first();
        }

        $data = [
            'start_m' => $horario->ini_m,
            'end_m'   => $horario->fin_m,
            'start_t' => $horario->ini_t,
            'end_t'   => $horario->fin_t,
        ];

        return $data;

    }

}
