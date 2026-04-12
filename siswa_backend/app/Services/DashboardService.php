<?php
// app/Services/DashboardService.php
namespace App\Services;

use App\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardService
{
    public function __construct(
        protected DashboardRepositoryInterface $dashboardRepo
    ) {}

    public function getStats(): array
    {
        $raw = $this->dashboardRepo->getStats();

        return [
            'jumlah_siswa'    => $raw['jumlah_siswa'],
            'jumlah_kelas'    => $raw['jumlah_kelas'],
            'jumlah_mapel'    => $raw['jumlah_mapel'],
            'rata_rata_nilai' => round($raw['rata_rata_nilai'] ?? 0, 2),
        ];
    }

    public function getChartData(): array
    {
        $rows = $this->dashboardRepo->getRataRataPerKelas();

        // Format: [ { kelas: '7A', rata_rata: 78.5 }, ... ]
        return $rows->map(fn($row) => [
            'kelas'      => $row->kelas,
            'rata_rata'  => round($row->rata_rata, 2),
            'nilai_huruf'=> $this->konversiHuruf($row->rata_rata),
        ])->values()->toArray();
    }

    private function konversiHuruf(float $nilai): string
    {
        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 70 => 'B',
            $nilai >= 60 => 'C',
            default      => 'D',
        };
    }
}