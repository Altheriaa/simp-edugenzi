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
            'nik'          => ['required', 'string', 'max:16', Rule::unique('users', 'nik')->ignore($userId)],
            'no_hp'        => ['required', 'string', 'max:15'],
            'alamat'       => ['required', 'string', 'max:255'],
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
            'nik.required'          => 'NIK wajib diisi.',
            'nik.unique'            => 'NIK sudah terdaftar.',
            'no_hp.required'        => 'No HP wajib diisi.',
            'alamat.required'       => 'Alamat wajib diisi.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.confirmed'    => 'Konfirmasi password tidak sesuai.',
        ];
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
