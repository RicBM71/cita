<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


   public function rules()
   {

       $rules = [
           'name'      => 'required',
           'username'  => ['min:6','required', Rule::unique('users')],
           'email'     => ['nullable',Rule::requiredIf($this->id <= 2),'email', Rule::unique('users')],
       ];

      return $rules;
   }

   public function messages()
   {
       return [
            'unique'               => 'El :attribute ya ha sido registrado.',
       ];
   }
}
