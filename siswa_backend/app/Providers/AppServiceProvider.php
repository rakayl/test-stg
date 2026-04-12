<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Auth
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\AuthRepository;

// Siswa
use App\Repositories\Contracts\SiswaRepositoryInterface;
use App\Repositories\SiswaRepository;

// Nilai
use App\Repositories\Contracts\NilaiRepositoryInterface;
use App\Repositories\NilaiRepository;

// Dashboard
use App\Repositories\Contracts\DashboardRepositoryInterface;
use App\Repositories\DashboardRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(SiswaRepositoryInterface::class, SiswaRepository::class);
        $this->app->bind(NilaiRepositoryInterface::class, NilaiRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
