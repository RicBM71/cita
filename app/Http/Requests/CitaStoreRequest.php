<?php

namespace App\Http\Requests;

use App\Rules\Citas\HoraRule;
use App\Rules\Citas\FestivoRule;
use App\Rules\Citas\HoraAreaRule;
use App\Rules\Citas\HoraUnicaRule;
use Illuminate\Foundation\Http\FormRequest;

class CitaStoreRequest extends FormRequest
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
        return [
            'empresa_id'     => ['required', 'integer'],
            'area_id'        => ['required', 'integer'],
            'tratamiento_id' => ['required', 'integer'],
            'facultativo_id' => ['required', 'integer'],
            'duracion_total' => ['required', 'integer'],
            'area_id'        => ['required', 'integer'],
            'fecha'          => ['required', 'date', new FestivoRule()],
            'hora'           => ['required', new HoraRule($this->fecha, $this->facultativo_id), new HoraUnicaRule($this), new HoraAreaRule($this)],
        ];

    }
}
