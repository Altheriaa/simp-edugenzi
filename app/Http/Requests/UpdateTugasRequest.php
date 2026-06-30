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
        $tugas = $this->route('tugas');
        $proyek = $tugas ? $tugas->proyek : null;

        return [
            'user_id'        => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($proyek, $tugas) {
                    if (!$proyek) {
                        return;
                    }
                    if ($tugas && $value == $tugas->user_id) {
                        return;
                    }
                    $query = \App\Models\User::where('id', $value)
                        ->where('role', 'peserta_didik')
                        ->where('status', 'aktif');

                    if ($proyek->program_pelatihan_id || $proyek->jenis_kelas_id || $proyek->durasi_pelatihan) {
                        $query->whereHas('enrollments', function ($q) use ($proyek) {
                            $q->where('status', 'aktif');
                            if ($proyek->program_pelatihan_id) {
                                $q->where('program_pelatihan_id', $proyek->program_pelatihan_id);
                            }
                            if ($proyek->jenis_kelas_id) {
                                $q->where('jenis_kelas_id', $proyek->jenis_kelas_id);
                            }
                            if ($proyek->durasi_pelatihan) {
                                $q->where('durasi_pelatihan', $proyek->durasi_pelatihan);
                            }
                        });
                    }

                    if (!$query->exists()) {
                        $fail('Peserta didik yang dipilih tidak terdaftar di kelas/program proyek ini.');
                    }
                }
            ],
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
