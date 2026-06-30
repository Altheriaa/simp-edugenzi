<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSertifikatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'mentor';
    }

    public function rules(): array
    {
        return [
            'enrollment_id'    => ['required', 'exists:enrollments,id'],
            'tgl_terbit'       => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.required' => 'Program pelatihan peserta wajib dipilih.',
            'enrollment_id.exists'   => 'Enrollment tidak ditemukan.',
            'tgl_terbit.required'    => 'Tanggal terbit wajib diisi.',
        ];
    }
}
