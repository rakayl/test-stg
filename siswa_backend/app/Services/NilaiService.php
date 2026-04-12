<?php
namespace App\Services;

use App\Repositories\Contracts\NilaiRepositoryInterface;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NilaiImport;
use App\Exports\NilaiExport;

class NilaiService
{
    public function __construct(
        protected NilaiRepositoryInterface $nilaiRepo
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->nilaiRepo->getAll($filters);
    }

    public function findById(int $id)
    {
        return $this->nilaiRepo->findById($id);
    }

    public function create(array $data)
    {
        return $this->nilaiRepo->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->nilaiRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->nilaiRepo->delete($id);
    }

    public function import($file)
    {
        Excel::import(new NilaiImport, $file);
        return true;
    }

    public function export()
    {
        return Excel::download(new NilaiExport, 'nilai.xlsx');
    }

    // Helper: konversi angka ke huruf (dipakai juga di view/resource)
    public function konversiHuruf(float $nilai): string
    {
        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 70 => 'B',
            $nilai >= 60 => 'C',
            default      => 'D',
        };
    }
}