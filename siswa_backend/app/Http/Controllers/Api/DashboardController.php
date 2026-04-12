<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function stats()
    {
        return response()->json([
            'data' => $this->dashboardService->getStats(),
        ]);
    }

    public function chart()
    {
        return response()->json([
            'data' => $this->dashboardService->getChartData(),
        ]);
    }
}