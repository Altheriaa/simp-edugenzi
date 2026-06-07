<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProyekRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'mentor';
    }

    public function rules(): array
    {
        return [
            'nama_proyek'   => ['required', 'string', 'max:100'],
            'deskripsi'     => ['nullable', 'string'],
            'tgl_mulai'     => ['required', 'date'],
            'tgl_selesai'   => ['required', 'date', 'after_or_equal:tgl_mulai'],
            'status_proyek' => ['required', 'in:berjalan,selesai,tertunda'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_proyek.required'   => 'Nama proyek wajib diisi.',
            'tgl_mulai.required'     => 'Tanggal mulai wajib diisi.',
            'tgl_selesai.required'   => 'Tanggal selesai wajib diisi.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'status_proyek.in'       => 'Status proyek tidak valid.',
        ];
    }
}
