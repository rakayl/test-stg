<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\NilaiService;

class NilaiResource extends JsonResource
{
    public function toArray($request): array
    {
        $service = app(NilaiService::class);

        return [
            'id'          => $this->id,
            'siswa_id'    => $this->siswa_id,
            'siswa'       => $this->whenLoaded('siswa', fn() => [
                'id'   => $this->siswa->id,
                'nama' => $this->siswa->nama,
            ]),
            'kelas'       => $this->kelas,
            'mapel'       => $this->mapel,
            'nilai'       => $this->nilai,
            'nilai_huruf' => $service->konversiHuruf((float) $this->nilai),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}