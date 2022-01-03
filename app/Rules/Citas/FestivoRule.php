<?php

namespace App\Rules\Citas;

use App\Models\Festivo;
use Illuminate\Contracts\Validation\Rule;

class FestivoRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $fecha
     * @return bool
     */
    public function passes($attribute, $fecha)
    {

        $f = Festivo::where('fecha', $fecha)->get();

        return !($f->count() > 0);

        try {

        } catch (\Exception $th) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Es festivo';
    }
}
