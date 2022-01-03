<?php

namespace App\Models;

use App\Scopes\EmpresaActivaScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bloqueo extends Model
{

    protected $connection = 'mysql2';

    protected $fillable = [
        'empresa_id', 'fecha', 'facultativo_id', 'start', 'end', 'motivo', 'remunerada', 'username',
    ];

    /**
     *
     * Añadimos global scope para filtrado por empresa.
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaActivaScope);
    }

    public function facultativo()
    {
        return $this->belongsTo(Facultativo::class);
    }

    public function getStartAttribute($hora)
    {

        return substr($hora, 0, 5);

    }

    public function getEndAttribute($hora)
    {

        return substr($hora, 0, 5);

    }

    public static function getBloqueosFisio($fecha, $facultativo_id)
    {

        $bloqueos = Bloqueo::whereDate('fecha', $fecha)
            ->join('facultativos', 'facultativo_id', '=', 'facultativos.id')
            ->where('facultativo_id', $facultativo_id)
            ->whereNull('fecha_baja')
            ->orderBy('start')
            ->get();

        if ($bloqueos == null) {
            return array();
        }

        $data = array();
        foreach ($bloqueos as $row) {

            $end = Carbon::parse($row->end)->subMinute()->format('H:i');

            $data[] = [
                'start' => $row->start,
                'end'   => $end,
            ];
        }

        return collect($data);
    }

}
