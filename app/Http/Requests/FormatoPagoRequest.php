<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class FormatoPagoRequest extends FormRequest
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
            'control_previo_id' => 'required',
            'estructura_formato_pago_id' => 'required',
            'datos' => 'required'
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
            'control_previo_id.required' => 'El campo :attribute es requerido',
            'estructura_formato_pago_id.required' => 'El campo :attribute es requerido',
            'datos.required' => 'El campo :attribute es requerido'
        ];
    }
}