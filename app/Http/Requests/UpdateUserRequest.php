<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public function rules(): array
    {
        $userId = $this->route('pengguna')?->id;

        return [
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'username'     => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($userId)],
            'email'        => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($userId)],
            'password'     => ['nullable', 'confirmed', Password::min(8)],
            'role'         => ['required', 'in:admin,mentor,peserta_didik'],
            'status'       => ['required', 'in:aktif,nonaktif'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'username.unique'       => 'Username sudah digunakan.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.confirmed'    => 'Konfirmasi password tidak sesuai.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Hanya hash password jika diisi
        if ($this->filled('password')) {
            $this->merge([
                'password' => bcrypt($this->password),
            ]);
        }
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated($key, $default);

        // Hapus password dari data jika kosong
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
