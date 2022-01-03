<?php

namespace App\Rules\Citas;

use App\Models\Bloqueo;
use Illuminate\Contracts\Validation\Rule;

class HoraRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($fecha, $facultativo_id)
    {
        $this->fecha = $fecha;
        $this->facultativo_id = $facultativo_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $hora)
    {
        $bloqueo = Bloqueo::where('facultativo_id', $this->facultativo_id)
                        ->where('fecha', $this->fecha)
                        ->get();

        if ($bloqueo->count() == 0)
            return true;

        $bloqueo = $bloqueo->first();

        if ($hora >= $bloqueo->start && $hora <= $bloqueo->end)
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Bloqueada';
    }
}
