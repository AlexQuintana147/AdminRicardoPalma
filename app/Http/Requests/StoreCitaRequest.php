<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StoreCitaRequest extends FormRequest
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
            'paciente_id' => ['required_if:guard,recepcionista,admin', 'exists:pacientes,id'],
            'medico_id' => ['required', 'exists:medicos,id'],
            'motivo' => ['required', 'string', 'min:5', 'max:500'],
            'fecha_hora' => ['required', 'date', 'after:now'],
            'urgente' => ['boolean'],
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
            'paciente_id.required_if' => 'El paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'medico_id.required' => 'El médico es obligatorio',
            'medico_id.exists' => 'El médico seleccionado no existe',
            'motivo.required' => 'El motivo de la cita es obligatorio',
            'motivo.min' => 'El motivo debe tener al menos 5 caracteres',
            'motivo.max' => 'El motivo no puede exceder los 500 caracteres',
            'fecha_hora.required' => 'La fecha y hora de la cita son obligatorias',
            'fecha_hora.date' => 'El formato de fecha y hora no es válido',
            'fecha_hora.after' => 'La fecha y hora deben ser posteriores a la actual',
        ];
    }
}