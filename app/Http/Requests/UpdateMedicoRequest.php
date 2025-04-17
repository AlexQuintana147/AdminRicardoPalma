<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicoRequest extends FormRequest
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
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:medicos,email,' . $this->medico->id,
            'password' => 'sometimes|required|string|min:8',
            'especialidad' => 'sometimes|required|string|max:255',
            'horario_inicio' => 'sometimes|required|date_format:H:i',
            'horario_fin' => 'sometimes|required|date_format:H:i|after:horario_inicio',
            'foto' => 'nullable|image|max:2048',
        ];
    }
}