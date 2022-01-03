<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

//use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        //$pwd = new Password;
        return ['required', 'string', Password::min(8)
                    ->uncompromised()
                    ->letters()
                    ->symbols(), 'confirmed'];
    }
}
