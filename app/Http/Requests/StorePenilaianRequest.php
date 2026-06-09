<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePenilaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'mentor';
    }

    public function rules(): array
    {
        return [
            'peserta_id' => ['required', 'exists:users,id'],
            'bulan'      => ['required', 'string', 'max:20'],
            'tahun'      => ['required', 'integer', 'min:2020', 'max:2099'],
            'm1_kls'     => ['required', 'integer', 'min:2', 'max:5'],
            'm1_pr'      => ['required', 'integer', 'min:2', 'max:5'],
            'm2_kls'     => ['required', 'integer', 'min:2', 'max:5'],
            'm2_pr'      => ['required', 'integer', 'min:2', 'max:5'],
            'm3_kls'     => ['required', 'integer', 'min:2', 'max:5'],
            'm3_pr'      => ['required', 'integer', 'min:2', 'max:5'],
            'm4_kls'     => ['required', 'integer', 'min:2', 'max:5'],
            'm4_pr'      => ['required', 'integer', 'min:2', 'max:5'],
            'catatan'    => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'peserta_id.required' => 'Peserta didik wajib dipilih.',
            'peserta_id.exists'   => 'Peserta didik tidak ditemukan.',
            'bulan.required'      => 'Bulan wajib diisi.',
            'tahun.required'      => 'Tahun wajib diisi.',
            '*.min'               => 'Nilai bintang minimal 2.',
            '*.max'               => 'Nilai bintang maksimal 5.',
        ];
    }
}
