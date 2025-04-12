<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class maisonReqest extends FormRequest
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
            'nom_proprietaire' => ['bail','required'],
            'nom_maison' => ['bail','required', 'string', 'max:255'],
            'quartier' => ['bail','required', 'string', 'max:255'],
            'nombre_chambre' => ['bail','required', 'max:255'],
        ];
    }
}
