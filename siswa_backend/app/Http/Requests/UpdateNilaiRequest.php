<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNilaiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'siswa_id' => 'sometimes|exists:siswas,id',
            'kelas'    => 'sometimes|string|max:20',
            'mapel'    => 'sometimes|string|max:100',
            'nilai'    => 'sometimes|numeric|min:0|max:100',
        ];
    }
}