<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
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
            'email' => 'sometimes|required|string|email|max:255|unique:admins,email,' . $this->admin->id,
            'password' => 'sometimes|required|string|min:8',
            'foto' => 'nullable|image|max:2048',
        ];
    }
}
