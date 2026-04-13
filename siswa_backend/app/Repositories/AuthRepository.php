<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthRepositoryInterface
{
    public function __construct(protected User $model) {}

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(array $data): User
    {
        try {
            DB::table('users')->insert([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'password'   => bcrypt($data['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->findByEmail($data['email']);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function deleteTokens(User $user): void
    {
        try {
            $user->tokens()->delete();
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}