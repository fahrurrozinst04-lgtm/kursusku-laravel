<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:courses,code'],
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'fee' => ['required', 'integer', 'min:0'],
            'quota' => ['required', 'integer', 'between:1,500'],
            'start_date' => ['required', 'date'],
            'status' => ['required', 'in:draft,open,closed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode kursus',
            'name' => 'nama kursus',
            'description' => 'deskripsi',
            'fee' => 'biaya',
            'quota' => 'kuota',
            'start_date' => 'tanggal mulai',
            'status' => 'status',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode kursus wajib diisi.',
            'code.max' => 'Kode kursus maksimal 20 karakter.',
            'code.unique' => 'Kode kursus sudah dipakai kursus lain.',
            'name.required' => 'Nama kursus wajib diisi.',
            'name.min' => 'Nama kursus minimal 3 karakter.',
            'name.max' => 'Nama kursus maksimal 120 karakter.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
            'fee.required' => 'Biaya wajib diisi.',
            'fee.integer' => 'Biaya harus berupa bilangan bulat.',
            'fee.min' => 'Biaya tidak boleh negatif.',
            'quota.required' => 'Kuota wajib diisi.',
            'quota.integer' => 'Kuota harus berupa bilangan bulat.',
            'quota.between' => 'Kuota harus antara 1 dan 500.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Tanggal mulai tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status hanya boleh draft, open, atau closed.',
        ];
    }
}
