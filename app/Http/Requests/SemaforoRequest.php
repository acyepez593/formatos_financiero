<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class SemaforoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'color' => 'required|max:50',
            'estado' => 'required|max:100',
            'rango_inicial' => 'required|numeric|integer|min:1',
            'rango_final' => 'required|numeric|integer|gt:rango_inicial',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'color.required' => 'El campo :attribute es requerido',
            'color.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'estado.required' => 'El campo :attribute es requerido',
            'estado.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'rango_inicial.required' => 'El campo :attribute es requerido',
            'rango_inicial.numeric' => 'El campo :attribute debe ser de tipo numérico',
            'rango_inicial.integer' => 'El campo :attribute debe ser de tipo entero',
            'rango_inicial.min' => 'El rango mínimo del campo :attribute es :min',
            'rango_final.required' => 'El campo :attribute es requerido',
            'rango_final.numeric' => 'El campo :attribute debe ser de tipo numérico',
            'rango_final.integer' => 'El campo :attribute debe ser de tipo entero',
            'rango_final.gt' => 'El rango mínimo del campo :attribute debe ser mayor al rango inicial',

        ];
    }
}