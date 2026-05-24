<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                // 'name'     => 'required|string|max:255',
                // 'email'    => 'required|string|email|unique:users,email',
                // 'password' => 'required|string|min:8',
                'nama'     => 'required|string|max:255',
                'jabatan'  => 'required|string|max:255',
                'alamat'   => 'required|string',
                'no_hp'    => 'required|string|max:20',
                'role'     => 'required|in:admin,owner,karyawan',
                'gaji'     => 'required|integer',
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:8',
            ];
        }

        // Default / Method lain (misal: PUT/PATCH)
        return [
            'nama'     => 'sometimes|required|string|max:255',
            'jabatan'  => 'sometimes|required|string|max:255',
            'alamat'   => 'sometimes|required|string',
            'no_hp'    => 'sometimes|required|string|max:20',
            'role'     => 'sometimes|required|in:admin,owner,karyawan',
            'gaji'     => 'sometimes|required|integer',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $this->route('id') . ',id_user',
            'password' => 'sometimes|required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama is required.',
            'jabatan.required' => 'Jabatan is required.',
            'alamat.required' => 'Alamat is required.',
            'no_hp.required' => 'No HP is required.',
            'role.required' => 'Role is required.',
            'gaji.required' => 'Gaji is required.',
            'username.required' => 'Username is required.',
            'password.required' => 'Password is required.',
        ];
    }
}
