<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class EstructuraFormatoPagoRequest extends FormRequest
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
            'descripcion' => 'required|max:500',
            'tipo_formato_id' => 'required',
            'estructura' => 'required|max:2000'
        ];
    }
}