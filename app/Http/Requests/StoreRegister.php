<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class StoreRegister extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'nik'          => ['required', 'string', 'size:16', 'unique:users,nik'],
            'email'        => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'     => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nik.required'          => 'NIK wajib diisi.',
            'nik.size'              => 'NIK harus berjumlah 16 digit.',
            'nik.unique'            => 'NIK sudah terdaftar.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.confirmed'    => 'Konfirmasi password tidak sesuai.',
        ];
    }

}
