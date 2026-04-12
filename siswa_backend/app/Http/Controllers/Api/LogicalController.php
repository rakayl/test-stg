<?php // app/Http/Controllers/Api/LogicalController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LogicalService;
use Illuminate\Http\Request;

class LogicalController extends Controller
{
    public function __construct(protected LogicalService $service) {}

    private function getNilai(Request $request): array
    {
        // Bisa dari request body atau pakai data default
        return $request->input('nilai', [
            ['nama' => 'Andi',  'mapel' => 'Matematika', 'nilai' => 80],
            ['nama' => 'Budi',  'mapel' => 'Matematika', 'nilai' => 60],
            ['nama' => 'Citra', 'mapel' => 'Matematika', 'nilai' => 90],
            ['nama' => 'Andi',  'mapel' => 'Bahasa',     'nilai' => 70],
            ['nama' => 'Budi',  'mapel' => 'Bahasa',     'nilai' => 75],
            ['nama' => 'Citra', 'mapel' => 'Bahasa',     'nilai' => 85],
        ]);
    }

    public function rataRata(Request $request)
    {
        $result = $this->service->rataRataPerSiswa($this->getNilai($request));
        return response()->json(['data' => $result]);
    }

    public function pivot(Request $request)
    {
        $result = $this->service->pivotData($this->getNilai($request));
        return response()->json(['data' => $result]);
    }

    public function nilaiTengah(Request $request)
    {
        $result = $this->service->nilaiTengah($this->getNilai($request));
        return response()->json(['data' => $result]);
    }

    public function distribusi(Request $request)
    {
        $result = $this->service->distribusiPerMapel($this->getNilai($request));
        return response()->json(['data' => $result]);
    }

    public function rataMapel(Request $request)
    {
        $result = $this->service->rataRataPerMapel($this->getNilai($request));
        return response()->json(['data' => $result]);
    }
}
