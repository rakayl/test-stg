<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Siswa::select('id', 'nama', 'kelas', 'alamat', 'coordinate', 'created_at')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nama', 'Kelas', 'Alamat', 'Coordinate', 'Dibuat'];
    }
}