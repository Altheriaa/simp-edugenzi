<?php

namespace App\Http\Requests;

use App\Models\Enrollment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdatePenilaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'mentor';
    }

    public function rules(): array
    {
        // Tentukan batas bulan_ke berdasarkan durasi pelatihan
        $maxBulan = 6;
        if ($this->filled('enrollment_id')) {
            $enrollment = Enrollment::find($this->enrollment_id);
            if ($enrollment) {
                $maxBulan = $enrollment->getDurasiBulan() ?: 6;
            }
        }

        return [
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'bulan_ke'   => [
                'required', 
                'integer', 
                'min:1', 
                "max:{$maxBulan}",
                Rule::unique('penilaian')->where(fn ($query) => $query->where('enrollment_id', $this->enrollment_id))->ignore($this->penilaian)
            ],
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
            'enrollment_id.required' => 'Enrollment/Peserta wajib dipilih.',
            'enrollment_id.exists'   => 'Enrollment tidak valid.',
            'bulan_ke.required'      => 'Bulan pelatihan wajib dipilih.',
            'bulan_ke.min'           => 'Bulan pelatihan minimal Bulan Ke-1.',
            'bulan_ke.max'           => 'Bulan pelatihan melebihi durasi pelatihan peserta.',
            'bulan_ke.unique'        => 'Penilaian untuk bulan ini sudah ada.',
            '*.min'                  => 'Nilai bintang minimal 2.',
            '*.max'                  => 'Nilai bintang maksimal 5.',
        ];
    }
}
