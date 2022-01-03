<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
/**
 * Determine if the user is authorized to make this request.
 *
 * @return bool
 */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'name'     => 'required',
            'lastname' => 'nullable',
            'photo'    => ['nullable', 'image', 'max:1024'],
            'blocked'  => 'boolean',
            // 'expira'    => 'required|min:0|max:180',
            // 'fecha_expira'=> 'nullable|date',
            'username' => ['required', 'email', Rule::unique('users')->ignore($this->route('user')->id)],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($this->route('user')->id)],
        ];

        if ($this->filled('password')) { //va a actualizar la password
            $rules['password'] = ['confirmed', 'min:8', 'password'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'unique' => 'El :attribute ya ha sido registrado.',
        ];
    }
}
