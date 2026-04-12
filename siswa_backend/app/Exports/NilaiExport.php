<?php
namespace App\Exports;

use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NilaiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Nilai::with('siswa')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Siswa', 'Kelas', 'Mata Pelajaran', 'Nilai', 'Nilai Huruf'];
    }

    public function map($row): array
    {
        $huruf = match(true) {
            $row->nilai >= 85 => 'A',
            $row->nilai >= 70 => 'B',
            $row->nilai >= 60 => 'C',
            default           => 'D',
        };

        return [
            $row->id,
            $row->siswa->nama ?? '-',
            $row->kelas,
            $row->mapel,
            $row->nilai,
            $huruf,
        ];
    }
}