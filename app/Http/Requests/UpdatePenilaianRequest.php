<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePenilaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'mentor';
    }

    public function rules(): array
    {
        // Tentukan batas bulan_ke berdasarkan durasi pelatihan peserta
        $maxBulan = 6;
        if ($this->filled('peserta_id')) {
            $peserta = User::find($this->peserta_id);
            if ($peserta && str_contains($peserta->durasi_pelatihan ?? '', '3 Bulan')) {
                $maxBulan = 3;
            }
        }

        return [
            'peserta_id' => ['required', 'exists:users,id'],
            'bulan_ke'   => ['required', 'integer', 'min:1', "max:{$maxBulan}"],
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
            'bulan_ke.required'   => 'Bulan pelatihan wajib dipilih.',
            'bulan_ke.min'        => 'Bulan pelatihan minimal Bulan Ke-1.',
            'bulan_ke.max'        => 'Bulan pelatihan melebihi durasi pelatihan peserta.',
            '*.min'               => 'Nilai bintang minimal 2.',
            '*.max'               => 'Nilai bintang maksimal 5.',
        ];
    }
}
