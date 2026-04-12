<?php

namespace App\Services;

use App\Repositories\Contracts\SiswaRepositoryInterface;
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
        return $this->siswaRepo->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->siswaRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->siswaRepo->delete($id);
    }

    public function import($file)
    {
        Excel::import(new SiswaImport, $file);
        return true;
    }

    public function export()
    {
        return Excel::download(new SiswaExport, 'siswa.xlsx');
    }
}
