<?php
namespace App\Services;

use App\Repositories\Contracts\NilaiRepositoryInterface;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        try {
            $nilai = $this->nilaiRepo->create($data);
            DB::commit();
            return $nilai;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $nilai = $this->nilaiRepo->update($id, $data);
            DB::commit();
            return $nilai;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->nilaiRepo->delete($id);
            DB::commit();
            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function import($file)
    {
        DB::beginTransaction();
        try {
            Excel::import(new NilaiImport, $file);
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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