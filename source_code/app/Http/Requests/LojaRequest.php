<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LojaRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            "cnpj_cliente" => "required",
            "nome_loja" => "required",
        ];
        return $rules;

    }

    public function messages()
    {
        return [
           "cnpj_cliente.required" => "O campo cnpj master é obrigatório!",
           "nome_loja" => "O campo nome loja é obrigatório!"
        ];
    }
}
