<?php // app/Services/LogicalService.php
namespace App\Services;

class LogicalService
{
    private function konversiHuruf(float $nilai): string
    {
        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 70 => 'B',
            $nilai >= 60 => 'C',
            default      => 'D',
        };
    }

    // SOAL 1: Rata-rata nilai setiap siswa + konversi huruf
    public function rataRataPerSiswa(array $nilai): array
    {
        $grouped = [];
        foreach ($nilai as $item) {
            $grouped[$item['nama']][] = $item['nilai'];
        }
        $result = [];
        foreach ($grouped as $nama => $scores) {
            $avg = array_sum($scores) / count($scores);
            $result[] = [
                'nama'         => $nama,
                'rata_rata'    => round($avg, 2),
                'nilai_huruf'  => $this->konversiHuruf($avg),
            ];
        }
        return $result;
    }

    // SOAL 2: Pivot data
    public function pivotData(array $nilai): array
    {
        $pivot = [];
        foreach ($nilai as $item) {
            $pivot[$item['nama']][$item['mapel']] = $item['nilai'];
        }
        return $pivot;
    }

    // SOAL 3: Nilai tengah dari Matematika
    public function nilaiTengah(array $nilai): array
    {
        $math = array_filter($nilai, fn($i) => $i['mapel'] === 'Matematika');
        usort($math, fn($a, $b) => $a['nilai'] <=> $b['nilai']);
        $math = array_values($math);
        $mid = intdiv(count($math), 2);  // index tengah
        return [
            'nama'  => $math[$mid]['nama'],
            'nilai' => $math[$mid]['nilai'],
        ];
    }

    // SOAL 4: Distribusi A/B/C/D per mapel
    public function distribusiPerMapel(array $nilai): array
    {
        $result = [];
        foreach ($nilai as $item) {
            $mapel = $item['mapel'];
            $huruf = $this->konversiHuruf($item['nilai']);
            $result[$mapel][$huruf] = ($result[$mapel][$huruf] ?? 0) + 1;
        }
        return $result;
    }

    // SOAL 5: Rata-rata per mapel + konversi huruf
    public function rataRataPerMapel(array $nilai): array
    {
        $grouped = [];
        foreach ($nilai as $item) {
            $grouped[$item['mapel']][] = $item['nilai'];
        }
        $result = [];
        foreach ($grouped as $mapel => $scores) {
            $avg = array_sum($scores) / count($scores);
            $result[] = [
                'mapel'        => $mapel,
                'rata_rata'    => round($avg, 2),
                'nilai_huruf'  => $this->konversiHuruf($avg),
            ];
        }
        return $result;
    }
}
