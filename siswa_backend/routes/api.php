<?php 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\NilaiController;
use App\Http\Controllers\Api\LogicalController;
use App\Http\Controllers\Api\DashboardController;

// Auth Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// Logical Test (Public - untuk testing)
Route::prefix('logical')->group(function () {
    Route::post('rata-rata',    [LogicalController::class, 'rataRata']);
    Route::post('pivot',        [LogicalController::class, 'pivot']);
    Route::post('nilai-tengah', [LogicalController::class, 'nilaiTengah']);
    Route::post('distribusi',   [LogicalController::class, 'distribusi']);
    Route::post('rata-mapel',   [LogicalController::class, 'rataMapel']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    // Siswa CRUD + Import/Export
    Route::post('siswas/import',  [SiswaController::class, 'import']);
    Route::get('siswas/export',   [SiswaController::class, 'export']);
    Route::apiResource('siswas', SiswaController::class);

    // Nilai CRUD + Import/Export
    Route::post('nilais/import',  [NilaiController::class, 'import']);
    Route::get('nilais/export',   [NilaiController::class, 'export']);
    Route::apiResource('nilais', NilaiController::class);

    // Dashboard stats
    Route::get('dashboard/stats',  [DashboardController::class, 'stats']);
    Route::get('dashboard/chart',  [DashboardController::class, 'chart']);
});
