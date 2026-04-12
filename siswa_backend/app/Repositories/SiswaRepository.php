<?php

namespace App\Repositories;

use App\Models\Siswa;
use App\Repositories\Contracts\SiswaRepositoryInterface;

class SiswaRepository implements SiswaRepositoryInterface
{
    public function __construct(protected Siswa $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with('nilais');
        if (!empty($filters['kelas'])) {
            $query->where('kelas', $filters['kelas']);
        }
        if (!empty($filters['search'])) {
            $query->where('nama', 'ilike', '%' . $filters['search'] . '%');
        }
        return $query->orderBy('nama')->paginate(10);
    }
    public function findById(int $id)
    {
        return $this->model->with('nilais')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $siswa = $this->findById($id);
        $siswa->update($data);
        return $siswa;
    }

    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }
}
