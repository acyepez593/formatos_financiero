<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class ExpedienteRequest extends FormRequest
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
            'victima' => 'required|max:200',
            'id_de_proteccion' => 'required|max:150',
            'proteccion_id' => 'required|exists:protecciones,id',
            'peticionario_notificado' => 'required|max:500',
            'nro_oficio_notificacion' => 'required|max:100',
            'fecha_notificacion' => 'required',
            'responsables_ids' => 'required',
            'fecha_maxima_respuesta' => 'required',
            'documentacion_solicitada' => 'required|max:5000',
            'observaciones' => 'max:5000',

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
            'victima.required' => 'El campo :attribute es requerido',
            'victima.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'id_de_proteccion.required' => 'El campo :attribute es requerido',
            'id_de_proteccion.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'proteccion_id.required' => 'El campo :attribute es requerido',
            'proteccion_id.exists' => 'El campo :attribute no existe en el catálogo de Protecciones',
            'peticionario_notificado.required' => 'El campo :attribute es requerido',
            'peticionario_notificado.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'nro_oficio_notificacion.required' => 'El campo :attribute es requerido',
            'nro_oficio_notificacion.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'fecha_notificacion.required' => 'El campo :attribute es requerido',
            'responsables_ids.required' => 'El campo :attribute es requerido',
            'fecha_maxima_respuesta.required' => 'El campo :attribute es requerido',
            'documentacion_solicitada.required' => 'El campo :attribute es requerido',
            'documentacion_solicitada.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'tipo_respuesta_id.exists' => 'El campo :attribute no existe en el catálogo Tipos Respuesta',
            'observaciones.exists' => 'El campo :attribute no existe en el catálogo Tipos Respuesta',
            'estado_id.required' => 'El campo :attribute es requerido',
            'estado_id.max' => 'La longitud máxima del campo :attribute es :max caracteres',
        ];
    }
}