<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNilaiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'siswa_id' => 'required|exists:siswas,id',
            'kelas'    => 'required|string|max:20',
            'mapel'    => 'required|string|max:100',
            'nilai'    => 'required|numeric|min:0|max:100',
        ];
    }
}