<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    /** Daftar hari yang boleh dipilih (dipakai validasi dan dropdown). */
    public const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function authorize(): bool
    {
        // Pembatasan akses sudah ditangani middleware role:admin di routes/web.php
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('kode')) {
            $this->merge(['kode' => strtoupper(trim((string) $this->input('kode')))]);
        }
    }

    public function rules(): array
    {
        return [
            'kode'        => [
                'required', 'string', 'max:20',
                // saat edit, abaikan kode milik mata kuliah yang sedang diedit
                Rule::unique('courses', 'kode')->ignore($this->route('course')),
            ],
            'nama'        => ['required', 'string', 'max:255'],
            'sks'         => ['required', 'integer', 'between:1,6'],
            'dosen'       => ['required', 'string', 'max:255'],
            'hari'        => ['required', Rule::in(self::HARI)],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'ruangan'     => ['required', 'string', 'max:50'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode'        => 'kode mata kuliah',
            'nama'        => 'nama mata kuliah',
            'sks'         => 'SKS',
            'dosen'       => 'dosen pengampu',
            'hari'        => 'hari',
            'jam_mulai'   => 'jam mulai',
            'jam_selesai' => 'jam selesai',
            'ruangan'     => 'ruangan',
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => ':attribute wajib diisi.',
            'max'         => ':attribute maksimal :max karakter.',
            'integer'     => ':attribute harus berupa angka bulat.',
            'between'     => ':attribute harus antara :min sampai :max.',
            'in'          => ':attribute yang dipilih tidak valid.',
            'date_format' => ':attribute harus berformat jam:menit (contoh 08:00).',
            'kode.unique' => 'Kode mata kuliah sudah dipakai, gunakan kode lain.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ];
    }
}
