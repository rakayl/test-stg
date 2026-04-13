<?php

namespace App\Services;

use App\Repositories\Contracts\SiswaRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;

class SiswaService
{
    public function __construct(
        protected SiswaRepositoryInterface $siswaRepo
    ) {}

    public function findById(int $id)
    {
        return $this->siswaRepo->findById($id);
    }

    public function getAll(array $filters = [])
    {
        return $this->siswaRepo->getAll($filters);
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $siswa = $this->siswaRepo->create($data);
            DB::commit();
            return $siswa;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $siswa = $this->siswaRepo->update($id, $data);
            DB::commit();
            return $siswa;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->siswaRepo->delete($id);
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
            Excel::import(new SiswaImport, $file);
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function export()
    {
        return Excel::download(new SiswaExport, 'siswa.xlsx');
    }
}
