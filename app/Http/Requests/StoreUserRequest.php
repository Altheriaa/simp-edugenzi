<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama_lengkap'         => ['required', 'string', 'max:100'],
            'nik'                  => ['required', 'string', 'max:16', 'unique:users,nik'],
            'no_hp'                => ['required', 'string', 'max:15'],
            'alamat'               => ['required', 'string', 'max:255'],
            'email'                => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'             => ['required', 'confirmed', Password::min(8)],
            'role'                 => ['required', 'in:admin,mentor,peserta_didik'],
            'status'               => ['required', 'in:aktif,nonaktif'],
            'program_pelatihan_id' => ['nullable', 'exists:program_pelatihans,id'],
            'jenis_kelas_id'       => ['nullable', 'exists:jenis_kelas,id'],
            'durasi_pelatihan'     => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required'          => 'Nama lengkap wajib diisi.',
            'nik.required'                   => 'NIK wajib diisi.',
            'nik.unique'                     => 'NIK sudah terdaftar.',
            'no_hp.required'                 => 'No HP wajib diisi.',
            'alamat.required'                => 'Alamat wajib diisi.',
            'email.unique'                   => 'Email sudah terdaftar.',
            'password.confirmed'             => 'Konfirmasi password tidak sesuai.',
            'role.in'                        => 'Role tidak valid.',
            'program_pelatihan_id.exists'    => 'Program pelatihan tidak valid.',
            'jenis_kelas_id.exists'          => 'Jenis kelas tidak valid.',
        ];
    }

}
