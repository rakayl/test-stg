<?php

namespace App\Repositories;

use App\Models\Siswa;
use App\Repositories\Contracts\SiswaRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::table('siswas')->insert(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            return $this->model->where('nis', $data['nis'])->firstOrFail();
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $siswa = $this->findById($id);
            $siswa->update($data);
            return $siswa;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->findById($id)->delete();
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
