<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UpdateCitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('paciente')->check() || 
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
            'paciente_id' => ['sometimes', 'exists:pacientes,id'],
            'medico_id' => ['sometimes', 'exists:medicos,id'],
            'motivo' => ['sometimes', 'string', 'min:5', 'max:500'],
            'fecha_hora' => ['sometimes', 'date', 'after:now'],
            'estado' => ['sometimes', 'string', 'in:pendiente,confirmada,cancelada,reprogramada,asistida'],
            'urgente' => ['sometimes', 'boolean'],
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
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'medico_id.exists' => 'El médico seleccionado no existe',
            'motivo.min' => 'El motivo debe tener al menos 5 caracteres',
            'motivo.max' => 'El motivo no puede exceder los 500 caracteres',
            'fecha_hora.date' => 'La fecha y hora debe ser una fecha válida',
            'fecha_hora.after' => 'La fecha y hora debe ser posterior a la actual',
            'estado.in' => 'El estado seleccionado no es válido',
        ];
    }
}