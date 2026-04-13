<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Siswa([
            'nama'       => $row['nama']       ?? null,
            'kelas'      => $row['kelas']      ?? null,
            'alamat'     => $row['alamat']     ?? null,
            'coordinate' => $row['coordinate'] ?? null,
        ]);
    }
}