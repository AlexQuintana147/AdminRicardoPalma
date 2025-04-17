<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReprogramarCitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('paciente')->check() || 
               Auth::guard('medico')->check() || 
               Auth::guard('recepcionista')->check() || 
               Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha_hora' => ['required', 'date', 'after:now'],
            'motivo_reprogramacion' => ['sometimes', 'string', 'max:500'],
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
            'fecha_hora.required' => 'La nueva fecha y hora es obligatoria',
            'fecha_hora.date' => 'La fecha y hora debe ser una fecha válida',
            'fecha_hora.after' => 'La fecha y hora debe ser posterior a la actual',
            'motivo_reprogramacion.max' => 'El motivo de reprogramación no puede exceder los 500 caracteres',
        ];
    }
}