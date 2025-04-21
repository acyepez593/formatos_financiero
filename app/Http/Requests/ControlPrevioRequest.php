<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class ControlPrevioRequest extends FormRequest
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
            'nro_control_previo_y_concurrente' => 'required|max:30',
            'fecha_tramite' => 'required',
            'solicitud_pago' => 'required|max:100',
            'objeto' => 'required|max:200',
            'beneficiario' => 'required|max:200',
            'ruc' => 'required|max:13',
            'mes' => 'required',
            'valor' => 'required'

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
            'nro_control_previo_y_concurrente.required' => 'El campo :attribute es requerido',
            'nro_control_previo_y_concurrente.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'fecha_tramite.required' => 'El campo :attribute es requerido',
            'solicitud_pago.required' => 'El campo :attribute es requerido',
            'solicitud_pago.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'objeto.required' => 'El campo :attribute es requerido',
            'objeto.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'beneficiario.required' => 'El campo :attribute es requerido',
            'beneficiario.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'ruc.required' => 'El campo :attribute es requerido',
            'ruc.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'mes.required' => 'El campo :attribute es requerido',
            'valor.required' => 'El campo :attribute es requerido'
        ];
    }
}