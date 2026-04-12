<?php
// app/Repositories/NilaiRepository.php
namespace App\Repositories;

use App\Models\Nilai;
use App\Repositories\Contracts\NilaiRepositoryInterface;

class NilaiRepository implements NilaiRepositoryInterface
{
    public function __construct(protected Nilai $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->with('siswa');

        if (!empty($filters['siswa_id'])) {
            $query->where('siswa_id', $filters['siswa_id']);
        }
        if (!empty($filters['mapel'])) {
            $query->where('mapel', $filters['mapel']);
        }
        if (!empty($filters['kelas'])) {
            $query->where('kelas', $filters['kelas']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function findById(int $id)
    {
        return $this->model->with('siswa')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $nilai = $this->findById($id);
        $nilai->update($data);
        return $nilai;
    }

    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function getBySiswa(int $siswaId)
    {
        return $this->model->where('siswa_id', $siswaId)->get();
    }

    public function getByMapel(string $mapel)
    {
        return $this->model->with('siswa')->where('mapel', $mapel)->get();
    }
}