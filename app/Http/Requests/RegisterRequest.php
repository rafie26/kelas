<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $rules = [
            'username' => 'required|string|max:50|unique:dataadmin,username',
            'password' => 'required|string|min:3',
            'role' => 'required|string|in:admin,guru,siswa',
        ];

        // Add conditional validation based on role
        if ($this->input('role') === 'siswa') {
            $rules['nama'] = 'required|string|max:100';
            $rules['tb'] = 'required|numeric|min:30|max:250';
            $rules['bb'] = 'required|numeric|min:10|max:200';
        }

        if ($this->input('role') === 'guru') {
            $rules['nama'] = 'required|string|max:100';
            $rules['mapel'] = 'required|string|max:100';
        }

        return $rules;
    }
}
