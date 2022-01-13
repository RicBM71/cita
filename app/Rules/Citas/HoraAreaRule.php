<?php

namespace App\Rules\Citas;

use Carbon\Carbon;
use App\Models\Area;
use Illuminate\Contracts\Validation\Rule;

class HoraAreaRule implements Rule
{
    protected $cita;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($cita)
    {
        $this->cita = $cita;
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $area = Area::findOrFail($this->cita->area_id);

        $hora       = Carbon::parse($this->cita->fecha . ' ' . $value . ':00');
        $hora_tarde = Carbon::parse($this->cita->fecha . ' ' . $area->tarde);

        $fin_tratamiento = $hora->addMinutes($this->cita->duracion_total);

        //    $k1 = $hora->format('d-m-Y H:i:s');
        //    $k2 = $hora_tarde->format('d-m-Y H:i:s');

        if ($hora <= $hora_tarde) {
            $hora_cierre = Carbon::parse($this->cita->fecha . ' ' . $area->hora2)->addMinutes($area->frecuencia);
        } else {
            $hora_cierre = Carbon::parse($this->cita->fecha . ' ' . $area->hora4)->addMinutes($area->frecuencia);
        }

        //    $k3 = $hora_cierre->format('d-m-Y H:i:s');
        //    $k4 = $fin_tratamiento->format('d-m-Y H:i:s');
        return ($fin_tratamiento <= $hora_cierre);

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Excede hora cierre';
    }
}
