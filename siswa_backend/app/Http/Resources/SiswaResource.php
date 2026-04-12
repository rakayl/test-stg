<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiswaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'nama'       => $this->nama,
            'kelas'      => $this->kelas,
            'alamat'     => $this->alamat,
            'coordinate' => $this->coordinate,
            'lat'        => $this->coordinate
                                ? (float) explode(',', $this->coordinate)[0]
                                : null,
            'lng'        => $this->coordinate
                                ? (float) explode(',', $this->coordinate)[1]
                                : null,
            'nilais'     => NilaiResource::collection(
                                $this->whenLoaded('nilais')
                            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}