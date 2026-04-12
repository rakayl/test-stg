<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SiswaService;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use App\Http\Resources\SiswaResource;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function __construct(protected SiswaService $siswaService) {}

    public function index(Request $request)
    {
        $data = $this->siswaService->getAll($request->only(['kelas','search']));
        return SiswaResource::collection($data);
    }

    public function store(StoreSiswaRequest $request)
    {
        $siswa = $this->siswaService->create($request->validated());
        return new SiswaResource($siswa);
    }

    public function show(int $id)
    {
        $siswa = $this->siswaService->findById($id);
        return new SiswaResource($siswa);
    }

    public function update(UpdateSiswaRequest $request, int $id)
    {
        $siswa = $this->siswaService->update($id, $request->validated());
        return new SiswaResource($siswa);
    }

    public function destroy(int $id)
    {
        $this->siswaService->delete($id);
        return response()->json(['message' => 'Deleted'], 200);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        $this->siswaService->import($request->file('file'));
        return response()->json(['message' => 'Import berhasil']);
    }

    public function export()
    {
        return $this->siswaService->export();
    }
}
