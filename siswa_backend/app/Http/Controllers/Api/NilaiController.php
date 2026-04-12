<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NilaiService;
use App\Http\Requests\StoreNilaiRequest;
use App\Http\Requests\UpdateNilaiRequest;
use App\Http\Resources\NilaiResource;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function __construct(protected NilaiService $nilaiService) {}

    public function index(Request $request)
    {
        $data = $this->nilaiService->getAll(
            $request->only(['siswa_id', 'mapel', 'kelas'])
        );
        return NilaiResource::collection($data);
    }

    public function store(StoreNilaiRequest $request)
    {
        $nilai = $this->nilaiService->create($request->validated());
        return new NilaiResource($nilai);
    }

    public function show(int $id)
    {
        $nilai = $this->nilaiService->findById($id);
        return new NilaiResource($nilai);
    }

    public function update(UpdateNilaiRequest $request, int $id)
    {
        $nilai = $this->nilaiService->update($id, $request->validated());
        return new NilaiResource($nilai);
    }

    public function destroy(int $id)
    {
        $this->nilaiService->delete($id);
        return response()->json(['message' => 'Deleted'], 200);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        $this->nilaiService->import($request->file('file'));
        return response()->json(['message' => 'Import berhasil']);
    }

    public function export()
    {
        return $this->nilaiService->export();
    }
}