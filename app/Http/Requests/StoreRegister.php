<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRegister extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_lengkap'         => ['required', 'string', 'max:100'],
            'nik'                  => ['required', 'string', 'size:16', 'unique:users,nik'],
            'email'                => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'             => ['required', 'confirmed', Password::min(8)],
            'program_pelatihan_id' => ['required', 'exists:program_pelatihans,id'],
            'jenis_kelas_id'       => ['required', 'exists:jenis_kelas,id'],
            'durasi_pelatihan'     => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required'         => 'Nama lengkap wajib diisi.',
            'nik.required'                  => 'NIK wajib diisi.',
            'nik.size'                      => 'NIK harus berjumlah 16 digit.',
            'nik.unique'                    => 'NIK sudah terdaftar.',
            'email.unique'                  => 'Email sudah terdaftar.',
            'password.confirmed'            => 'Konfirmasi password tidak sesuai.',
            'program_pelatihan_id.required' => 'Program pelatihan wajib dipilih.',
            'program_pelatihan_id.exists'   => 'Program pelatihan tidak valid.',
            'jenis_kelas_id.required'       => 'Jenis kelas wajib dipilih.',
            'jenis_kelas_id.exists'         => 'Jenis kelas tidak valid.',
            'durasi_pelatihan.required'     => 'Durasi pelatihan wajib dipilih.',
        ];
    }
}
