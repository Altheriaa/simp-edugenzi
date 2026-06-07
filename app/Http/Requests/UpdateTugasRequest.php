<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'mentor';
    }

    public function rules(): array
    {
        return [
            'user_id'        => ['required', 'exists:users,id'],
            'judul_task'     => ['required', 'string', 'max:150'],
            'deskripsi_task' => ['nullable', 'string'],
            'prioritas'      => ['required', 'in:rendah,sedang,tinggi'],
            'deadline'       => ['nullable', 'date'],
            'status_task'    => ['required', 'in:to_do,in_progress,done'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'    => 'Peserta didik wajib dipilih.',
            'user_id.exists'      => 'Peserta didik tidak ditemukan.',
            'judul_task.required' => 'Judul tugas wajib diisi.',
            'prioritas.in'        => 'Prioritas harus rendah, sedang, atau tinggi.',
        ];
    }
}
