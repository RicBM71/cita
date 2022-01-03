<?php

namespace App\Rules\Citas;

use App\Models\Cita;
use Illuminate\Contracts\Validation\Rule;

class HoraUnicaRule implements Rule
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

        if ($this->cita->tune == true) {
            return true;
        }

        $cita_id = $this->cita->id;

        $citas_repetidas = Cita::where('fecha', $this->cita->fecha)
            ->where('hora', $this->cita->hora)
            ->where('facultativo_id', $this->cita->facultativo_id)
            ->where('estado_id', '<>', 4)
            ->when($cita_id > 0, function ($query) use ($cita_id) {
                return $query->where('id', '<>', $cita_id);})
            ->get()
            ->count();

        return $citas_repetidas > 0 ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Hora NO disponible!';
    }
}
