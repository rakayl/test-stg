<?php
namespace App\Imports;

use App\Models\Nilai;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class NilaiImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Cari siswa_id berdasarkan nama jika yang diimport pakai kolom nama
        $siswaId = $row['siswa_id']
            ?? Siswa::where('nama', $row['nama'] ?? '')->value('id');

        return new Nilai([
            'siswa_id' => $siswaId,
            'kelas'    => $row['kelas'],
            'mapel'    => $row['mapel'],
            'nilai'    => $row['nilai'],
        ]);
    }

    public function rules(): array
    {
        return [
            'kelas' => 'required|string',
            'mapel' => 'required|string',
            'nilai' => 'required|numeric|min:0|max:100',
        ];
    }
}