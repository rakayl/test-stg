<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected AuthRepositoryInterface $authRepo
    ) {}

    public function register(array $data): array
    {
        DB::beginTransaction();
        try {
            $user  = $this->authRepo->create($data);
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return [
                'user'  => $user,
                'token' => $token,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login(array $data): array
    {
        $user = $this->authRepo->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        DB::beginTransaction();
        try {
            $this->authRepo->deleteTokens($user);
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return [
                'user'  => $user,
                'token' => $token,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function logout(User $user): void
    {
        DB::beginTransaction();
        try {
            $this->authRepo->deleteTokens($user);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}