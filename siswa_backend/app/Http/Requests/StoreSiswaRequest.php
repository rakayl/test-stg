<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama'       => 'required|string|max:255',
            'kelas'      => 'required|string|max:20',
            'alamat'     => 'required|string|max:500',
            'coordinate' => [
                'nullable',
                'string',
                'regex:/^-?\d+(\.\d+)?,-?\d+(\.\d+)?$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'       => 'Nama siswa wajib diisi.',
            'kelas.required'      => 'Kelas wajib diisi.',
            'alamat.required'     => 'Alamat wajib diisi.',
            'coordinate.regex'    => 'Format koordinat tidak valid. Gunakan format: -7.123,110.456',
        ];
    }
}