<?php
namespace App\Repositories;

use App\Models\Siswa;
use App\Models\Nilai;
use App\Repositories\Contracts\DashboardRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getStats(): array
    {
        return [
            'jumlah_siswa'    => Siswa::count(),
            'jumlah_kelas'    => Siswa::distinct('kelas')->count('kelas'),
            'jumlah_mapel'    => Nilai::distinct('mapel')->count('mapel'),
            'rata_rata_nilai' => Nilai::avg('nilai'),
        ];
    }

    public function getRataRataPerKelas()
    {
        // Rata-rata nilai per kelas, dikelompokkan dari tabel nilais
        return Nilai::select('kelas', DB::raw('AVG(nilai) as rata_rata'))
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();
    }
}