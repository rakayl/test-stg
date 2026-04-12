<?php

namespace App\Repositories\Contracts;

interface NilaiRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
    public function getBySiswa(int $siswaId);
    public function getByMapel(string $mapel);
}