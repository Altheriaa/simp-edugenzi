<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSertifikatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'mentor';
    }

    public function rules(): array
    {
        return [
            'peserta_id'       => ['required', 'exists:users,id'],
            'tgl_terbit'       => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'peserta_id.required'   => 'Peserta didik wajib dipilih.',
            'peserta_id.exists'     => 'Peserta didik tidak ditemukan.',
            'nama_program.required' => 'Nama program wajib diisi.',
            'tgl_terbit.required'   => 'Tanggal terbit wajib diisi.',
        ];
    }
}
