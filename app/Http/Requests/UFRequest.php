<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UFRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {

        $ufMinusculas = [];
        $ufMaiusculas = [
            'AL',
            'AP',
            'AM',
            'BA',
            'CE',
            'DF',
            'ES',
            'GO',
            'MA',
            'MT',
            'MS',
            'MG',
            'PA',
            'PB',
            'PR',
            'PE',
            'PI',
            'RJ',
            'RN',
            'RS',
            'RO',
            'RR',
            'SC',
            'SP',
            'SE',
            'TO',
        ];

        foreach ($ufMaiusculas as $uf) {
            $ufMinusculas[] = strtolower($uf);
        }

        $ufs = array_merge($ufMaiusculas, $ufMinusculas);

        return [
            'uf' => ['required', Rule::in($ufs)],
        ];
    }

    public function failedValidation(Validator $validador): void
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'data' => $validador->errors()
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }

    public function messages(): array
    {
        return [
            'uf.required' => 'É necessário enviar :attribute.',
            'uf.in' => ':attribute não consta na lista de valores válidos.',
        ];
    }
}
