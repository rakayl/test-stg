<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama'       => 'sometimes|string|max:255',
            'kelas'      => 'sometimes|string|max:20',
            'alamat'     => 'sometimes|string|max:500',
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
            'coordinate.regex' => 'Format koordinat tidak valid. Gunakan format: -7.123,110.456',
        ];
    }
}