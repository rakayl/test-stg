<?php
namespace App\Repositories\Contracts;

interface DashboardRepositoryInterface
{
    public function getStats(): array;
    public function getRataRataPerKelas();
}